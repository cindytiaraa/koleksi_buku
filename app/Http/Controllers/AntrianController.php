<?php

namespace App\Http\Controllers;

use App\Models\Antrian;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class AntrianController extends Controller
{
    // =============================================
    // HALAMAN LANDING PAGE
    // =============================================
    public function landing()
    {
        return view('antrian.landing');
    }

    // =============================================
    // HALAMAN GUEST - Form ambil antrian
    // =============================================
    public function guest()
    {
        return view('antrian.guest');
    }

    // =============================================
    // PROSES AMBIL ANTRIAN (POST dari guest)
    // =============================================
    public function ambilAntrian(Request $request)
    {
        $request->validate([
            'nama_pengunjung' => 'required|string|max:100|min:2',
        ], [
            'nama_pengunjung.required' => 'Nama wajib diisi.',
            'nama_pengunjung.min'      => 'Nama minimal 2 karakter.',
            'nama_pengunjung.max'      => 'Nama maksimal 100 karakter.',
        ]);

        $kode = Antrian::generateKode();
        $today = Carbon::today()->toDateString();

        $antrian = Antrian::create([
            'kode_antrian'    => $kode,
            'nama_pengunjung' => trim($request->nama_pengunjung),
            'status'          => 'menunggu',
            'tanggal_antrian' => $today,
        ]);

        // Jika request AJAX/fetch (dari JS), kembalikan JSON
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success'   => true,
                'tiket_url' => route('antrian.tiket', $antrian->id),
                'kode'      => $antrian->kode_antrian,
            ]);
        }

        // Redirect biasa (fallback)
        return redirect()->route('antrian.tiket', $antrian->id);
    }

    // =============================================
    // HALAMAN TIKET PENGUNJUNG (tab baru)
    // =============================================
    public function tiket($id)
    {
        $antrian = Antrian::findOrFail($id);
        return view('antrian.tiket', compact('antrian'));
    }

    // =============================================
    // HALAMAN ADMIN/PETUGAS - Dashboard antrian
    // =============================================
    public function adminAntrian()
    {
        $daftarMenunggu = Antrian::hariIni()
            ->where('status', 'menunggu')
            ->orderBy('id')
            ->get();

        $sedangDipanggil = Antrian::hariIni()
            ->where('status', 'dipanggil')
            ->latest('dipanggil_pada')
            ->first();

        $daftarTerlambat = Antrian::hariIni()
            ->where('status', 'terlambat')
            ->orderBy('id')
            ->get();

        $totalSelesai = Antrian::hariIni()
            ->where('status', 'selesai')
            ->count();

        return view('antrian.index', compact(
            'daftarMenunggu',
            'sedangDipanggil',
            'daftarTerlambat',
            'totalSelesai'
        ));
    }

    // =============================================
    // HALAMAN PAPAN ANTRIAN (publik)
    // =============================================
    public function papanAntrian()
    {
        return view('antrian.papan');
    }

    // =============================================
    // SSE ENDPOINT - Stream realtime
    // =============================================
    public function sseAntrian()
    {
        $dipanggil = Antrian::hariIni()
            ->where('status', 'dipanggil')
            ->latest('dipanggil_pada')
            ->first();

        $menunggu = Antrian::hariIni()
            ->where('status', 'menunggu')
            ->orderBy('id')
            ->get();

        $terlambat = Antrian::hariIni()
            ->where('status', 'terlambat')
            ->orderBy('id')
            ->get();

        $totalSelesai = Antrian::hariIni()
            ->where('status', 'selesai')
            ->count();

        return response()->json([
            'dipanggil' => $dipanggil,
            'menunggu' => $menunggu,
            'terlambat' => $terlambat,
            'total_selesai' => $totalSelesai,
        ]);
    }


    // =============================================
    // API: Panggil berikutnya
    // =============================================
    public function panggilBerikutnya(Request $request)
    {
        $today = Carbon::today()->toDateString();

        // Set antrian yang sedang dipanggil menjadi selesai
        Antrian::where('tanggal_antrian', $today)
            ->where('status', 'dipanggil')
            ->update(['status' => 'selesai']);

        // Ambil antrian menunggu berikutnya
        $next = Antrian::where('tanggal_antrian', $today)
            ->where('status', 'menunggu')
            ->orderBy('id')
            ->first();

        if (!$next) {
            return response()->json(['success' => false, 'message' => 'Tidak ada antrian menunggu.']);
        }

        $next->update([
            'status'        => 'dipanggil',
            'dipanggil_pada' => now(),
        ]);

        return response()->json([
            'success'  => true,
            'antrian'  => [
                'id'              => $next->id,
                'kode_antrian'    => $next->kode_antrian,
                'nama_pengunjung' => $next->nama_pengunjung,
            ],
        ]);
    }

    // =============================================
    // API: Skip antrian (tandai selesai)
    // =============================================
    public function skipAntrian($id)
    {
        $antrian = Antrian::findOrFail($id);

        $antrian->update([
            'status' => 'selesai'
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    // =============================================
    // API: Tandai terlambat
    // =============================================
    public function tandaiTerlambat(Request $request, $id)
    {
        $antrian = Antrian::findOrFail($id);
        $antrian->update(['status' => 'terlambat']);

        return response()->json(['success' => true, 'message' => 'Antrian ditandai terlambat.']);
    }

    // =============================================
    // API: Panggil ulang terlambat
    // =============================================
    public function panggilUlang(Request $request, $id)
    {
        $today = Carbon::today()->toDateString();

        // Selesaikan yang sedang dipanggil
        Antrian::where('tanggal_antrian', $today)
            ->where('status', 'dipanggil')
            ->update(['status' => 'selesai']);

        $antrian = Antrian::findOrFail($id);
        $antrian->update([
            'status'        => 'dipanggil',
            'dipanggil_pada' => now(),
        ]);

        return response()->json([
            'success' => true,
            'antrian' => [
                'id'              => $antrian->id,
                'kode_antrian'    => $antrian->kode_antrian,
                'nama_pengunjung' => $antrian->nama_pengunjung,
            ],
        ]);
    }

    // =============================================
    // ADMIN: Reset antrian harian
    // =============================================
    public function resetAntrian(Request $request)
    {
        $today = Carbon::today()->toDateString();

        // Tandai semua antrian hari ini yang belum selesai sebagai selesai
        Antrian::where('tanggal_antrian', $today)
            ->whereIn('status', ['menunggu', 'dipanggil', 'terlambat'])
            ->update(['status' => 'selesai']);

        return response()->json(['success' => true, 'message' => 'Antrian hari ini telah direset.']);
    }

    // =============================================
    // ADMIN: Riwayat semua antrian
    // =============================================
    public function riwayat(Request $request)
    {
        $query = Antrian::orderBy('tanggal_antrian', 'desc')->orderBy('id', 'desc');

        if ($request->filled('tanggal')) {
            $query->where('tanggal_antrian', $request->tanggal);
        }

        $riwayat = $query->paginate(20);

        return view('antrian.riwayat', compact('riwayat'));
    }
}
