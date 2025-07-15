<?php

use App\Http\Controllers\Admin\Admin_dashboardController;
use App\Http\Controllers\Admin\Admin_rtController;
use App\Http\Controllers\Admin\Admin_rwController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Rt\Rt_kartu_keluargaController;
use App\Http\Controllers\Rt\Rt_wargaController;
use App\Http\Controllers\Rt\Rt_dashboardController;
use App\Http\Controllers\Rt\Rt_pengumumanController;
use App\Http\Controllers\Rt\RtiuranController;
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
use App\Http\Controllers\UserController;
use App\Http\Controllers\Warga\DashboardWargaController;
use App\Http\Controllers\Warga\PengumumanWargaController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', [Admin_dashboardController::class, 'index'])->name('dashboard-admin');
    Route::resource('admin/data_rt', Admin_rtController::class);
    Route::resource('admin/data_rw', Admin_rwController::class);
    
});


    Route::middleware(['auth', 'role:rw'])->group(function () {

        Route::get('/rw', [DashboardController::class, 'index'])->name('dashboard-rw');
        Route::resource('rw/warga', WargaController::class);
        Route::resource('rw/kartu_keluarga', Kartu_keluargaController::class);
        Route::resource('rw/rukun_tetangga', Rukun_tetanggaController::class);
        Route::resource('rw/pengumuman', PengumumanController::class);
        Route::resource('rw/tagihan', TagihanController::class);
        Route::resource('rw/iuran', IuranController::class);
        Route::resource('rw/kategori_golongan', Kategori_golonganController::class);
        Route::resource('rw/pengeluaran', PengeluaranController::class);
        Route::get('rw/laporan_pengeluaran_bulanan/{bulan}/{tahun}', [LaporanController::class, 'pengeluaran_bulanan'])->name('pengeluaran_bulanan');
        
     });

    Route::middleware(['auth', 'role:rw,rt,warga,admin'])->post('/update-password', [UserController::class, 'updatePassword'])->name('update.password');


// Route::middleware(['auth', 'role:rt'])->group(function () {
    Route::get('/rt', [Rt_dashboardController::class, 'index'])->name('dashboard-rt');
    Route::resource('rt/rt_kartu_keluarga', Rt_kartu_keluargaController::class);
    Route::resource('rt/rt_warga', Rt_wargaController::class);
    Route::resource('rt/rt_pengumuman', Rt_pengumumanController::class);
    Route::resource('rt/rt_iuran', RtiuranController::class);
// });


Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('update', [UserController::class, 'updatePassword'])->name('update.password');





Route::middleware(['auth', 'role:warga'])->group(function () {

    Route::get('/', [DashboardWargaController::class, 'index'])->name('dashboard-main');
    Route::get('/warga/warga_pengumuman', [PengumumanWargaController::class, 'index'])->name('pengumuman-main');
});
