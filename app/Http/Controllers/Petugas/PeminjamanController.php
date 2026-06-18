<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    /**
     * Daftar semua peminjaman (aktif & historis)
     */
    public function index(Request $request)
    {
        $query = Peminjaman::with(['user', 'buku', 'petugas'])->latest();

        if ($request->status !== null && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn($u) => $u->where('name', 'like', "%$search%"))
                  ->orWhere('kode_buku', 'like', "%$search%");
            });
        }

        $peminjaman = $query->paginate(15)->withQueryString();
        $users = User::where('role', 3)->get(); // role 3 = user/anggota

        return view('petugas.peminjaman.index', compact('peminjaman', 'users'));
    }

    /**
     * Form tambah peminjaman
     */
    public function create()
    {
        $users = User::where('role', 3)->get();
        $buku  = Buku::where('status', 1)->get();
        return view('petugas.peminjaman.create', compact('users', 'buku'));
    }

    /**
     * Simpan peminjaman baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'iduser'               => 'required|exists:users,id',
            'kode_buku'            => 'required|exists:buku,kode',
            'tgl_pinjam'           => 'required|date',
            'tgl_kembali_rencana'  => 'required|date|after_or_equal:tgl_pinjam',
            'catatan'              => 'nullable|string|max:500',
        ]);

        // Cek apakah buku sedang dipinjam
        $sedangDipinjam = Peminjaman::where('kode_buku', $request->kode_buku)
            ->where('status', 0)
            ->exists();

        if ($sedangDipinjam) {
            return back()->withErrors(['kode_buku' => 'Buku ini sedang dipinjam oleh anggota lain.'])->withInput();
        }

        Peminjaman::create([
            'iduser'              => $request->iduser,
            'idpetugas'           => auth()->id(),
            'kode_buku'           => $request->kode_buku,
            'tgl_pinjam'          => $request->tgl_pinjam,
            'tgl_kembali_rencana' => $request->tgl_kembali_rencana,
            'status'              => 0,
            'denda'               => 0,
            'catatan'             => $request->catatan,
        ]);

        return redirect()->route('petugas.peminjaman.index')
            ->with('success', 'Peminjaman berhasil dicatat.');
    }

    /**
     * Proses pengembalian buku
     */
    public function kembalikan(Request $request, $id)
    {
        $request->validate([
            'tgl_kembali_aktual' => 'required|date',
        ]);

        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->status == 1) {
            return back()->with('error', 'Buku sudah dikembalikan sebelumnya.');
        }

        $tglRencana = $peminjaman->tgl_kembali_rencana;
        $tglAktual  = \Carbon\Carbon::parse($request->tgl_kembali_aktual);
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
        ]);

        $msg = 'Buku berhasil dikembalikan.';
        if ($denda > 0) {
            $msg .= " Denda keterlambatan: Rp " . number_format($denda, 0, ',', '.');
        }

        return redirect()->route('petugas.peminjaman.index')->with('success', $msg);
    }

    /**
     * AJAX: cari buku berdasarkan kode
     */
    public function cariBuku(Request $request)
    {
        $buku = Buku::where('kode', 'like', '%' . $request->q . '%')
            ->orWhere('judul', 'like', '%' . $request->q . '%')
            ->where('status', 1)
            ->limit(10)
            ->get(['kode', 'judul', 'pengarang']);

        return response()->json($buku);
    }
}
