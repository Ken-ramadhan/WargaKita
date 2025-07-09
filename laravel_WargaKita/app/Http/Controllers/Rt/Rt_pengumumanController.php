<?php

namespace App\Http\Controllers\Rt;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Rt_pengumumanController extends Controller
{
    /**
     * Tampilkan daftar pengumuman milik RT yang login.
     */
    public function index(Request $request)
{
    $title = 'Pengumuman';

    // Ini filter input (1 nilai)
    $search = $request->input('search');
    $tahun = $request->input('tahun');
    $bulan = $request->input('bulan');
    $kategori = $request->input('kategori');

    // Ambil ID RT yang login
    $rtId = Auth::user()->id_rt;

    // Data list: daftar tahun & kategori unik di RT ini
    $daftar_tahun = Pengumuman::where('id_rt', $rtId)
        ->selectRaw('YEAR(tanggal) as tahun')
        ->distinct()
        ->orderByDesc('tahun')
        ->pluck('tahun');

    $daftar_kategori = Pengumuman::where('id_rt', $rtId)
        ->select('kategori')
        ->distinct()
        ->pluck('kategori');

    $daftar_bulan = range(1, 12);

    // Query data
    $rwId = Auth::user()->rukunTetangga->id_rw;

$pengumuman = Pengumuman::where(function ($q) use ($rtId, $rwId) {
        $q->where('id_rt', $rtId)
          ->orWhere(function ($q2) use ($rwId) {
              $q2->whereNull('id_rt')
                 ->where('id_rw', $rwId);
          });
    })
    ->when($search, function ($q) use ($search) {
        $q->where(function ($q2) use ($search) {
            $q2->where('judul', 'like', "%$search%")
               ->orWhere('isi', 'like', "%$search%");
        });
    })
    ->when($tahun, fn($q) => $q->whereYear('tanggal', $tahun))
    ->when($bulan, fn($q) => $q->whereMonth('tanggal', $bulan))
    ->when($kategori, fn($q) => $q->where('kategori', $kategori))
    ->orderByDesc('created_at')
    ->paginate(10)
    ->withQueryString();

    return view('rt.pengumuman.pengumuman', compact(
        'pengumuman',
        'title',
        'daftar_tahun',
        'daftar_bulan',
        'daftar_kategori',
        'tahun',
        'bulan',
        'kategori',
        'search'
    ));
}


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
            'id_rt' => Auth::user()->id_rt,
            'id_rw' => Auth::user()->rukunTetangga->id_rw // Relasi RT ➜ RW
        ]);

        return back()->with('success', 'Pengumuman RT berhasil dibuat.');
    }

    /**
     * Tampilkan detail pengumuman.
     */
    public function show($id)
    {
        $pengumuman = Pengumuman::where('id_rt', Auth::user()->id_rt)->findOrFail($id);

        return view('rt.pengumuman.show', compact('pengumuman'));
    }

    /**
     * Tampilkan form edit.
     */
    public function edit($id)
    {
        $pengumuman = Pengumuman::where('id_rt', Auth::user()->id_rt)->findOrFail($id);

        return view('rt.pengumuman.edit', compact('pengumuman'));
    }

    /**
     * Update pengumuman.
     */
    public function update(Request $request, $id)
    {
        $pengumuman = Pengumuman::where('id_rt', Auth::user()->id_rt)->findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'isi' => 'required|string',
            'tanggal' => 'required|date',
        ]);

        $pengumuman->update([
            'judul' => $request->judul,
            'kategori' => $request->kategori,
            'isi' => $request->isi,
            'tanggal' => $request->tanggal,
        ]);

        return redirect()->route('rt_pengumuman.index')
            ->with('success', 'Pengumuman berhasil diperbarui.');
    }

    /**
     * Hapus pengumuman.
     */
    public function destroy($id)
    {
        $pengumuman = Pengumuman::where('id_rt', Auth::user()->id_rt)->findOrFail($id);
        $pengumuman->delete();

        return redirect()->route('rt_pengumuman.index')
            ->with('success', 'Pengumuman berhasil dihapus.');
    }
}
