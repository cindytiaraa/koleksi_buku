<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->increments('idpeminjaman');
            $table->unsignedBigInteger('iduser');          // user yang meminjam
            $table->unsignedBigInteger('idpetugas');       // petugas yang memproses
            $table->string('kode_buku', 50);               // buku yang dipinjam
            $table->date('tgl_pinjam');
            $table->date('tgl_kembali_rencana');
            $table->date('tgl_kembali_aktual')->nullable();
            $table->tinyInteger('status')->default(0);     // 0=dipinjam, 1=dikembalikan, 2=terlambat
            $table->integer('denda')->default(0);          // denda keterlambatan (Rp)
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('iduser')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('idpetugas')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('kode_buku')->references('kode')->on('buku')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};