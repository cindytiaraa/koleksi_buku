<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $kategori = Kategori::withCount('buku')->get();
        
        return view('admin.dashboard', [
            'totalKategori' => Kategori::count(),
            'totalBuku' => Buku::count(),
            'totalUser' => User::count(),
            'tersedia' => Buku::where('status','tersedia')->count(),
            'tidakTersedia' => Buku::where('status','tidak tersedia')->count(),
            'bukuTerbaru' => Buku::with('kategori')->latest()->limit(5)->get(),
            'kategoriLabels' => $kategori->pluck('nama_kategori'),
            'kategoriData' => $kategori->pluck('buku_count'),
        ]);
    }
}
