<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use Illuminate\Http\Request;

class PenjualanController extends Controller
{
    /**
     * Halaman POS / kasir petugas
     */
    public function index()
    {
        return view('petugas.penjualan.pos');
    }

    /**
     * Riwayat penjualan
     */
    public function riwayat(Request $request)
    {
        $query = Penjualan::with('detail')->latest('timestamp');

        if ($request->tanggal) {
            $query->whereDate('timestamp', $request->tanggal);
        }

        $penjualan = $query->paginate(15)->withQueryString();
        $totalHari = Penjualan::whereDate('timestamp', today())->sum('total');

        return view('petugas.penjualan.riwayat', compact('penjualan', 'totalHari'));
    }

    /**
     * AJAX: cari buku by kode
     */
    public function cariBuku(Request $request)
    {
        $buku = Buku::where('kode', $request->kode)
            ->where('status', 1)
            ->first();

        if (!$buku) {
            return response()->json([
                'status'  => 'error',
                'code'    => 404,
                'message' => 'Buku tidak ditemukan atau tidak tersedia',
                'data'    => null,
            ], 404);
        }

        return response()->json([
            'status'  => 'success',
            'code'    => 200,
            'message' => 'Buku ditemukan',
            'data'    => [
                'kode'  => $buku->kode,
                'nama'  => $buku->judul,
                'harga' => $buku->harga ?? 0,
            ],
        ]);
    }

    /**
     * AJAX: simpan transaksi penjualan tunai
     */
    public function bayar(Request $request)
    {
        $items = $request->items;
        $total = $request->total;

        if (!$items || count($items) === 0) {
            return response()->json([
                'status'  => 'error',
                'code'    => 400,
                'message' => 'Tidak ada item transaksi',
                'data'    => null,
            ], 400);
        }

        $penjualan = Penjualan::create([
            'timestamp' => now(),
            'total'     => $total,
        ]);

        foreach ($items as $item) {
            PenjualanDetail::create([
                'id_penjualan' => $penjualan->id_penjualan,
                'id_barang'    => $item['kode'],
                'jumlah'       => $item['jumlah'],
                'subtotal'     => $item['subtotal'],
            ]);
        }

        return response()->json([
            'status'  => 'success',
            'code'    => 200,
            'message' => 'Transaksi berhasil disimpan',
            'data'    => ['id_penjualan' => $penjualan->id_penjualan],
        ]);
    }
}
