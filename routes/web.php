<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/listpeminjaman', [PageController::class, 'listpeminjaman'])->name('listpeminjaman.index');
Route::get('/listbarang', [PageController::class, 'listbarang'])->name('listbarang.index');
Route::get('/listusers', [PageController::class, 'listusers'])->name('listusers.index');
Route::get('/pengajuan', [PageController::class, 'pengajuan'])->name('pengajuan');
