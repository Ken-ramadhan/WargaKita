<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use App\Models\Kartu_keluarga;
use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LihatKKController extends Controller
{
    //


      public function index() // Menggunakan nama 'index' sesuai kode yang Anda berikan
    {
        // 1. Ambil NIK dari user yang sedang login
        $user = Auth::user();
        $nikUserLogin = $user->nik; // Ambil NIK dari model User yang login

        // 2. Cari data warga berdasarkan NIK user yang login
        // Asumsi: Model Warga memiliki primary key 'nik' atau kolom 'nik' yang unik
        $warga = Warga::where('nik', $nikUserLogin)->first();

        $kartuKeluarga = null;
        if ($warga) {
            // 3. Dari data warga, ambil 'no_kk' untuk mencari Kartu Keluarga
            // Asumsi: Model Warga memiliki kolom 'no_kk' yang berisi No. KK
            // Asumsi: Model Kartu_keluarga memiliki primary key 'no_kk'
            // Penting: Relasi 'warga' di Kartu_keluarga harus sudah didefinisikan (hasMany)
            // Relasi 'kartuKeluarga' di Warga harus sudah didefinisikan (belongsTo)

            // Menggunakan with('warga') untuk eager loading anggota keluarga (warga)
            // di Kartu_keluarga, karena relasi dari KK ke Warga adalah one-to-many.
            $kartuKeluarga = Kartu_keluarga::with('warga')
                                ->where('no_kk', $warga->no_kk)
                                ->first();
        }

        // Jika Kartu Keluarga tidak ditemukan untuk warga yang login
        if (!$kartuKeluarga) {
            return view('warga.kk.lihat-kk', ['kartuKeluarga' => null])
                   ->with('error', 'Data Kartu Keluarga Anda belum tersedia atau tidak ditemukan. Silakan hubungi RT/RW Anda.');
        }

        $title = 'Data Kartu Keluarga';
        return view('warga.kk.lihat-kk', compact('title', 'kartuKeluarga'));
    }
}
