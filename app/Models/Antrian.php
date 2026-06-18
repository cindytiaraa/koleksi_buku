<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Antrian extends Model
{
    protected $table = 'antrian';

    protected $fillable = [
        'kode_antrian',
        'nama_pengunjung',
        'status',
        'dipanggil_pada',
        'tanggal_antrian',
    ];

    protected $casts = [
        'dipanggil_pada' => 'datetime',
        'tanggal_antrian' => 'date',
    ];

    /**
     * Generate kode antrian berikutnya untuk hari ini.
     * Format: A001, A002, dst.
     * Reset setiap hari berdasarkan tanggal_antrian.
     */
    public static function generateKode(): string
    {
        $today = Carbon::today()->toDateString();

        $last = self::where('tanggal_antrian', $today)
            ->orderBy('id', 'desc')
            ->first();

        if (!$last) {
            $nomor = 1;
        } else {
            // Ambil angka dari kode, misal "A012" -> 12
            $nomor = (int) substr($last->kode_antrian, 1) + 1;
        }

        return 'A' . str_pad($nomor, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Scope antrian hari ini
     */
    public function scopeHariIni($query)
    {
        return $query->where('tanggal_antrian', Carbon::today()->toDateString());
    }

    /**
     * Scope antrian menunggu hari ini
     */
    public function scopeMenunggu($query)
    {
        return $query->hariIni()->where('status', 'menunggu');
    }

    /**
     * Scope antrian terlambat hari ini
     */
    public function scopeTerlambat($query)
    {
        return $query->hariIni()->where('status', 'terlambat');
    }
}
