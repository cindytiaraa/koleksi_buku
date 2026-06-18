<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPesanan extends Model
{
    protected $table      = 'detail_pesanan';
    protected $primaryKey = 'iddetail_pesanan';
    protected $fillable   = [
        'idpesanan', 'kode_buku', 'jumlah',
        'harga', 'subtotal', 'catatan'
    ];

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'kode_buku', 'kode');
    }
}