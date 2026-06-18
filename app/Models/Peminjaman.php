<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table      = 'peminjaman';
    protected $primaryKey = 'idpeminjaman';

    protected $fillable = [
        'iduser',
        'idpetugas',
        'kode_buku',
        'tgl_pinjam',
        'tgl_kembali_rencana',
        'tgl_kembali_aktual',
        'status',
        'denda',
        'catatan',
    ];

    protected $casts = [
        'tgl_pinjam'           => 'date',
        'tgl_kembali_rencana'  => 'date',
        'tgl_kembali_aktual'   => 'date',
    ];

    // Relasi ke user peminjam
    public function user()
    {
        return $this->belongsTo(User::class, 'iduser', 'id');
    }

    // Relasi ke petugas
    public function petugas()
    {
        return $this->belongsTo(User::class, 'idpetugas', 'id');
    }

    // Relasi ke buku
    public function buku()
    {
        return $this->belongsTo(Buku::class, 'kode_buku', 'kode');
    }

    // Status label
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            0 => 'Dipinjam',
            1 => 'Dikembalikan',
            2 => 'Terlambat',
            default => 'Unknown',
        };
    }

    // Hitung denda otomatis (Rp 1.000/hari)
    public function hitungDenda(): int
    {
        if ($this->status == 1 || !$this->tgl_kembali_aktual) {
            return 0;
        }
        $terlambat = $this->tgl_kembali_aktual->diffInDays($this->tgl_kembali_rencana, false);
        return $terlambat < 0 ? abs($terlambat) * 1000 : 0;
    }
}
