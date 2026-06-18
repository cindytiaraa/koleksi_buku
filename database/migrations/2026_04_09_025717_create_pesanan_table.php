<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanan', function (Blueprint $table) {
            $table->increments('idpesanan');
            $table->string('nama', 255);           // nama guest
            $table->timestamp('timestamp')->useCurrent();
            $table->integer('total');
            $table->tinyInteger('metode_bayar');   // 1=VA, 2=QRIS
            $table->smallInteger('status_bayar')->default(0); // 0=pending, 1=lunas
            $table->string('midtrans_order_id', 100)->nullable();
            $table->string('snap_token', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
