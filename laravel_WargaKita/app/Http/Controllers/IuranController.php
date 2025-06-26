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
        $iuran_golongan = IuranGolongan::with('golongan')->get();
        $title = 'Iuran';
        return view('iuran', compact('iuran', 'title', 'kategori_golongan', 'iuran_golongan'));
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
        $validated = $request->validate(
            [
                'nama' => 'required|string|max:255',
                'tgl_tagih' => 'required|date',
                'tgl_tempo' => 'required|date',
                'jenis' => 'required|in:otomatis,manual',
                // hanya jika manual
                'nominal' => $request->jenis === 'manual' ? 'required|numeric' : 'nullable',

                // hanya jika otomatis
                'nominal.*' => $request->jenis === 'otomatis' ? 'required|numeric' : 'nullable',
            ],
            [
                'nama.required' => 'Nama iuran harus diisi',
                'tgl_tagih.required' => 'Tanggal tagih harus diisi',
                'tgl_tempo.required' => 'Tanggal tempo harus diisi',
                'jenis.required' => 'Jenis iuran harus dipilih',
            ]
        );

        $iuran = Iuran::create([
            'nama' => $request->nama,
            'tgl_tagih' => $request->tgl_tagih,
            'tgl_tempo' => $request->tgl_tempo,
            'jenis' => $request->jenis,
            'nominal' => $request->jenis === 'manual' ? $request->nominal_manual : null,
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
        $iuran = Iuran::findOrFail($id);
        $kategori_golongan = Kategori_golongan::all();
        return view('iuran.edit', compact('iuran', 'kategori_golongan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'tgl_tagih' => 'required|date',
            'tgl_tempo' => 'required|date',
            'jenis' => 'required|in:otomatis,manual',
            'nominal' => 'nullable|numeric',
            'nominal.*' => 'nullable|numeric'
        ]);

        $iuran = Iuran::findOrFail($id);
        $iuran->update([
            'nama' => $request->nama,
            'tgl_tagih' => $request->tgl_tagih,
            'tgl_tempo' => $request->tgl_tempo,
            'jenis' => $request->jenis,
            'nominal' => $request->jenis === 'manual' ? $request->nominal : null,
        ]);

        if ($request->jenis === 'otomatis') {
            IuranGolongan::where('id_iuran', $iuran->id)->delete();
            foreach ($request->nominal as $golonganId => $nominal) {
                IuranGolongan::create([
                    'id_iuran' => $iuran->id,
                    'id_golongan' => $golonganId,
                    'nominal' => $nominal,
                ]);
            }
        }

        return redirect()->route('iuran.index')->with('success', 'Iuran berhasil diperbarui');
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
