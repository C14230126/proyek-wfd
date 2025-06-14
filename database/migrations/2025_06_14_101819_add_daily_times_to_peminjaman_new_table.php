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
        Schema::table('peminjaman_new', function (Blueprint $table) {
            // Tambahkan kolom 'daily_times' dengan tipe JSON
            // Gunakan after() untuk menempatkannya setelah kolom tertentu (opsional)
            $table->json('daily_times')->nullable()->after('lokasi_acara');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman_new', function (Blueprint $table) {
            $table->dropColumn('daily_times');
        });
    }
};