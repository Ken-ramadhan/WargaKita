<?php

use App\Http\Controllers\Kartu_keluargaController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\Rukun_tetanggaController;
use App\Http\Controllers\WargaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
});

Route::resource('warga', WargaController::class);
Route::resource('kartu_keluarga', Kartu_keluargaController::class);
Route::resource('rukun_tetangga', Rukun_tetanggaController::class);
Route::resource('pengumuman', PengumumanController::class);