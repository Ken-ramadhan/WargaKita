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
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    //

    public function index()
    {

        $id_rw = Auth::user()->id_rw;
        $id_rt = Auth::user()->id_rt;
        $jumlah_warga = Warga::count();
        $jumlah_kk = Kartu_keluarga::count();
       $pengumuman_rw = Pengumuman::where('id_rw', $id_rw)
                            ->whereNull('id_rt')
                            ->count();

        $pengumuman_rt = Pengumuman::where('id_rt',$id_rt)->count();
        $jumlah_rt = Rukun_tetangga::count();
        $total_pemasukan = Tagihan::where('status_bayar', 'sudah_bayar')->sum('nominal');
        $total_pengeluaran = Transaksi::sum('pengeluaran');
        $total_saldo_akhir = $total_pemasukan - $total_pengeluaran;
        $title = 'Dashboard';
        return view('rw.dashboard.dashboard', compact('title','jumlah_warga','jumlah_kk','jumlah_pengumuman','jumlah_warga_penduduk','jumlah_warga_pendatang','jumlah_rt','total_pemasukan','total_pengeluaran', 'total_saldo_akhir'));
        $jumlah_warga_penduduk = Warga::where('status_warga', 'penduduk')->count();
        $jumlah_warga_pendatang = Warga::where('status_warga', 'pendatang')->count();
        $title = 'Dashboard';
        return view('rw.dashboard.dashboard', compact('title','jumlah_warga','jumlah_kk','pengumuman_rw','pengumuman_rt','jumlah_warga_penduduk','jumlah_warga_pendatang','jumlah_rt'));
    }
}
