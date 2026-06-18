<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('riwayat_stok', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id');
            $table->string('barcode_toko');
            $table->unsignedBigInteger('buku_id');
            $table->integer('stok_sebelum');
            $table->integer('stok_tambah');
            $table->integer('stok_sesudah');
            $table->timestamp('created_at')->useCurrent();

            $table->index('vendor_id');
            $table->index('barcode_toko');
            $table->index('buku_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('riwayat_stok');
    }
};
