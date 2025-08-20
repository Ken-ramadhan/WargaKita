<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use App\Models\Tagihan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardWargaController extends Controller
{
    //

   public function index()
{
    $title = 'Dashboard';

   // Mengambil ID RT dan RW dari pengguna yang sedang login
        // Menggunakan null coalescing operator untuk menghindari error jika relasi tidak ada
        $userRtId = Auth::user()->warga->kartuKeluarga->rukunTetangga->id ?? null;
        $userRwId = Auth::user()->warga->kartuKeluarga->rw->id ?? null;

        // Memastikan pengguna terhubung dengan setidaknya satu RT atau RW
        // Jika tidak, tampilkan error 403 (Forbidden)
        if (is_null($userRtId) && is_null($userRwId)) {
            abort(403, 'Anda tidak terhubung dengan RT atau RW manapun untuk melihat pengumuman. Harap hubungi administrator.');
        }

        // Membuat query dasar untuk pengumuman yang relevan dengan pengguna
        // Query ini akan digunakan kembali untuk menghitung total, mengambil data,
        // serta daftar tahun dan kategori.
        $baseQuery = Pengumuman::query();

        // Menerapkan logika filter berdasarkan RT dan RW pengguna
        $baseQuery->where(function ($query) use ($userRtId, $userRwId) {
            // Kondisi 1: Pengumuman yang secara spesifik ditujukan untuk RT pengguna
            if ($userRtId) {
                $query->where('id_rt', $userRtId);
            }

            // Kondisi 2: Pengumuman yang ditujukan untuk RW pengguna,
            // TETAPI TIDAK ditujukan untuk RT spesifik (artinya ini pengumuman tingkat RW)
            if ($userRwId) {
                // Menggunakan orWhere dengan closure untuk mengelompokkan kondisi
                // (id_rw = X AND id_rt IS NULL)
                $query->orWhere(function ($subQuery) use ($userRwId) {
                    $subQuery->where('id_rw', $userRwId)
                             ->whereNull('id_rt'); // Penting: Hanya pengumuman tingkat RW
                });
            }
        });

        // Menghitung total pengumuman berdasarkan baseQuery
        $jumlah_pengumuman = (clone $baseQuery)->count();

        $total_pemasukan = Tagihan::where('status_bayar', 'sudah_bayar')->sum('nominal');
        $total_pengeluaran = Transaksi::sum('pengeluaran');
        $total_saldo_akhir = $total_pemasukan - $total_pengeluaran;

    return view('warga.dashboard.dashboard', compact('title', 'jumlah_pengumuman','total_pemasukan', 'total_pengeluaran', 'total_saldo_akhir'));
}
}
