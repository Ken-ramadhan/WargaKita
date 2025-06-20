<?php

namespace App\Http\Controllers;

use App\Models\Iuran;
use App\Models\IuranGolongan;
use App\Models\Kategori_golongan;
use Illuminate\Http\Request;

class IuranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $iuran = Iuran::paginate(5);
        $kategori_golongan = Kategori_golongan::all();
        $title = 'Iuran';
        return view('iuran', compact('iuran', 'title', 'kategori_golongan'));
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
    $validated = $request->validate([
        'nama' => 'required|string|max:255',
        'tgl_tagih' => 'required|date',
        'tgl_tempo' => 'required|date',
        'jenis' => 'required|in:otomatis,manual',
        'nominal' => 'nullable|numeric',
        'nominal.*' => 'nullable|numeric'
    ],
    [
        'nama.required' => 'Nama iuran harus diisi',
        'tgl_tagih.required' => 'Tanggal tagih harus diisi',
        'tgl_tempo.required' => 'Tanggal tempo harus diisi',
        'jenis.required' => 'Jenis iuran harus dipilih',
        'nominal.numeric' => 'Nominal harus berupa angka|max:20',
        'nominal.*.numeric' => 'Nominal untuk setiap golongan harus berupa angka',
    ]);

    $iuran = Iuran::create([
        'nama' => $request->nama,
        'tgl_tagih' => $request->tgl_tagih,
        'tgl_tempo' => $request->tgl_tempo,
        'jenis' => $request->jenis,
        'nominal' => $request->jenis === 'manual' ? $request->nominal : null,
    ]);

    if ($request->jenis === 'otomatis') {
        foreach ($request->nominal as $golonganId => $nominal) {
            IuranGolongan::create([
                'id_iuran' => $iuran->id,
                'id_golongan' => $golonganId,
                'nominal' => $nominal,
            ]);
        }
    }

    return redirect()->route('iuran.index')->with('success', 'Iuran berhasil ditambahkan');
}



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $iuran = Iuran::findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //

        $iuran = Iuran::findOrFail($id);
        $iuran->delete();

        return redirect()->route('iuran.index')->with('success', 'Iuran berhasil dihapus');
    }
}
