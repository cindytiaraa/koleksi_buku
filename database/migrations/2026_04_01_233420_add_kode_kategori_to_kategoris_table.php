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
        Schema::table('kategori', function (Blueprint $table) {
            if (!Schema::hasColumn('kategori', 'kode_kategori')) {
                $table->string('kode_kategori', 20)->nullable()->after('nama_kategori');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kategori', function (Blueprint $table) {
            if (Schema::hasColumn('kategori', 'kode_kategori')) {
                $table->dropColumn('kode_kategori');
            }
        });
    }
};
