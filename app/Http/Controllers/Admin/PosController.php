<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use Illuminate\Http\Request;

class PosController extends Controller
{
    public function index()
    {
        return view('admin.pos.index');
    }

    // AJAX: cari buku berdasarkan kode
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
                'data'    => null
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
            ]
        ]);
    }

    // AJAX: simpan transaksi
    public function bayar(Request $request)
    {
        $items = $request->items; // array dari JS
        $total = $request->total;

        if (!$items || count($items) === 0) {
            return response()->json([
                'status'  => 'error',
                'code'    => 400,
                'message' => 'Tidak ada item transaksi',
                'data'    => null
            ], 400);
        }

        // Simpan header penjualan
        $penjualan = Penjualan::create([
            'timestamp' => now(),
            'total'     => $total,
        ]);

        // Simpan detail
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
            'data'    => ['id_penjualan' => $penjualan->id_penjualan]
        ]);
    }
}