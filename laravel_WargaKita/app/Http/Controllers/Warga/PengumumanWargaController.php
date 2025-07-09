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
        $search = $request->input('search');
        $tahun = $request->input('tahun');
        $bulan = $request->input('bulan');
        $kategori = $request->input('kategori');

        $id_rt_warga = Auth::user()->warga->kartuKeluarga->rukunTetangga->id ?? null;
        $id_rw = Auth::user()->warga?->kartuKeluarga?->rw?->id;

        if (!$id_rt_warga) {
            abort(403, 'RT warga tidak ditemukan. Pastikan data KK dan RT sudah terhubung.');
        }

        $pengumuman = Pengumuman::where(function ($q) use ($id_rt_warga, $id_rw) {
            $q->where('id_rt', $id_rt_warga)
                ->orWhere(function ($q2) use ($id_rw) {
                    $q2->whereNull('id_rt')
                        ->where('id_rw', $id_rw);
                });
        })

            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('judul', 'like', "%$search%")
                        ->orWhere('isi', 'like', "%$search%");

                    $searchLower = strtolower($search);

                    $hariList = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'];
                    if (in_array($searchLower, $hariList)) {
                        $q->orWhereRaw("DAYNAME(tanggal) = ?", [$this->indoToEnglishDay($searchLower)]);
                    }

                    $bulanList = [
                        'januari',
                        'februari',
                        'maret',
                        'april',
                        'mei',
                        'juni',
                        'juli',
                        'agustus',
                        'september',
                        'oktober',
                        'november',
                        'desember'
                    ];
                    if (in_array($searchLower, $bulanList)) {
                        $bulanAngka = array_search($searchLower, $bulanList) + 1;
                        $q->orWhereMonth('tanggal', $bulanAngka);
                    }
                });
            })
            ->when($tahun, fn($q) => $q->whereYear('tanggal', $tahun))
            ->when($bulan, fn($q) => $q->whereMonth('tanggal', $bulan))
            ->when($kategori, fn($q) => $q->where('kategori', $kategori))
            ->orderByDesc('created_at')
            ->paginate(5)
            ->withQueryString();

        $daftar_tahun = Pengumuman::where(function ($q) use ($id_rt_warga, $id_rw) {
            $q->where('id_rt', $id_rt_warga)
                ->orWhere(function ($q2) use ($id_rw) {
                    $q2->whereNull('id_rt')
                        ->where('id_rw', $id_rw);
                });
        })
            ->selectRaw('YEAR(tanggal) as tahun')
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun');

        $daftar_kategori = Pengumuman::where(function ($q) use ($id_rt_warga, $id_rw) {
            $q->where('id_rt', $id_rt_warga)
                ->orWhere(function ($q2) use ($id_rw) {
                    $q2->whereNull('id_rt')
                        ->where('id_rw', $id_rw);
                });
        })
            ->select('kategori')
            ->distinct()
            ->pluck('kategori');

        $daftar_bulan = range(1, 12);

        $rukun_tetangga = Rukun_tetangga::find($id_rt_warga);
        $title = 'Pengumuman';

        return view('warga.pengumuman.pengumuman', compact(
            'pengumuman',
            'rukun_tetangga',
            'title',
            'daftar_tahun',
            'daftar_bulan',
            'daftar_kategori'
        ));
    }


    private function indoToEnglishDay($day)
    {
        return match (strtolower($day)) {
            'senin' => 'Monday',
            'selasa' => 'Tuesday',
            'rabu' => 'Wednesday',
            'kamis' => 'Thursday',
            'jumat' => 'Friday',
            'sabtu' => 'Saturday',
            'minggu' => 'Sunday',
            default => $day,
        };
    }
}
