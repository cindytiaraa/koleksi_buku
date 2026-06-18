<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            // Tambah iduser agar pesanan bisa dilacak per user
            $table->unsignedBigInteger('iduser')->nullable()->after('nama');
            $table->foreign('iduser')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropForeign(['iduser']);
            $table->dropColumn('iduser');
        });
    }
};