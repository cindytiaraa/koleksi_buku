<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Pesanan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScannerController extends Controller
{
    /**
     * Halaman Scanner Barcode (Praktikum 1)
     */
    public function index()
    {
        return view('admin.scanner.index');
    }

    /**
     * API: Cek data buku berdasarkan Barcode (Praktikum 1)
     */
    public function cekData($kode)
    {
        // Jika yang di-scan adalah URL (dari QR Code lama)
        if (filter_var($kode, FILTER_VALIDATE_URL) || strpos($kode, 'admin/buku/') !== false) {
            $parts = explode('/', rtrim($kode, '/'));
            $id = end($parts);
            $buku = Buku::where('idbuku', $id)->first();
        } else {
            // Jika yang di-scan adalah Kode Buku (dari Barcode)
            $buku = Buku::where('kode', $kode)->first();
        }

        if (!$buku) {
            return response()->json([
                'success' => false,
                'message' => 'Data buku tidak ditemukan (' . $kode . ')'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'idbuku' => $buku->idbuku, // Menampilkan ID Barang
                'kode' => $buku->kode,
                'judul' => $buku->judul,   // Menampilkan Nama Barang
                'pengarang' => $buku->pengarang,
                'harga' => $buku->harga ? 'Rp ' . number_format($buku->harga, 0, ',', '.') : 'Gratis Pinjam', // Menampilkan Harga Barang
                'kategori' => $buku->kategori->nama_kategori ?? '-',
                'stok' => $buku->stok ?? 0,
                'gambar' => $buku->gambar ? asset('storage/' . $buku->gambar) : asset('assets/images/no-image.png')
            ]
        ]);
    }

    /**
     * Halaman Scanner QR Code Admin (Praktikum 2)
     */
    public function indexQr()
    {
        return view('admin.scanner.qrcode');
    }

    /**
     * API: Cek QR Code Transaksi (Praktikum 2 - Pembelian & Peminjaman)
     */
    public function cekQr($id)
    {
        // 1. Cek jika QR Code adalah Pembelian Buku / Pesanan (Alur Kantin)
        // Format QR: PESANAN-ID-{id} atau ORDER-{timestamp}-{rand}
        if (str_starts_with($id, 'PESANAN-ID-') || str_starts_with($id, 'ORDER-') || is_numeric($id)) {
            $pesananId = $id;
            if (str_starts_with($id, 'PESANAN-ID-')) {
                $pesananId = str_replace('PESANAN-ID-', '', $id);
            }

            $pesanan = is_numeric($pesananId)
                ? Pesanan::with(['user', 'detail.buku'])->find($pesananId)
                : Pesanan::with(['user', 'detail.buku'])->where('midtrans_order_id', $pesananId)->first();

            if ($pesanan) {
                $items = [];
                foreach ($pesanan->detail as $det) {
                    $items[] = [
                        'judul' => $det->buku->judul ?? $det->kode_buku,
                        'jumlah' => $det->jumlah,
                        'harga' => 'Rp ' . number_format($det->harga, 0, ',', '.'),
                        'subtotal' => 'Rp ' . number_format($det->subtotal, 0, ',', '.')
                    ];
                }

                return response()->json([
                    'success' => true,
                    'type' => 'pesanan',
                    'data' => [
                        'idpesanan' => $pesanan->idpesanan,
                        'midtrans_order_id' => $pesanan->midtrans_order_id,
                        'nama_customer' => $pesanan->nama ?? ($pesanan->user->name ?? 'Guest'),
                        'tanggal' => $pesanan->created_at->format('d M Y H:i'),
                        'total' => 'Rp ' . number_format($pesanan->total, 0, ',', '.'),
                        'status_bayar' => $pesanan->status_bayar, // 0 = belum, 1 = lunas
                        'status_label' => $pesanan->status_bayar == 1 ? 'Lunas' : 'Belum Lunas',
                        'metode_bayar' => $pesanan->metode_bayar_label,
                        'items' => $items
                    ]
                ]);
            }
        }

        // 2. Cek jika QR Code adalah Peminjaman Buku
        // Format QR: PEMINJAMAN-ID-{id}
        if (str_starts_with($id, 'PEMINJAMAN-ID-')) {
            $pemId = str_replace('PEMINJAMAN-ID-', '', $id);
            $peminjaman = Peminjaman::with(['user', 'buku'])->find($pemId);

            if ($peminjaman) {
                // Hitung denda dinamis
                $denda = 0;
                $tglRencana = $peminjaman->tgl_kembali_rencana;
                $tglAktual = $peminjaman->tgl_kembali_aktual ?? Carbon::now();
                if ($tglAktual->greaterThan($tglRencana)) {
                    $denda = $tglAktual->diffInDays($tglRencana) * 1000;
                }

                return response()->json([
                    'success' => true,
                    'type' => 'peminjaman',
                    'data' => [
                        'idpeminjaman' => $peminjaman->idpeminjaman,
                        'nama_anggota' => $peminjaman->user->name ?? 'Anggota',
                        'buku_judul' => $peminjaman->buku->judul ?? $peminjaman->kode_buku,
                        'buku_kode' => $peminjaman->kode_buku,
                        'buku_pengarang' => $peminjaman->buku->pengarang ?? '-',
                        'tgl_pinjam' => $peminjaman->tgl_pinjam->format('d M Y'),
                        'tgl_kembali_rencana' => $peminjaman->tgl_kembali_rencana->format('d M Y'),
                        'tgl_kembali_aktual' => $peminjaman->tgl_kembali_aktual ? $peminjaman->tgl_kembali_aktual->format('d M Y') : '-',
                        'status' => $peminjaman->status, // 0 = dipinjam, 1 = dikembalikan, 2 = terlambat
                        'status_label' => $peminjaman->status_label,
                        'denda' => 'Rp ' . number_format($peminjaman->denda > 0 ? $peminjaman->denda : $denda, 0, ',', '.'),
                        'catatan' => $peminjaman->catatan ?? '-'
                    ]
                ]);
            }
        }

        // Jika tidak ditemukan
        return response()->json([
            'success' => false,
            'message' => 'Transaksi atau Peminjaman tidak ditemukan (' . $id . ')'
        ], 404);
    }

    /**
     * API: Proses Pembayaran Pesanan Lunas di Tempat (Sisi Admin)
     */
    public function prosesBayarQr($id)
    {
        $pesanan = Pesanan::findOrFail($id);
        
        if ($pesanan->status_bayar == 1) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan ini sudah lunas sebelumnya.'
            ], 400);
        }

        $pesanan->update([
            'status_bayar' => 1
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran pesanan ' . $pesanan->midtrans_order_id . ' berhasil dikonfirmasi Lunas!'
        ]);
    }

    /**
     * API: Proses Pengembalian Buku via QR Code (Sisi Admin)
     */
    public function prosesKembaliQr($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->status == 1) {
            return response()->json([
                'success' => false,
                'message' => 'Buku sudah dikembalikan sebelumnya.'
            ], 400);
        }

        $tglRencana = $peminjaman->tgl_kembali_rencana;
        $tglAktual  = Carbon::now();
        $denda      = 0;
        $status     = 1; // dikembalikan

        if ($tglAktual->greaterThan($tglRencana)) {
            $selisihHari = $tglAktual->diffInDays($tglRencana);
            $denda       = $selisihHari * 1000; // Rp 1.000/hari
            $status      = 2; // terlambat
        }

        $peminjaman->update([
            'tgl_kembali_aktual' => $tglAktual,
            'status'             => $status,
            'denda'              => $denda,
            'idpetugas'          => auth()->id(), // Petugas yang memproses pengembalian
        ]);

        $msg = 'Buku berhasil dikembalikan.';
        if ($denda > 0) {
            $msg .= " Terlambat " . $selisihHari . " hari. Denda keterlambatan sebesar Rp " . number_format($denda, 0, ',', '.') . " telah dicatat.";
        }

        return response()->json([
            'success' => true,
            'message' => $msg
        ]);
    }
}
