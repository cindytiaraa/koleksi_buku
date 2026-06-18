<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\KunjunganVendor;
use App\Models\StokToko;

class LokasiToko extends Model
{
    use HasFactory;

    protected $table = 'lokasi_toko';
    protected $primaryKey = 'barcode';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'barcode', 'nama_toko', 'latitude', 'longitude', 'accuracy'
    ];

    public function kunjunganVendor()
    {
        return $this->hasMany(KunjunganVendor::class, 'barcode_toko', 'barcode');
    }

    public function stok()
    {
        return $this->hasMany(StokToko::class, 'barcode_toko', 'barcode');
    }
}
