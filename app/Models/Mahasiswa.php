<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswa';

    protected $fillable = [
        'nama',
        'nim',
        'nfc_serial',
    ];

    /**
     * Relasi ke tabel absensi
     */
    public function absensis()
    {
        return $this->hasMany(Absensi::class, 'mahasiswa_id', 'id');
    }
}
