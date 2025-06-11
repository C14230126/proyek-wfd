<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\PeminjamanNewController;
use App\Http\Controllers\PeminjamanNewDetailController;

// ✅ Public routes (akses tanpa login)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// ✅ Routes hanya untuk user login
Route::middleware(['auth'])->group(function () {
    // ✅ Peminjaman Baru
    Route::get('/peminjaman', [PeminjamanNewController::class, 'index'])->name('listpeminjaman.index');
    Route::get('/peminjaman/buat', [PeminjamanNewController::class, 'create'])->name('listpeminjaman.create');
    Route::post('/peminjaman/buat', [PeminjamanNewController::class, 'store'])->name('listpeminjaman.store');
    Route::get('/peminjaman/jadwal/{tanggal}', [PeminjamanNewController::class, 'getJadwalByTanggal']);

    // ✅ Detail Peminjaman (jika kamu ingin mengatur detail barang yg dipinjam)
    Route::get('/peminjaman/{id}/detail', [PeminjamanNewDetailController::class, 'index'])->name('peminjaman.detail');
    Route::post('/peminjaman/{id}/detail', [PeminjamanNewDetailController::class, 'store'])->name('peminjaman.detail.store');

    // ✅ Barang dan lainnya
    Route::get('/list-barang', [BarangController::class, 'index'])->name('listbarang.index');
    Route::get('/listusers', [UserController::class, 'index'])->name('listusers.index');
    Route::get('/pengajuan', [PengajuanController::class, 'index'])->name('pengajuan');
});

// use App\Http\Controllers\AuthController;
// use App\Http\Controllers\BarangController;
// use App\Http\Controllers\PeminjamanController;
// use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\HomeController;
// use App\Http\Controllers\PengajuanController;
// use App\Http\Controllers\UserController;

// // ✅ Public route (bisa diakses tanpa login)
// Route::get('/', [HomeController::class, 'index'])->name('home');
// Route::get('/login', [AuthController::class, 'login'])->name('login');
// Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post'); // Proses form login

// Route::get('/register', [AuthController::class, 'register'])->name('register');
// Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');


// // ✅ Route yang hanya bisa diakses setelah login
// Route::middleware(['auth'])->group(function () {
//     Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('listpeminjaman.index');
//     Route::get('/peminjaman/buat', [PeminjamanController::class, 'create'])->name('listpeminjaman.create');
//     Route::post('/peminjaman/buat', [PeminjamanController::class, 'store'])->name('listpeminjaman.store');
//     Route::get('/peminjaman/jadwal/{tanggal}', [PeminjamanController::class, 'getJadwalByTanggal']);

//     Route::get('/list-barang', [BarangController::class, 'index'])->name('listbarang.index');
//     Route::get('/listusers', [UserController::class, 'index'])->name('listusers.index');
//     Route::get('/pengajuan', [PengajuanController::class, 'index'])->name('pengajuan');
// });
