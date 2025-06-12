<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjaman_new', function (Blueprint $table) {
            if (!Schema::hasColumn('peminjaman_new', 'nama_acara')) {
            $table->string('nama_acara')->after('user_id');
            $table->string('lokasi_acara')->after('nama_acara');
            $table->date('tanggal_kembali')->nullable(false)->change(); // Ubah jadi NOT NULL
            }
        });
    }

    public function down(): void
    {
        Schema::table('peminjaman_new', function (Blueprint $table) {
            $table->dropColumn(['nama_acara', 'lokasi_acara']);
            $table->date('tanggal_kembali')->nullable()->change(); // Kembalikan jadi NULLABLE
        });
    }
};

