<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table      = 'pesanan';
    protected $primaryKey = 'idpesanan';
    protected $fillable   = [
        'nama', 'timestamp', 'total',
        'metode_bayar', 'status_bayar',
        'midtrans_order_id', 'snap_token'
    ];

    public function detail()
    {
        return $this->hasMany(DetailPesanan::class, 'idpesanan', 'idpesanan');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'iduser', 'id');
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->status_bayar == 1 ? 'Lunas' : 'Pending';
    }

    public function getMetodeBayarLabelAttribute(): string
    {
        return match($this->metode_bayar) {
            1 => 'Virtual Account',
            2 => 'QRIS / GoPay',
            default => '-',
        };
    }
}