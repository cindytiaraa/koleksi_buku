<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Vendor;
use App\Models\Pesanan;
use App\Models\Kategori;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    // Ambil vendor milik user yang login
    private function getVendor()
    {
        return Vendor::where('iduser', auth()->user()->id)->firstOrFail();
    }

    public function dashboard()
    {
        $vendor        = $this->getVendor();
        $totalPesanan  = Pesanan::whereHas('detail', function($q) use ($vendor) {
            $q->whereIn('kode_buku',
                Buku::where('idvendor', $vendor->idvendor)->pluck('kode')
            );
        })->where('status_bayar', 1)->count();

        $totalBuku = Buku::where('idvendor', $vendor->idvendor)->count();

        return view('vendor.dashboard', compact('vendor', 'totalPesanan', 'totalBuku'));
    }

    public function pesanan()
    {
        $vendor  = $this->getVendor();
        $kodeBuku = Buku::where('idvendor', $vendor->idvendor)->pluck('kode');

        $pesanan = Pesanan::whereHas('detail', function($q) use ($kodeBuku) {
            $q->whereIn('kode_buku', $kodeBuku);
        })
        ->where('status_bayar', 1)
        ->with('detail')
        ->latest()
        ->get();

        return view('vendor.pesanan', compact('vendor', 'pesanan'));
    }

    public function buku()
    {
        $vendor   = $this->getVendor();
        $buku     = Buku::where('idvendor', $vendor->idvendor)->with('kategori')->get();
        $kategori = Kategori::all();
        return view('vendor.buku', compact('vendor', 'buku', 'kategori'));
    }

    public function bukuStore(Request $request)
    {
        $vendor = $this->getVendor();

        $request->validate([
            'judul'      => 'required',
            'pengarang'  => 'required',
            'idkategori' => 'required',
            'harga'      => 'required|integer|min:0',
        ]);

        $kategori    = Kategori::findOrFail($request->idkategori);
        $jumlahBuku  = Buku::where('idkategori', $kategori->idkategori)->count() + 1;
        $kodeBuku    = $kategori->kode_kategori . '-' . str_pad($jumlahBuku, 3, '0', STR_PAD_LEFT);

        Buku::create([
            'kode'       => $kodeBuku,
            'judul'      => $request->judul,
            'pengarang'  => $request->pengarang,
            'idkategori' => $request->idkategori,
            'harga'      => $request->harga,
            'status'     => 1,
            'idvendor'   => $vendor->idvendor,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Buku berhasil ditambahkan'
        ]);
    }

    public function bukuDestroy($id)
    {
        $vendor = $this->getVendor();
        $buku   = Buku::where('idbuku', $id)
                      ->where('idvendor', $vendor->idvendor)
                      ->firstOrFail();
        $buku->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Buku berhasil dihapus'
        ]);
    }
}