<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $table      = 'vendor';
    protected $primaryKey = 'idvendor';
    protected $fillable   = [
        'nama_vendor', 
        'iduser', 
        'latitude',
        'longitude',
        'accuracy',
        'barcode',
    ];

    public function buku()
    {
        // vendor punya buku via tabel buku (kita pakai idvendor di buku nanti)
        return $this->hasMany(Buku::class, 'idvendor', 'idvendor');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'iduser', 'id');
    }
}