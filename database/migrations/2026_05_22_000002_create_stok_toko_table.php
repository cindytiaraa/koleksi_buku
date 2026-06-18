<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('stok_toko', function (Blueprint $table) {
            $table->id();
            $table->string('barcode_toko');
            $table->unsignedBigInteger('buku_id');
            $table->integer('jumlah_stok')->default(0);
            $table->timestamps();

            $table->unique(['barcode_toko', 'buku_id']);
            $table->index('barcode_toko');
        });
    }

    public function down()
    {
        Schema::dropIfExists('stok_toko');
    }
};
