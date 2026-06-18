<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('penjualan_detail', function (Blueprint $table) {
            $table->increments('idpenjualan_detail');
            $table->unsignedInteger('id_penjualan');
            $table->string('id_barang', 20); // pakai kode buku
            $table->smallInteger('jumlah');
            $table->integer('subtotal');

            $table->foreign('id_penjualan')
                ->references('id_penjualan')
                ->on('penjualan')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualan_detail');
    }
};
