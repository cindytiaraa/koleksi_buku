<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\BukuController;
use App\Models\Buku;
use Illuminate\Http\Request;
class DatatablesManual extends Controller
{
    public function index()
    {
        $buku = Buku::with('kategori')->get();
        return view('admin.datatables_manual', compact('buku'));
    }

    public function getData()
    {
        $buku = Buku::with('kategori')->get();
        return response()->json($buku);
    }
}