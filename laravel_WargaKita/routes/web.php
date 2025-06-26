<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IuranController;
use App\Http\Controllers\Kartu_keluargaController;
use App\Http\Controllers\Kategori_golonganController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\Rukun_tetanggaController;
use App\Http\Controllers\TagihanController;
use App\Http\Controllers\WargaController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('warga', WargaController::class);
Route::resource('kartu_keluarga', Kartu_keluargaController::class);
Route::resource('rukun_tetangga', Rukun_tetanggaController::class);
Route::resource('pengumuman', PengumumanController::class);
Route::resource('tagihan', TagihanController::class);
Route::resource('iuran', IuranController::class);
Route::resource('kategori_golongan', Kategori_golonganController::class);
Route::resource('pengeluaran', PengeluaranController::class);

use App\Http\Controllers\LaporanController;

Route::get('/laporan_pengeluaran_bulanan/{bulan}/{tahun}', [LaporanController::class, 'pengeluaran_bulanan'])->name('pengeluaran_bulanan');

