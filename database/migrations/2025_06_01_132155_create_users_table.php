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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 30);
            $table->string('nrp', 20)->nullable();
            $table->string('jurusan', 100)->nullable();
            $table->string('nip', 20)->nullable();
            $table->string('email', 255)->unique();
            $table->string('password', 255);
            $table->string('no_hp', 20);
            $table->enum('status', ['Requesting', 'Accepted']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
