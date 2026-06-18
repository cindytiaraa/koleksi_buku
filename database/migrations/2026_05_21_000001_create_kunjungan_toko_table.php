<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('kunjungan_toko', function (Blueprint $table) {
            $table->id();
            $table->string('barcode_toko');
            $table->double('latitude_sales', 15, 8);
            $table->double('longitude_sales', 15, 8);
            $table->double('accuracy_sales')->nullable();
            $table->double('jarak')->nullable();
            $table->string('status_kunjungan')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kunjungan_toko');
    }
};
