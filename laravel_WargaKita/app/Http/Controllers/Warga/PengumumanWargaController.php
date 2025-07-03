<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use App\Models\Rukun_tetangga;
use Illuminate\Http\Request;

class PengumumanWargaController extends Controller
{
    //
    public function index(Request $request)
{
    $search = $request->input('search');
    $tahun = $request->input('tahun');
    $bulan = $request->input('bulan');
    $kategori = $request->input('kategori');

    $pengumuman = Pengumuman::when($search, function ($query, $search) {
        $query->where('judul', 'like', '%' . $search . '%')
              ->orWhere('isi', 'like', '%' . $search . '%');

        $searchLower = strtolower($search);

        $hariList = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'];
        if (in_array($searchLower, $hariList)) {
            $query->orWhereRaw("DAYNAME(tanggal) = ?", [$this->indoToEnglishDay($searchLower)]);
        }

        $bulanList = ['januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember'];
        if (in_array($searchLower, $bulanList)) {
            $bulanAngka = array_search($searchLower, $bulanList) + 1;
            $query->orWhereMonth('tanggal', $bulanAngka);
        }
    })
    ->when($tahun, fn($q) => $q->whereYear('tanggal', $tahun))
    ->when($bulan, fn($q) => $q->whereMonth('tanggal', $bulan))
    ->when($kategori, fn($q) => $q->where('kategori', $kategori))
    ->orderBy('created_at', 'desc')
    ->paginate(5)
    ->withQueryString();

    $rukun_tetangga = Rukun_tetangga::all();
    $title = 'Pengumuman';

    $daftar_tahun = Pengumuman::selectRaw('YEAR(tanggal) as tahun')->distinct()->orderByDesc('tahun')->pluck('tahun');
    $daftar_bulan = range(1, 12);
    $daftar_kategori = Pengumuman::select('kategori')->distinct()->pluck('kategori');

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
