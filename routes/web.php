<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\PeminjamanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\UserController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('listpeminjaman.index');
Route::get('/peminjaman/buat', [PeminjamanController::class, 'create'])->name('listpeminjaman.create');
Route::get('/listbarang', [BarangController::class, 'index'])->name('listbarang.index');
Route::get('/listusers', [UserController::class, 'index'])->name('listusers.index');
Route::get('/pengajuan', [PengajuanController::class, 'index'])->name('pengajuan');
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::get('/register', [AuthController::class, 'register'])->name('register');
