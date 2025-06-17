<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Rute untuk mendapatkan daftar pengguna yang berstatus 'Requesting'
Route::get('/users/requesting', [UserController::class, 'getRequestingUsers']);

// Rute untuk menyetujui pengguna dengan peran yang dipilih dari modal dropdown
Route::post('/users/{user}/approve', [UserController::class, 'approveUser']);

// ✅ Rute baru untuk menyetujui pengguna sebagai Mahasiswa
Route::post('/users/{user}/approve/mahasiswa', [UserController::class, 'approveUserAsMahasiswa']);

// ✅ Rute baru untuk menyetujui pengguna sebagai Admin
Route::post('/users/{user}/approve/admin', [UserController::class, 'approveUserAsAdmin']);

Route::get('/user', function (Request $request) {
    return $request->user();
});
