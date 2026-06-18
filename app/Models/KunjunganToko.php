<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KunjunganToko extends Model
{
    use HasFactory;

    protected $table = 'kunjungan_toko';
    public $timestamps = false; // created_at managed manually

    protected $fillable = [
        'barcode_toko', 'latitude_sales', 'longitude_sales', 'accuracy_sales', 'jarak', 'status_kunjungan', 'created_at'
    ];
}
