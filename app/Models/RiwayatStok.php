<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatStok extends Model
{
    use HasFactory;

    protected $table = 'riwayat_stok';
    public $timestamps = false;

    protected $fillable = [
        'vendor_id',
        'barcode_toko',
        'buku_id',
        'stok_sebelum',
        'stok_tambah',
        'stok_sesudah',
        'created_at'
    ];

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'buku_id', 'idbuku');
    }

    public function toko()
    {
        return $this->belongsTo(LokasiToko::class, 'barcode_toko', 'barcode');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id', 'idvendor');
    }
}
