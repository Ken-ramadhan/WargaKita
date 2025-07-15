<?php

namespace App\Http\Controllers\Rw;

use App\Http\Controllers\Controller;
use App\Models\Iuran;
use App\Models\Rukun_tetangga;
use App\Models\IuranGolongan; // Tetap diperlukan jika Anda masih memiliki iuran otomatis di RW
use App\Models\Kategori_golongan; // Tetap diperlukan jika Anda masih memiliki iuran otomatis di RW
use Illuminate\Http\Request;

class IuranController extends Controller
{
    /**
     * Menampilkan daftar iuran (manual dan otomatis) untuk halaman RW.
     */
    public function index(Request $request)
    {
        // PENTING: Eager load relasi 'iuran_golongan' untuk mencegah error 'sum() on null'
        // saat mengakses data nominal per golongan di Blade.
        $search = $request->input('search');
        $rtFilter = $request->input('rt');

        $iuran = Iuran::with('iuran_golongan')
                            ->when($search, function ($query) use ($search) {
                                $query->where('nama', 'like', '%' . $search . '%');
                            })
                            // Anda bisa tambahkan filter RT jika kolom id_rt ada di tabel iuran
                            // ->when($rtFilter, function ($query) use ($rtFilter) {
                            //     $query->where('id_rt', $rtFilter);
                            // })
                            ->paginate(5); // Gunakan pagination umum

        $kategori_golongan = Kategori_golongan::all();
        $rt = Rukun_tetangga::all();
        $title = 'Iuran';

        // Kirimkan semua data yang relevan ke view
        return view('rw.iuran.iuran', compact('iuran', 'kategori_golongan', 'rt', 'title'));
    }

    /**
     * Menyimpan data iuran baru.
     * Menangani iuran manual dan otomatis dengan penyimpanan nominal per golongan.
     */
    public function store(Request $request)
    {
        // Validasi input dari form
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'tgl_tagih' => 'required|date',
            'tgl_tempo' => 'required|date',
            'jenis' => 'required|in:otomatis,manual',
            // Nominal manual hanya diperlukan jika jenisnya 'manual'
            'nominal_manual' => 'nullable|numeric|min:0',
            // Nominal (array) hanya diperlukan jika jenisnya 'otomatis'
            'nominal' => 'nullable|array',
            'nominal.*' => 'nullable|numeric|min:0',
        ], [
            'nama.required' => 'Nama iuran harus diisi',
            'tgl_tagih.required' => 'Tanggal tagih harus diisi',
            'tgl_tempo.required' => 'Tanggal tempo harus diisi',
            'jenis.required' => 'Jenis iuran harus dipilih',
            'nominal_manual.required' => 'Nominal manual harus diisi jika jenisnya manual',
            'nominal.required' => 'Nominal harus diisi untuk semua golongan jika jenisnya otomatis',
            'nominal.*.numeric' => 'Nominal harus berupa angka.',
            'nominal.*.min' => 'Nominal tidak boleh kurang dari 0.',
        ]);

        // Simpan data iuran utama
        $iuran = Iuran::create([
            'nama' => $request->nama,
            'tgl_tagih' => $request->tgl_tagih,
            'tgl_tempo' => $request->tgl_tempo,
            'jenis' => $request->jenis,
            'nominal' => $request->jenis === 'manual' ? $request->nominal_manual : null,
        ]);

        // Jika jenis iuran adalah 'otomatis', simpan nominal per golongan
        if ($request->jenis === 'otomatis') {
            foreach ($request->nominal ?? [] as $golonganId => $nominal) {
                $nominalValue = is_numeric($nominal) ? $nominal : 0;
                IuranGolongan::create([
                    'nama' => $iuran->nama,
                    'id_iuran' => $iuran->id,
                    'id_golongan' => $golonganId,
                    'nominal' => $nominalValue,
                ]);
            }
        }

        // PERBAIKAN DI SINI: Redirect ke route iuran.index (untuk RW)
        return redirect()->route('iuran.index')->with('success', 'Iuran berhasil ditambahkan');
    }

    /**
     * Menampilkan form edit untuk iuran tertentu.
     */
    public function edit(string $id)
    {
        $iuran = Iuran::with('iuran_golongan')->findOrFail($id);
        $kategori_golongan = Kategori_golongan::all();

        return view('rw.iuran.edit', compact('iuran', 'kategori_golongan'));
    }

    /**
     * Memperbarui data iuran yang sudah ada.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'tgl_tagih' => 'required|date',
            'tgl_tempo' => 'required|date',
            'jenis' => 'required|in:otomatis,manual',
            'nominal_manual' => 'nullable|numeric|min:0',
            'nominal' => 'nullable|array',
            'nominal.*' => 'nullable|numeric|min:0',
        ], [
            'nama.required' => 'Nama iuran harus diisi',
            'tgl_tagih.required' => 'Tanggal tagih harus diisi',
            'tgl_tempo.required' => 'Tanggal tempo harus diisi',
            'jenis.required' => 'Jenis iuran harus dipilih',
            'nominal_manual.required' => 'Nominal manual harus diisi jika jenisnya manual',
            'nominal.required' => 'Nominal harus diisi untuk semua golongan jika jenisnya otomatis',
            'nominal.*.numeric' => 'Nominal harus berupa angka.',
            'nominal.*.min' => 'Nominal tidak boleh kurang dari 0.',
        ]);

        $iuran = Iuran::findOrFail($id);
        $iuran->update([
            'nama' => $request->nama,
            'tgl_tagih' => $request->tgl_tagih,
            'tgl_tempo' => $request->tgl_tempo,
            'jenis' => $request->jenis,
            'nominal' => $request->jenis === 'manual' ? $request->nominal_manual : null,
        ]);

        // Jika jenis iuran adalah 'otomatis'
        if ($request->jenis === 'otomatis') {
            IuranGolongan::where('id_iuran', $iuran->id)->delete();
            foreach ($request->nominal ?? [] as $golonganId => $nominal) {
                $nominalValue = is_numeric($nominal) ? $nominal : 0;
                IuranGolongan::create([
                    'nama' => $iuran->nama,
                    'id_iuran' => $iuran->id,
                    'id_golongan' => $golonganId,
                    'nominal' => $nominalValue,
                ]);
            }
        } else {
            // Jika jenisnya berubah menjadi 'manual', hapus semua entri IuranGolongan yang terkait
            IuranGolongan::where('id_iuran', $iuran->id)->delete();
        }

        // PERBAIKAN DI SINI: Redirect ke route iuran.index (untuk RW)
        return redirect()->route('iuran.index')->with('success', 'Iuran berhasil diperbarui');
    }

    /**
     * Menghapus data iuran.
     * Juga menghapus semua iuran golongan yang terkait.
     */
    public function destroy(string $id)
    {
        $iuran = Iuran::findOrFail($id);
        // Pastikan juga menghapus IuranGolongan yang terkait sebelum menghapus Iuran utama
        IuranGolongan::where('id_iuran', $iuran->id)->delete();
        $iuran->delete();

        // PERBAIKAN DI SINI: Redirect ke route iuran.index (untuk RW)
        return redirect()->route('iuran.index')->with('success', 'Iuran berhasil dihapus');
    }
}
