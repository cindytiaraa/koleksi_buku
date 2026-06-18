<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('detail_pesanan')) {
            Schema::create('detail_pesanan', function (Blueprint $table) {
                $table->increments('iddetail_pesanan');
                $table->unsignedInteger('idpesanan');
                $table->string('kode_buku', 20);
                $table->integer('jumlah');
                $table->integer('harga');
                $table->integer('subtotal');
                $table->timestamp('timestamp')->useCurrent();
                $table->string('catatan', 255)->nullable();
                $table->timestamps();

                $table->foreign('idpesanan')
                    ->references('idpesanan')
                    ->on('pesanan')
                    ->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_pesanan');
    }
};
