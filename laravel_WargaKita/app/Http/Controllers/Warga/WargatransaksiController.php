<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class WargatransaksiController extends Controller
{
    /**
     * Menampilkan daftar transaksi keuangan RW yang terkait dengan RT warga yang sedang login.
     */
    public function index(Request $request)
    {
        $title = 'Transaksi Keuangan RT Saya';
        $user = Auth::user(); // Dapatkan user yang sedang login

        // Pastikan user adalah warga dan memiliki relasi ke data warga dan RT
        if (!$user || $user->role !== 'warga' || !$user->rukunTetangga) { // Cek relasi rukunTetangga
            Log::warning("Akses tidak sah ke halaman transaksi warga atau data RT tidak ditemukan.", ['user_id' => $user->id ?? 'guest']);
            return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman ini atau data RT Anda tidak lengkap.');
        }

        // Dapatkan nomor RT dari user yang login melalui relasi rukunTetangga
        $currentRtNumber = $user->rukunTetangga->nomor_rt;

        if (!$currentRtNumber) {
            Log::warning("Nomor RT tidak ditemukan untuk user login saat melihat transaksi.", ['user_id' => $user->id, 'id_rt' => $user->id_rt]);
            return redirect('/')->with('error', 'Data RT Anda tidak ditemukan. Silakan hubungi RT/RW Anda.');
        }

        Log::info("Memuat halaman transaksi untuk RT: " . $currentRtNumber, ['user_id' => $user->id]);

        // Query data Transaksi (keuangan RW) yang terkait dengan RT ini
        $query = Transaksi::where('rt', $currentRtNumber); // Filter berdasarkan kolom 'rt' di tabel transaksi

        // Tambahkan filter pencarian jika ada
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama_transaksi', 'like', '%' . $search . '%')
                  ->orWhere('keterangan', 'like', '%' . $search . '%'); // Sesuaikan kolom yang bisa dicari
            });
            Log::info('Filter pencarian diterapkan pada transaksi:', ['search_term' => $search]);
        }

        // Urutkan berdasarkan tanggal transaksi, lalu ID untuk konsistensi saldo berjalan
        $transaksi = $query->orderBy('tanggal', 'desc')->orderBy('id', 'desc')->paginate(10); 
        Log::info('Jumlah transaksi yang diambil untuk RT ' . $currentRtNumber . ':', ['count' => $transaksi->count(), 'total' => $transaksi->total()]);

        return view('warga.iuran.transaksi', compact('title', 'transaksi'));
    }
}

