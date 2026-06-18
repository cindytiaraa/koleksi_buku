<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('antrian', function (Blueprint $table) {
            $table->id();
            $table->string('kode_antrian', 10);
            $table->string('nama_pengunjung', 100);
            $table->enum('status', ['menunggu', 'dipanggil', 'selesai', 'terlambat'])->default('menunggu');
            $table->timestamp('dipanggil_pada')->nullable();
            $table->date('tanggal_antrian');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('antrian');
    }
};
