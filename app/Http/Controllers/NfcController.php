<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Absensi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NfcController extends Controller
{
    /**
     * Tampilkan halaman registrasi KTM NFC.
     * Form berisi: Nama, NIM, tombol Aktifkan NFC.
     */
    public function registerForm()
    {
        return view('nfc.register');
    }

    /**
     * Simpan data mahasiswa baru beserta serial NFC KTM-nya.
     * Jika NIM sudah ada, update nfc_serial-nya.
     */
    public function registerStore(Request $request)
    {
        $request->validate([
            'nama'       => 'required|string|max:255',
            'nim'        => 'required|string|max:50',
            'nfc_serial' => 'required|string',
        ]);

        // Cek jika serial NFC sudah dipakai mahasiswa lain
        $existing = Mahasiswa::where('nfc_serial', $request->nfc_serial)
                             ->where('nim', '!=', $request->nim)
                             ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'NFC serial ini sudah terdaftar atas nama: ' . $existing->nama . ' (' . $existing->nim . ').',
            ], 400);
        }

        // Buat atau update mahasiswa berdasarkan NIM
        $mahasiswa = Mahasiswa::updateOrCreate(
            ['nim' => $request->nim],
            [
                'nama'       => $request->nama,
                'nfc_serial' => $request->nfc_serial,
            ]
        );

        return response()->json([
            'success'   => true,
            'message'   => 'KTM mahasiswa berhasil didaftarkan.',
            'mahasiswa' => [
                'id'         => $mahasiswa->id,
                'nama'       => $mahasiswa->nama,
                'nim'        => $mahasiswa->nim,
                'nfc_serial' => $mahasiswa->nfc_serial,
            ],
        ]);
    }

    /**
     * Tampilkan halaman scanner absensi.
     * Tidak ada form input — hanya tombol dan area hasil scan.
     */
    public function scannerForm()
    {
        return view('nfc.scanner');
    }

    /**
     * Endpoint POST: terima serial NFC, cari mahasiswa, catat absensi.
     */
    public function scanApi(Request $request)
    {
        $request->validate([
            'serial_number' => 'required|string',
        ]);

        $serial = $request->serial_number;

        // Cari mahasiswa berdasarkan nfc_serial
        $mahasiswa = Mahasiswa::where('nfc_serial', $serial)->first();

        if (!$mahasiswa) {
            return response()->json([
                'success' => false,
                'message' => 'KTM belum terdaftar. Silakan lakukan registrasi terlebih dahulu.',
            ], 404);
        }

        // Catat absensi
        $tanggal = Carbon::today()->toDateString();
        $waktu   = Carbon::now()->toTimeString();

        $absensi = Absensi::create([
            'mahasiswa_id' => $mahasiswa->id,
            'tanggal'      => $tanggal,
            'waktu'        => $waktu,
        ]);

        return response()->json([
            'success'   => true,
            'message'   => 'Absensi berhasil dicatat.',
            'mahasiswa' => [
                'id'   => $mahasiswa->id,
                'nama' => $mahasiswa->nama,
                'nim'  => $mahasiswa->nim,
            ],
            'absensi' => [
                'tanggal' => $absensi->tanggal,
                'waktu'   => $absensi->waktu,
            ],
        ]);
    }

    /**
     * Placeholder untuk route nfc.riwayat.form (jika ada di sidebar user)
     * Arahkan ke scanner saja.
     */
    public function riwayatForm()
    {
        return redirect()->route('nfc.scanner.form');
    }
}
