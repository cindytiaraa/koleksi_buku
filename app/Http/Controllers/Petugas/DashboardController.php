<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Penjualan;
use App\Models\Buku;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPinjamAktif    = Peminjaman::where('status', 0)->count();
        $totalPinjamHari     = Peminjaman::whereDate('created_at', today())->count();
        $totalKembaliHari    = Peminjaman::whereDate('updated_at', today())->whereIn('status',[1,2])->count();
        $totalPenjualanHari  = Penjualan::whereDate('timestamp', today())->sum('total');
        $totalBuku           = Buku::where('status', 1)->count();
        $totalAnggota        = User::where('role', 3)->count();

        // Peminjaman hampir jatuh tempo (3 hari ke depan)
        $hampirJatuhTempo = Peminjaman::where('status', 0)
            ->whereBetween('tgl_kembali_rencana', [today(), today()->addDays(3)])
            ->with(['user', 'buku'])
            ->get();

        // Peminjaman terlambat
        $terlambat = Peminjaman::where('status', 0)
            ->where('tgl_kembali_rencana', '<', today())
            ->with(['user', 'buku'])
            ->get();

        return view('petugas.dashboard', compact(
            'totalPinjamAktif',
            'totalPinjamHari',
            'totalKembaliHari',
            'totalPenjualanHari',
            'totalBuku',
            'totalAnggota',
            'hampirJatuhTempo',
            'terlambat',
        ));
    }
}
