<?php

namespace App\Http\Controllers\Rt;

use App\Http\Controllers\Controller;
use App\Models\Kartu_keluarga;
use App\Models\Pengumuman;
use App\Models\Tagihan;
use App\Models\Transaksi;
use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Rt_dashboardController extends Controller
{
    //
    public function index()
{
    $title = 'Dashboard';
    // 1. Dapatkan ID RT dari user yang sedang login
    // Asumsi: Auth::user() memiliki relasi 'rukunTetangga' yang mengembalikan objek RT,
    // dan objek RT tersebut memiliki properti 'id'.
    $rt_user_login_id = Auth::user()->rukunTetangga->id;
    $rwId = Auth::user()->rukunTetangga->id_rw;

    // 2. Cari semua Nomor Kartu Keluarga (no_kk) yang RT-nya sesuai dengan RT user yang login
    // Ini akan menghasilkan sebuah array dari no_kk yang berada di RT tersebut.
    $kk_nomor_list = Kartu_keluarga::where('id_rt', $rt_user_login_id)->pluck('no_kk');

    // --- Perhitungan yang Benar ---

    // Total Warga di RT yang login (terhubung melalui KK di RT tersebut)
    $jumlah_warga = Warga::whereIn('no_kk', $kk_nomor_list)->count();

    // Total Kartu Keluarga di RT yang login
    $jumlah_kk = Kartu_keluarga::where('id_rt', $rt_user_login_id)->count();
    
    
    $jumlah_pengumuman = Pengumuman::where(function ($q) use ($rt_user_login_id, $rwId) {
        $q->where('id_rt', $rt_user_login_id)
          ->orWhere(function ($q2) use ($rwId) {
              $q2->whereNull('id_rt')
                 ->where('id_rw', $rwId);
          });
    })->count();

    // Jumlah Warga dengan jenis 'penduduk' DI RT yang login
    // KOREKSI: Gunakan whereIn untuk membatasi scope hanya pada KK di RT yang login
    $jumlah_warga_penduduk = Warga::where('status_warga', 'penduduk')
                                 ->whereIn('no_kk', $kk_nomor_list)
                                 ->count();

    // Jumlah Warga dengan jenis 'pendatang' DI RT yang login
    // KOREKSI: Gunakan whereIn untuk membatasi scope hanya pada KK di RT yang login
    $jumlah_warga_pendatang = Warga::where('status_warga', 'pendatang')
                                  ->whereIn('no_kk', $kk_nomor_list)
                                  ->count();

    $total_pemasukan = Tagihan::where('status_bayar', 'sudah_bayar')->sum('nominal');
        $total_pengeluaran= Transaksi::sum('pengeluaran');
        $total_saldo_akhir = $total_pemasukan - $total_pengeluaran;                              
    // --- Mengirim Data ke View ---
    return view('rt.dashboard.dashboard', compact(
        'title',
        'jumlah_warga',
        'jumlah_kk',
        'jumlah_pengumuman',
        'jumlah_warga_penduduk',
        'jumlah_warga_pendatang',
        'total_pemasukan',
        'total_pengeluaran',
        'total_saldo_akhir'
    ));
}
}
