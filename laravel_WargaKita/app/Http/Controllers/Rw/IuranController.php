<?php

namespace App\Http\Controllers\Rw;

use App\Http\Controllers\Controller;
use App\Models\Iuran;
use App\Models\Rukun_tetangga;
use App\Models\IuranGolongan;
use App\Models\Kategori_golongan;
use Illuminate\Http\Request;

class IuranController extends Controller
{
    /**
     * Menampilkan daftar iuran, kategori golongan, dan data RT.
     * Memuat relasi iuran_golongan untuk iuran otomatis.
     */
    public function index()
    {
        // PENTING: Eager load relasi 'iuran_golongan' untuk mencegah error 'sum() on null'
        // saat mengakses data nominal per golongan di Blade.
        $iuran = Iuran::with('iuran_golongan')->paginate(5);
        $kategori_golongan = Kategori_golongan::all();
        $rt = Rukun_tetangga::all();
        $title = 'Iuran';

        return view('rw.iuran.iuran', compact('iuran', 'kategori_golongan', 'rt', 'title'));
    }

    /**
     * Menyimpan data iuran baru.
     * Menangani iuran manual dan otomatis dengan penyimpanan nominal per golongan.
     */
    public function store(Request $request)
    {
        // Debugging: Tampilkan semua data yang diterima dari form.
        // Aktifkan baris ini jika Anda ingin melihat data request secara keseluruhan.
        // dd($request->all());

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
            // PERBAIKAN DI SINI: Gunakan $request->nominal_manual jika jenisnya manual
            'nominal' => $request->jenis === 'manual' ? $request->nominal_manual : null,
        ]);

        // Jika jenis iuran adalah 'otomatis', simpan nominal per golongan
        if ($request->jenis === 'otomatis') {
            // Menggunakan operator null coalescing (?? []) untuk memastikan $request->nominal adalah array
            // jika tidak ada atau null, mencegah error foreach().
            foreach ($request->nominal ?? [] as $golonganId => $nominal) {
                // Pastikan $nominal adalah angka atau set ke 0 jika kosong/null
                $nominalValue = is_numeric($nominal) ? $nominal : 0;

                IuranGolongan::create([
                    'nama' => $iuran->nama, // Menggunakan nama dari iuran yang baru dibuat
                    'id_iuran' => $iuran->id,
                    'id_golongan' => $golonganId,
                    'nominal' => $nominalValue,
                ]);
            }
        }

        // Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('iuran.index')->with('success', 'Iuran berhasil ditambahkan');
    }

    /**
     * Menampilkan form edit untuk iuran tertentu.
     */
    public function edit(string $id)
    {
        // PENTING: Eager load relasi 'iuran_golongan' saat mengedit juga,
        // agar data nominal per golongan tersedia untuk form edit.
        $iuran = Iuran::with('iuran_golongan')->findOrFail($id);
        $kategori_golongan = Kategori_golongan::all();

        // Mengembalikan view edit dengan data iuran dan kategori golongan
        return view('rw.iuran.edit', compact('iuran', 'kategori_golongan')); // Asumsi ada view edit.blade.php
    }

    /**
     * Memperbarui data iuran yang sudah ada.
     */
    public function update(Request $request, string $id)
    {
        // Validasi input dari form
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
            // Hapus semua iuran golongan yang terkait dengan iuran ini sebelum membuat yang baru
            IuranGolongan::where('id_iuran', $iuran->id)->delete();
            // Kemudian buat entri IuranGolongan baru berdasarkan input
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

        // Redirect kembali ke halaman index dengan pesan sukses
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

        // Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('iuran.index')->with('success', 'Iuran berhasil dihapus');
    }
}
