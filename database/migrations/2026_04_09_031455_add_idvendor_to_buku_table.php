<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('buku', function (Blueprint $table) {
            if (!Schema::hasColumn('buku', 'idvendor')) {
                $table->unsignedInteger('idvendor')->nullable()->after('tipe');
            }
        });
    }

    public function down(): void
    {
        Schema::table('buku', function (Blueprint $table) {
            if (Schema::hasColumn('buku', 'idvendor')) {
                $table->dropColumn('idvendor');
            }
        });
    }
};
