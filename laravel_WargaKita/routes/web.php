<?php

use App\Http\Controllers\Admin\Admin_dashboardController;
use App\Http\Controllers\Admin\Admin_rtController;
use App\Http\Controllers\Admin\Admin_rwController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PengeluaranController as ControllersPengeluaranController;
use App\Http\Controllers\Rt\Rt_kartu_keluargaController;
use App\Http\Controllers\Rt\Rt_wargaController;
use App\Http\Controllers\Rt\Rt_dashboardController;
use App\Http\Controllers\Rt\Rt_pengumumanController;
use App\Http\Controllers\Rt\Rt_tagihanController;
use App\Http\Controllers\Rt\Rt_transaksiController;
use App\Http\Controllers\Rt\RtiuranController;
use App\Http\Controllers\Rw\DashboardController;
use App\Http\Controllers\Rw\IuranController;
use App\Http\Controllers\Rw\Kartu_keluargaController;
use App\Http\Controllers\Rw\Kategori_golonganController;
use App\Http\Controllers\Rw\LaporanController;
use App\Http\Controllers\Rw\PengeluaranController;
use App\Http\Controllers\Rw\TransaksiController;
use App\Http\Controllers\Rw\PengumumanController;
use App\Http\Controllers\Rw\PengumumanRtController;
use App\Http\Controllers\Rw\Rukun_tetanggaController;
use App\Http\Controllers\Rw\TagihanController;
use App\Http\Controllers\Rw\WargaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Warga\DashboardWargaController;
use App\Http\Controllers\Warga\LihatKKController;
use App\Http\Controllers\Warga\PengumumanWargaController;
use App\Http\Controllers\Warga\WargatagihanController;
use App\Http\Controllers\Warga\WargatransaksiController;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [Admin_dashboardController::class, 'index'])->name('dashboard-admin');
    Route::resource('admin/data_rt', Admin_rtController::class);
    Route::resource('admin/data_rw', Admin_rwController::class);
});


Route::middleware(['auth', 'role:rw'])->group(function () {
    Route::get('/rw', [DashboardController::class, 'index'])->name('dashboard-rw');
    Route::resource('rw/warga', WargaController::class);

        Route::get('/rw', [DashboardController::class, 'index'])->name('dashboard-rw');
        Route::resource('rw/warga', WargaController::class);
        Route::resource('rw/kartu_keluarga', Kartu_keluargaController::class);
        Route::resource('rw/rukun_tetangga', Rukun_tetanggaController::class);
        Route::resource('rw/pengumuman', PengumumanController::class);
        Route::resource('rw/tagihan', TagihanController::class);
        Route::resource('rw/iuran', IuranController::class);
        Route::resource('rw/kategori_golongan', Kategori_golonganController::class);
        Route::resource('rw/transaksi', TransaksiController::class);
        Route::get('rw/laporan_pengeluaran_bulanan/{bulan}/{tahun}', [LaporanController::class, 'pengeluaran_bulanan'])->name('pengeluaran_bulanan');
        
    // Perbaikan untuk rute Kartu Keluarga terkait foto
    Route::resource('rw/kartu_keluarga', Kartu_keluargaController::class);

    // Route untuk mengunggah/memperbarui foto KK (menggunakan PUT)
    // Gunakan kartu_keluarga sebagai parameter route model binding
    Route::put('rw/kartu_keluarga/{kartu_keluarga}/upload-foto', [Kartu_keluargaController::class, 'uploadFoto'])->name('kartu_keluarga.upload_foto');

    // Route untuk menghapus foto KK (menggunakan DELETE)
    Route::delete('rw/kartu_keluarga/{kartu_keluarga}/delete-foto', [Kartu_keluargaController::class, 'deleteFoto'])->name('kartu_keluarga.delete_foto');

    // Route upload form jika masih dibutuhkan (meskipun fungsionalitas sudah di modal)
    Route::get('rw/kartu_keluarga/{kartu_keluarga}/upload-form', [Kartu_keluargaController::class, 'uploadForm'])->name('kartu_keluarga.upload_form');


    Route::resource('rw/rukun_tetangga', Rukun_tetanggaController::class);
    Route::resource('rw/pengumuman', PengumumanController::class);
    Route::resource('rw/pengumuman-rt', PengumumanRtController::class);
    Route::resource('rw/tagihan', TagihanController::class);
    Route::resource('rw/iuran', IuranController::class);
    Route::resource('rw/kategori_golongan', Kategori_golonganController::class);
    Route::resource('rw/pengeluaran', PengeluaranController::class);
    Route::get('rw/laporan_pengeluaran_bulanan/{bulan}/{tahun}', [LaporanController::class, 'pengeluaran_bulanan'])->name('pengeluaran_bulanan');
});

Route::middleware(['auth', 'role:rw,rt,warga,admin'])->post('/update-password', [UserController::class, 'updatePassword'])->name('update.password');


Route::middleware(['auth', 'role:rt'])->group(function () {
    Route::get('/rt', [Rt_dashboardController::class, 'index'])->name('dashboard-rt');
    Route::resource('rt/rt_kartu_keluarga', Rt_kartu_keluargaController::class);
    Route::resource('rt/rt_warga', Rt_wargaController::class);
    Route::resource('rt/rt_pengumuman', Rt_pengumumanController::class);
    Route::resource('rt/rt_iuran', RtiuranController::class);
    Route::resource('rt/rt_tagihan', Rt_tagihanController::class);
    Route::resource('rt/rt_transaksi', Rt_transaksiController::class);

        // Gunakan rt_kartu_keluarga sebagai parameter route model binding
    Route::put('rw/kartu_keluarga/{rt_kartu_keluarga}/upload-foto', [Rt_kartu_keluargaController::class, 'uploadFoto'])->name('rt_kartu_keluarga.upload_foto');
        // Route untuk menghapus foto KK (menggunakan DELETE)
    Route::delete('rt/rt_kartu_keluarga/{rt_kartu_keluarga}/delete-foto', [Rt_kartu_keluargaController::class, 'deleteFoto'])->name('rt_kartu_keluarga.delete_foto');

    // Route upload form jika masih dibutuhkan (meskipun fungsionalitas sudah di modal)
    Route::get('rt/rt_kartu_keluarga/{rt_kartu_keluarga}/upload-form', [Rt_kartu_keluargaController::class, 'uploadForm'])->name('rt_kartu_keluarga.upload_form');
});


Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('update', [UserController::class, 'updatePassword'])->name('update.password');





Route::middleware(['auth', 'role:warga'])->group(function () {

    Route::get('/', [DashboardWargaController::class, 'index'])->name('dashboard-main');
    Route::get('/warga/warga_pengumuman', [PengumumanWargaController::class, 'index'])->name('pengumuman-main');
    Route::get('/warga/lihat_kk', [LihatKKController::class, 'index'])->name('lihat_kk');
    Route::get('/warga/tagihan', [WargatagihanController::class, 'index'])->name('tagihan');
    Route::get('/warga/transaksi', [WargatransaksiController::class, 'index'])->name('transaksi');
});
