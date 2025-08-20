<?php

namespace App\Http\Controllers\Rw;
use App\Http\Controllers\Controller;
use App\Models\Kartu_keluarga;
use App\Models\Pengumuman;
use App\Models\Rukun_tetangga;
use App\Models\Tagihan;
use App\Models\Transaksi;
use App\Models\Warga;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //

    public function index()
    {
        $jumlah_warga = Warga::count();
        $jumlah_kk = Kartu_keluarga::count();
        $jumlah_pengumuman = Pengumuman::count();
        $jumlah_rt = Rukun_tetangga::count();
        $jumlah_warga_penduduk = Warga::where('jenis', 'penduduk')->count();
        $jumlah_warga_pendatang = Warga::where('jenis', 'pendatang')->count();
        $total_pemasukan = Tagihan::where('status_bayar', 'sudah_bayar')->sum('nominal');
        $total_pengeluaran = Transaksi::sum('pengeluaran');
        $total_saldo_akhir = $total_pemasukan - $total_pengeluaran;
        $title = 'Dashboard';
        return view('rw.dashboard.dashboard', compact('title','jumlah_warga','jumlah_kk','jumlah_pengumuman','jumlah_warga_penduduk','jumlah_warga_pendatang','jumlah_rt','total_pemasukan','total_pengeluaran', 'total_saldo_akhir'));
    }
}
