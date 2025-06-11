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
        Schema::create('peminjaman_new_detail', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('peminjaman_new_id');
        $table->unsignedBigInteger('barang_id');
        $table->integer('jumlah');
        $table->timestamps();

        $table->foreign('peminjaman_new_id')->references('id')->on('peminjaman_new')->onDelete('cascade');
        $table->foreign('barang_id')->references('id')->on('barangs')->onDelete('cascade');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman_new_detail');
    }
};
