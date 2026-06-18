<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Mahasiswa;
use App\Models\Absensi;

try {
    // 1. Buat mahasiswa baru (simulasi registrasi KTM)
    $mahasiswa = Mahasiswa::create([
        'nama'       => 'Cindy Tiara',
        'nim'        => '2023999001',
        'nfc_serial' => 'NFC_TEST_ABSENSI',
    ]);
    echo "✓ Mahasiswa terdaftar: {$mahasiswa->nama} ({$mahasiswa->nim})\n";

    // 2. Cari mahasiswa berdasarkan nfc_serial (simulasi scan harian)
    $found = Mahasiswa::where('nfc_serial', 'NFC_TEST_ABSENSI')->first();
    echo "✓ Mahasiswa ditemukan via NFC serial: {$found->nama}\n";

    // 3. Catat absensi (tanpa input nama/nim — hanya via serial)
    $absensi = Absensi::create([
        'mahasiswa_id' => $found->id,
        'tanggal'      => date('Y-m-d'),
        'waktu'        => date('H:i:s'),
    ]);
    echo "✓ Absensi dicatat: {$absensi->tanggal} pukul {$absensi->waktu}\n";
    echo "✓ Relasi: {$absensi->mahasiswa->nama}\n";

    // 4. Simulasi KTM belum terdaftar
    $notFound = Mahasiswa::where('nfc_serial', 'UNKNOWN_SERIAL')->first();
    echo "✓ Uji KTM tidak terdaftar: " . ($notFound ? "DITEMUKAN (salah)" : "Tidak ditemukan (benar)") . "\n";

    // Cleanup
    $absensi->delete();
    $mahasiswa->delete();
    echo "✓ Cleanup selesai.\n";

} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
