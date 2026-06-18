<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokToko extends Model
{
    use HasFactory;

    protected $table = 'stok_toko';

    protected $fillable = [
        'barcode_toko',
        'buku_id',
        'jumlah_stok'
    ];

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'buku_id', 'idbuku');
    }

    public function toko()
    {
        return $this->belongsTo(LokasiToko::class, 'barcode_toko', 'barcode');
    }
}
