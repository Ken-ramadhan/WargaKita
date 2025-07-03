<?php

use App\Http\Controllers\Rw\DashboardController;
use App\Http\Controllers\Rw\IuranController;
use App\Http\Controllers\Rw\Kartu_keluargaController;
use App\Http\Controllers\Rw\Kategori_golonganController;
use App\Http\Controllers\Rw\LaporanController;
use App\Http\Controllers\Rw\PengeluaranController;
use App\Http\Controllers\Rw\PengumumanController;
use App\Http\Controllers\Rw\Rukun_tetanggaController;
use App\Http\Controllers\Rw\TagihanController;
use App\Http\Controllers\Rw\WargaController;
use App\Http\Controllers\Warga\DashboardWargaController;
use App\Http\Controllers\Warga\PengumumanWargaController;
use Illuminate\Support\Facades\Route;





// halaman Backend
Route::get('/admin', [DashboardController::class, 'index'])->name('dashboard');
Route::resource('admin/warga', WargaController::class);
Route::resource('admin/kartu_keluarga', Kartu_keluargaController::class);
Route::resource('admin/rukun_tetangga', Rukun_tetanggaController::class);
Route::resource('admin/pengumuman', PengumumanController::class);
Route::resource('admin/tagihan', TagihanController::class);
Route::resource('admin/iuran', IuranController::class);
Route::resource('admin/kategori_golongan', Kategori_golonganController::class);
Route::resource('admin/pengeluaran', PengeluaranController::class);
Route::get('admin/laporan_pengeluaran_bulanan/{bulan}/{tahun}', [LaporanController::class, 'pengeluaran_bulanan'])->name('pengeluaran_bulanan');







// Halaman Front end
Route::get('/', [DashboardWargaController::class, 'index'])->name('dashboard-main');
Route::get('pengumuman', [PengumumanWargaController::class, 'index'])->name('pengumuman-main');