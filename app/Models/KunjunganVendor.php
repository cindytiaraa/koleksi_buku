<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KunjunganVendor extends Model
{
    use HasFactory;

    protected $table = 'kunjungan_vendor';
    public $timestamps = false;

    protected $fillable = [
        'vendor_id',
        'barcode_toko',
        'latitude_vendor',
        'longitude_vendor',
        'accuracy_vendor',
        'jarak',
        'threshold_efektif',
        'status_kunjungan',
        'waktu_kunjungan'
    ];

    public function toko()
    {
        return $this->belongsTo(LokasiToko::class, 'barcode_toko', 'barcode');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id', 'idvendor');
    }
}
