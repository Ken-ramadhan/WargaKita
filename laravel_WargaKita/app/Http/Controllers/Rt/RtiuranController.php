<?php
namespace App\Http\Controllers\Rt;

use App\Http\Controllers\Controller;
use App\Models\Iuran;
use App\Models\Rukun_tetangga;
use Illuminate\Http\Request;

class RtIuranController extends Controller // PASTIkan nama class ini benar
{
    /**
     * Menampilkan daftar iuran manual untuk halaman RT.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $rtFilter = $request->input('rt');

        $iuran = Iuran::where('jenis', 'manual')
                                ->when($search, function ($query) use ($search) {
                                    $query->where('nama', 'like', '%' . $search . '%');
                                })
                                ->paginate(5);

        $rt = Rukun_tetangga::all();
        $title = 'Iuran RT';

        // PASTIkan view ini benar: resources/views/rt/iuran/iuran.blade.php
        return view('rt.iuran.iuran', compact('iuran', 'rt', 'title'));
    }

    /**
     * Menyimpan data iuran manual baru.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'nama' => 'required|string|max:255',
                'tgl_tagih' => 'required|date',
                'tgl_tempo' => 'required|date',
                'nominal_manual' => 'required|numeric|min:0',
            ],
            [
                'nama.required' => 'Nama iuran harus diisi',
                'tgl_tagih.required' => 'Tanggal tagih harus diisi',
                'tgl_tempo.required' => 'Tanggal tempo harus diisi',
                'nominal_manual.required' => 'Nominal harus diisi.',
                'nominal_manual.numeric' => 'Nominal harus berupa angka.',
                'nominal_manual.min' => 'Nominal tidak boleh kurang dari 0.',
            ]
        );

        Iuran::create([
            'nama' => $request->nama,
            'tgl_tagih' => $request->tgl_tagih,
            'tgl_tempo' => $request->tgl_tempo,
            'jenis' => 'manual',
            'nominal' => $request->nominal_manual,
        ]);

        // PERBAIKAN DI SINI: Redirect ke route RT
        return redirect()->route('rt_iuran.index')->with('success', 'Iuran berhasil ditambahkan');
    }

    /**
     * Menampilkan form edit untuk iuran manual tertentu.
     */
    public function edit(string $id)
    {
        $iuran = Iuran::findOrFail($id);
        // PERBAIKAN DI SINI: View harus mengarah ke rt.iuran.edit
        return view('rt_iuran.edit', compact('iuran'));
    }

    /**
     * Memperbarui data iuran manual yang sudah ada.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'tgl_tagih' => 'required|date',
            'tgl_tempo' => 'required|date',
            'nominal_manual' => 'required|numeric|min:0',
        ], [
            'nama.required' => 'Nama iuran harus diisi',
            'tgl_tagih.required' => 'Tanggal tagih harus diisi',
            'tgl_tempo.required' => 'Tanggal tempo harus diisi',
            'nominal_manual.required' => 'Nominal harus diisi.',
            'nominal_manual.numeric' => 'Nominal harus berupa angka.',
            'nominal_manual.min' => 'Nominal tidak boleh kurang dari 0.',
        ]);

        $iuran = Iuran::findOrFail($id);
        $iuran->update([
            'nama' => $request->nama,
            'tgl_tagih' => $request->tgl_tagih,
            'tgl_tempo' => $request->tgl_tempo,
            'jenis' => 'manual',
            'nominal' => $request->nominal_manual,
        ]);

        // PERBAIKAN DI SINI: Redirect ke route RT
        return redirect()->route('rt_iuran.index')->with('success', 'Iuran berhasil diperbarui');
    }

    /**
     * Menghapus data iuran.
     */
    public function destroy(string $id)
    {
        $iuran = Iuran::findOrFail($id);
        $iuran->delete();

        // PERBAIKAN DI SINI: Redirect ke route RT
        return redirect()->route('rt_iuran.index')->with('success', 'Iuran berhasil dihapus');
    }
}
