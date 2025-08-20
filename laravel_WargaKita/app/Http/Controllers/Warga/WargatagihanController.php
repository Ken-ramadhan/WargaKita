<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class WargatagihanController extends Controller
{
    
    /**
     * Menampilkan daftar tagihan untuk warga yang sedang login.
     */
    public function index(Request $request)
    {
        $title = 'Tagihan Saya';
        $user = Auth::user(); // Dapatkan user yang sedang login

        // Pastikan user adalah warga dan memiliki relasi ke data warga
        if (!$user || $user->role !== 'warga' || !$user->warga) {
            Log::warning("Akses tidak sah ke halaman tagihan warga atau data warga tidak ditemukan.", ['user_id' => $user->id ?? 'guest']);
            return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman ini atau data profil Anda tidak lengkap.');
        }

        // Dapatkan nomor KK dari warga yang terkait dengan user yang login
        $no_kk_warga = $user->warga->no_kk;

        if (!$no_kk_warga) {
            Log::warning("Nomor Kartu Keluarga tidak ditemukan untuk warga yang login.", ['user_id' => $user->id, 'nik' => $user->nik]);
            return redirect('/')->with('error', 'Data Kartu Keluarga Anda tidak ditemukan. Silakan hubungi RT/RW Anda.');
        }

        Log::info("Memuat halaman tagihan untuk KK: " . $no_kk_warga, ['user_id' => $user->id]);

        $query = Tagihan::where('no_kk', $no_kk_warga);

        // Tambahkan filter pencarian jika ada
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('nominal', 'like', '%' . $search . '%');
            });
            Log::info('Filter pencarian diterapkan:', ['search_term' => $search]);
        }

        $tagihan = $query->orderBy('tgl_tagih', 'desc')->paginate(10);
        Log::info('Jumlah tagihan yang diambil untuk KK ' . $no_kk_warga . ':', ['count' => $tagihan->count(), 'total' => $tagihan->total()]);

        return view('warga.iuran.tagihan', compact('title', 'tagihan'));
    
}
}
