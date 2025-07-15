<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use App\Models\Rukun_tetangga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengumumanWargaController extends Controller
{
     public function index(Request $request)
    {
        // Mengambil parameter filter dari request
        $search = $request->input('search');
        $tahun = $request->input('tahun');
        $bulan = $request->input('bulan');
        $kategori = $request->input('kategori');

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

            // Kondisi 3 (Opsional): Pengumuman yang bersifat global (tidak terikat RT atau RW)
            // Ini akan ditampilkan kepada semua warga yang terhubung dengan RT/RW manapun.
            // Jika Anda tidak ingin pengumuman global ditampilkan, hapus blok orWhere ini.
            $query->orWhere(function ($subQuery) {
                $subQuery->whereNull('id_rt')
                         ->whereNull('id_rw');
            });
        });

        // Menghitung total pengumuman berdasarkan baseQuery
        $total_pengumuman = (clone $baseQuery)->count();

        // Mengambil data pengumuman dengan filter pencarian, tahun, bulan, dan kategori
        $pengumuman = (clone $baseQuery)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    // Mencari berdasarkan judul atau isi
                    $q->where('judul', 'like', "%$search%")
                        ->orWhere('isi', 'like', "%$search%");

                    $searchLower = strtolower($search);

                    // Mencari berdasarkan nama hari (misal: "senin")
                    $hariList = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'];
                    if (in_array($searchLower, $hariList)) {
                        // Memanggil helper method untuk konversi nama hari
                        $q->orWhereRaw("DAYNAME(tanggal) = ?", [$this->indoToEnglishDay($searchLower)]);
                    }

                    // Mencari berdasarkan nama bulan (misal: "januari")
                    $bulanList = [
                        'januari', 'februari', 'maret', 'april', 'mei', 'juni',
                        'juli', 'agustus', 'september', 'oktober', 'november', 'desember'
                    ];
                    if (in_array($searchLower, $bulanList)) {
                        $bulanAngka = array_search($searchLower, $bulanList) + 1;
                        $q->orWhereMonth('tanggal', $bulanAngka);
                    }
                });
            })
            // Filter berdasarkan tahun
            ->when($tahun, fn($q) => $q->whereYear('tanggal', $tahun))
            // Filter berdasarkan bulan
            ->when($bulan, fn($q) => $q->whereMonth('tanggal', $bulan))
            // Filter berdasarkan kategori
            ->when($kategori, fn($q) => $q->where('kategori', $kategori))
            ->orderByDesc('created_at') // Mengurutkan dari yang terbaru
            ->paginate(5) // Paginasi 5 item per halaman
            ->withQueryString(); // Menjaga parameter query string saat paginasi

        // Mengambil daftar tahun yang tersedia untuk filter
        $daftar_tahun = (clone $baseQuery)
            ->selectRaw('YEAR(tanggal) as tahun')
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun');

        // Mengambil daftar kategori yang tersedia untuk filter
        $daftar_kategori = (clone $baseQuery)
            ->select('kategori')
            ->distinct()
            ->pluck('kategori');

        // Daftar bulan untuk dropdown filter
        $daftar_bulan = range(1, 12);

        // Mengambil data rukun tetangga pengguna (jika ada)
        $rukun_tetangga = $userRtId ? Rukun_tetangga::find($userRtId) : null;
        $title = 'Pengumuman';

        // Mengirim data ke view
        return view('warga.pengumuman.pengumuman', compact(
            'pengumuman',
            'rukun_tetangga',
            'title',
            'daftar_tahun',
            'daftar_bulan',
            'daftar_kategori',
            'total_pengumuman'
        ));
    }

    /**
     * Helper method untuk mengkonversi nama hari dalam Bahasa Indonesia ke Bahasa Inggris.
     * Metode ini diasumsikan ada di dalam controller yang sama.
     *
     * @param string $indoDay
     * @return string|null
     */
    protected function indoToEnglishDay(string $indoDay): ?string
    {
        $map = [
            'senin' => 'Monday',
            'selasa' => 'Tuesday',
            'rabu' => 'Wednesday',
            'kamis' => 'Thursday',
            'jumat' => 'Friday',
            'sabtu' => 'Saturday',
            'minggu' => 'Sunday',
        ];
        return $map[strtolower($indoDay)] ?? null;
    }
}
