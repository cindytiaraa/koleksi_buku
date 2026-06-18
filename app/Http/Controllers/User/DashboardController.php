<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;

class DashboardController extends Controller
{
    /**
     * Dashboard utama user - katalog buku
     */
    public function index(Request $request)
    {
        $query = Buku::with('kategori')->where('status', 1);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->search . '%')
                  ->orWhere('pengarang', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->kategori) {
            $query->where('idkategori', $request->kategori);
        }

        $buku      = $query->paginate(12)->withQueryString();
        $kategori  = Kategori::all();

        return view('user.dashboard', compact('buku', 'kategori'));
    }

    /**
     * Riwayat peminjaman user
     */
    public function riwayatPinjam()
    {
        $peminjaman = Peminjaman::where('iduser', auth()->id())
            ->with(['buku', 'petugas'])
            ->latest()
            ->paginate(10);

        return view('user.riwayat_pinjam', compact('peminjaman'));
    }

    /**
     * Riwayat pembelian / pesanan user
     */
    public function riwayatBeli()
    {
        $pesanan = Pesanan::where('iduser', auth()->id())
            ->with('detail')
            ->latest()
            ->paginate(10);

        return view('user.riwayat_beli', compact('pesanan'));
    }

    /**
     * Halaman order / beli buku (mirip order admin tapi untuk user)
     */
    public function order(Request $request)
    {
        $vendor = \App\Models\Vendor::all();
        return view('user.order', compact('vendor'));
    }

    /**
     * Checkout pembelian oleh user (sama seperti OrderController tapi attach iduser)
     */
    public function checkout(Request $request)
    {
        $items       = $request->items;
        $total       = $request->total;
        $metodeBayar = $request->metode_bayar;

        if (!$items || count($items) === 0) {
            return response()->json(['status' => 'error', 'message' => 'Keranjang kosong'], 400);
        }

        $user    = auth()->user();
        $orderId = 'ORDER-' . time() . '-' . rand(100, 999);

        $pesanan = Pesanan::create([
            'iduser'            => $user->id,
            'nama'              => $user->name,
            'total'             => $total,
            'metode_bayar'      => $metodeBayar,
            'status_bayar'      => 0,
            'midtrans_order_id' => $orderId,
        ]);

        foreach ($items as $item) {
            \App\Models\DetailPesanan::create([
                'idpesanan' => $pesanan->idpesanan,
                'kode_buku' => $item['kode'],
                'jumlah'    => $item['jumlah'],
                'harga'     => $item['harga'],
                'subtotal'  => $item['subtotal'],
                'catatan'   => $item['catatan'] ?? null,
            ]);
        }

        // Setup Midtrans
        Config::$serverKey    = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized  = true;
        Config::$is3ds        = true;

        $itemDetails = [];
        foreach ($items as $item) {
            $itemDetails[] = [
                'id'       => $item['kode'],
                'price'    => $item['harga'],
                'quantity' => $item['jumlah'],
                'name'     => substr($item['nama'], 0, 50),
            ];
        }

        $enabledPayments = $metodeBayar == 1
            ? ['bni_va', 'bri_va', 'bca_va', 'permata_va']
            : ['gopay', 'qris'];

        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => $total,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email'      => $user->email,
            ],
            'item_details'     => $itemDetails,
            'enabled_payments' => $enabledPayments,
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            $pesanan->snap_token = $snapToken;
            $pesanan->save();

            return response()->json([
                'status'     => 'success',
                'code'       => 200,
                'snap_token' => $snapToken,
                'order_id'   => $orderId,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal membuat transaksi: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Status pesanan user
     */
    public function statusOrder($midtrans_order_id)
    {
        $pesanan = Pesanan::where('midtrans_order_id', $midtrans_order_id)
            ->where('iduser', auth()->id())
            ->with('detail')
            ->firstOrFail();

        return view('user.status_order', compact('pesanan'));
    }

    /**
     * AJAX: cari buku untuk user (by vendor)
     */
    public function cariBuku(Request $request)
    {
        $buku = Buku::where('idvendor', $request->idvendor)
            ->where('status', 1)
            ->get()
            ->map(fn($b) => [
                'kode'  => $b->kode,
                'nama'  => $b->judul,
                'harga' => $b->harga ?? 0,
            ]);

        return response()->json(['status' => 'success', 'code' => 200, 'data' => $buku]);
    }

    /**
     * Halaman form booking peminjaman buku oleh user (Praktikum 2)
     */
    public function createPeminjaman($kode_buku)
    {
        $buku = Buku::where('kode', $kode_buku)->firstOrFail();
        
        // Cek ketersediaan buku
        $sedangDipinjam = Peminjaman::where('kode_buku', $kode_buku)
            ->where('status', 0)
            ->exists();
            
        return view('user.peminjaman.create', compact('buku', 'sedangDipinjam'));
    }

    /**
     * Simpan transaksi peminjaman baru dari user (booking online) (Praktikum 2)
     */
    public function storePeminjaman(Request $request)
    {
        $request->validate([
            'kode_buku' => 'required|exists:buku,kode',
            'tgl_kembali_rencana' => 'required|date|after_or_equal:today',
            'catatan' => 'nullable|string|max:500',
        ]);

        $kode_buku = $request->kode_buku;

        // Cek ketersediaan buku
        $sedangDipinjam = Peminjaman::where('kode_buku', $kode_buku)
            ->where('status', 0)
            ->exists();

        if ($sedangDipinjam) {
            return back()->withErrors(['kode_buku' => 'Buku ini sedang tidak tersedia untuk dipinjam.'])->withInput();
        }

        $peminjaman = Peminjaman::create([
            'iduser' => auth()->id(),
            'idpetugas' => null, // Belum diproses petugas
            'kode_buku' => $kode_buku,
            'tgl_pinjam' => \Carbon\Carbon::today(),
            'tgl_kembali_rencana' => \Carbon\Carbon::parse($request->tgl_kembali_rencana),
            'status' => 0, // Dipinjam / Aktif
            'denda' => 0,
            'catatan' => $request->catatan,
        ]);

        return redirect()->route('user.peminjaman.show', $peminjaman->idpeminjaman)
            ->with('success', 'Booking peminjaman berhasil dibuat! Silakan tunjukkan QR Code ke petugas perpustakaan.');
    }

    /**
     * Halaman detail transaksi peminjaman & Tampilan QR Code (Persisten) (Praktikum 2)
     */
    public function showPeminjaman($id)
    {
        $peminjaman = Peminjaman::with(['buku', 'user'])->findOrFail($id);

        if ($peminjaman->iduser !== auth()->id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk transaksi ini.');
        }

        return view('user.peminjaman.show', compact('peminjaman'));
    }
}
