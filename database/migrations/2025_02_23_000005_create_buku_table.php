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
        Schema::create('buku', function (Blueprint $table) {
            $table->integer('idbuku')->primary();
            $table->string('kode')->unique();
            $table->string('judul');
            $table->string('pengarang');
            $table->integer('idkategori');
            $table->string('status')->default('tersedia');
            $table->timestamps();
            
            // Foreign key constraint
            $table->foreign('idkategori')->references('idkategori')->on('kategori')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku');
    }
};
