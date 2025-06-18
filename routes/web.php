<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\PeminjamanNewController;
use App\Http\Controllers\PeminjamanNewDetailController;
use Illuminate\Support\Facades\Auth;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Public routes (akses tanpa login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post');
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'handleRegister'])->name('register.post');
});

// Routes hanya untuk user login
Route::middleware('auth')->group(function () {

    // Detail Peminjaman (jika kamu ingin mengatur detail barang yg dipinjam)
    Route::get('/peminjaman', [PeminjamanNewController::class, 'index'])->name('listpeminjaman.index');
    Route::get('/peminjaman/{id}/detail', [PeminjamanNewDetailController::class, 'index'])->name('peminjaman.detail');
    Route::post('/peminjaman/{id}/detail', [PeminjamanNewDetailController::class, 'store'])->name('peminjaman.detail.store');
    Route::put('/peminjaman/{id}/detail/processing', [PeminjamanNewDetailController::class, 'update'])->name('peminjaman.detail.update');
    Route::get('/peminjaman/jadwal/{tanggal}', [PeminjamanNewController::class, 'getJadwalByTanggal']);

    // Barang dan lainnya
    Route::get('/listbarang', [BarangController::class, 'index'])->name('listbarang.index');
    Route::post('/listbarang', [BarangController::class, 'store'])->name('listbarang.store');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/listusers', [UserController::class, 'index'])->name('listusers.index');

    // Routes untuk role admin dan mahasiswa
    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::get('/listusers', [UserController::class, 'index'])->name('listusers.index');
        Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

        Route::get('/pengajuan', [PengajuanController::class, 'index'])->name('pengajuan');
        Route::get('/pengajuan/{id}', [PengajuanController::class, 'show'])->name('pengajuan.show');
        Route::post('/pengajuan/{id}/approve', [PengajuanController::class, 'approve'])->name('pengajuan.approve');
        Route::post('/pengajuan/{id}/decline', [PengajuanController::class, 'decline'])->name('pengajuan.decline');
        Route::post('/pengajuan/{id}/finish', [PengajuanController::class, 'finish'])->name('pengajuan.finish');
    });



    Route::middleware('role:mahasiswa')->group(function () {
        Route::get('/peminjaman/buat', [PeminjamanNewController::class, 'create'])->name('listpeminjaman.create');
        Route::post('/peminjaman/buat', [PeminjamanNewController::class, 'store'])->name('listpeminjaman.store');
    });

});

// use App\Http\Controllers\AuthController;
// use App\Http\Controllers\BarangController;
// use App\Http\Controllers\PeminjamanController;
// use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\HomeController;
// use App\Http\Controllers\PengajuanController;
// use App\Http\Controllers\UserController;

// // Public route (bisa diakses tanpa login)
// Route::get('/', [HomeController::class, 'index'])->name('home');
// Route::get('/login', [AuthController::class, 'login'])->name('login');
// Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post'); // Proses form login

// Route::get('/register', [AuthController::class, 'register'])->name('register');
// Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');


// // Route yang hanya bisa diakses setelah login
// Route::middleware(['auth'])->group(function () {
//     Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('listpeminjaman.index');
//     Route::get('/peminjaman/buat', [PeminjamanController::class, 'create'])->name('listpeminjaman.create');
//     Route::post('/peminjaman/buat', [PeminjamanController::class, 'store'])->name('listpeminjaman.store');
//     Route::get('/peminjaman/jadwal/{tanggal}', [PeminjamanController::class, 'getJadwalByTanggal']);

//     Route::get('/list-barang', [BarangController::class, 'index'])->name('listbarang.index');
//     Route::get('/listusers', [UserController::class, 'index'])->name('listusers.index');
//     Route::get('/pengajuan', [PengajuanController::class, 'index'])->name('pengajuan');
// });
