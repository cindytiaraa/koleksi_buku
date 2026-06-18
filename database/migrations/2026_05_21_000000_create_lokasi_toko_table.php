<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('lokasi_toko', function (Blueprint $table) {
            $table->string('barcode')->primary();
            $table->string('nama_toko');
            $table->double('latitude', 15, 8);
            $table->double('longitude', 15, 8);
            $table->double('accuracy')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lokasi_toko');
    }
};
