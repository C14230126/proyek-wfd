<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\PeminjamanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/listpeminjaman', [PeminjamanController::class, 'index'])->name('listpeminjaman.index');
Route::get('/listbarang', [BarangController::class, 'index'])->name('listbarang.index');
Route::get('/listusers', [UserController::class, 'index'])->name('listusers.index');
Route::get('/pengajuan', [PengajuanController::class, 'index'])->name('pengajuan');
