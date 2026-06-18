<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('kunjungan_vendor', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id');
            $table->string('barcode_toko');
            $table->double('latitude_vendor', 15, 8);
            $table->double('longitude_vendor', 15, 8);
            $table->double('accuracy_vendor')->nullable();
            $table->double('jarak')->nullable();
            $table->double('threshold_efektif')->nullable();
            $table->string('status_kunjungan');
            $table->timestamp('waktu_kunjungan')->useCurrent();

            $table->index('vendor_id');
            $table->index('barcode_toko');
        });
    }

    public function down()
    {
        Schema::dropIfExists('kunjungan_vendor');
    }
};
