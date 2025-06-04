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
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kategori_id')->nullable();
            $table->foreign('kategori_id')->references('id')->on('kategori')->onDelete('set null');
            $table->string('item', 255);
            $table->integer('jumlah_unit');
            $table->string('lokasi', 255)->nullable(); // barang ini di uppk mana
            $table->enum('status', ['Borrowed', 'Returned'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
