<?php

namespace App\Http\Controllers\Rw;
use App\Http\Controllers\Controller;

use App\Models\Pengumuman;
use App\Models\Rukun_tetangga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengumumanController extends Controller
{
    /**
     * Display a listing of the resource.
     */

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
    $title = 'Pengumuman';

    $daftar_tahun = Pengumuman::selectRaw('YEAR(tanggal) as tahun')->distinct()->orderByDesc('tahun')->pluck('tahun');
    $daftar_bulan = range(1, 12);
    $daftar_kategori = Pengumuman::select('kategori')->distinct()->pluck('kategori');
    //  dd($request->all());
    return view('rw.pengumuman.pengumuman', compact(
        'pengumuman',
        'title',
        'daftar_tahun',
        'daftar_bulan',
        'daftar_kategori'
    ));
}

    // Fungsi bantu ubah nama hari dari Indonesia ke Inggris
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


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'isi' => 'required',
            'kategori' => 'required',
            'tanggal' => 'required|date',
        ]);

        Pengumuman::create([
            'judul' => $request->judul,
            'isi' => $request->isi,
            'kategori' => $request->kategori,
            'tanggal' => $request->tanggal,
            'id_rw' => Auth::user()->id_rw,
            'id_rt' => null
        ]);

        return back()->with('success', 'Pengumuman RW berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $pengumuman = Pengumuman::findOrFail($id);
        return view('pengumuman.show', compact('pengumuman'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $pengumuman = Pengumuman::findOrFail($id); 
        return view('pengumuman.edit', compact('pengumuman'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $pengumuman = Pengumuman::findOrFail($id);
        $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'string|max:255',
            'isi' => 'required|string',
            'tanggal' => 'required|date',
        ], [
            'judul.required' => 'Judul pengumuman harus diisi.',
            'isi.required' => 'Isi pengumuman harus diisi.',
            'tanggal.required' => 'Tanggal pengumuman harus diisi.',
            'kategori.required' => 'Kategori pengumuman harus diisi.',
        ]);
        $pengumuman->update([
            'judul' => $request->judul,
            'kategori' => $request->kategori,
            'isi' => $request->isi,
            'tanggal' => $request->tanggal,
        ]);
        return redirect()->route('pengumuman.index')->with('success', 'Pengumuman berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $pengumuman = Pengumuman::findOrFail($id);
        $pengumuman->delete();
        return redirect()->route('pengumuman.index')->with('success', 'Pengumuman berhasil dihapus.');
    }
}
