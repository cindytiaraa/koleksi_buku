<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;
class DashboardController extends Controller
{
    /**
     * Dashboard utama vendor - katalog buku
     */
    public function index(Request $request)
    {
        $query = Buku::with('kategori')->where('idvendor', auth()->id());

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

        return view('vendor.dashboard', compact('buku', 'kategori'));
    }
}