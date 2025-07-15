<?php

namespace App\Http\Controllers\Rw;

use App\Http\Controllers\Controller;
use App\Models\Iuran; // Asumsi model Iuran
use App\Models\Kartu_keluarga; // Asumsi model Kartu_keluarga
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Untuk debugging dengan Log

class TagihanController extends Controller
{
    /**
     * Menampilkan daftar iuran manual.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $title = 'Data Iuran Manual';

        // Mengambil semua nomor Kartu Keluarga (no_kk) unik dari tabel kartu_keluarga
        // untuk mengisi dropdown filter.
        // Asumsi: Model App\Models\KartuKeluarga ada dan memiliki kolom 'no_kk'.
        $kartuKeluargaForFilter = Kartu_keluarga::select('no_kk')
                                            ->distinct()
                                            ->orderBy('no_kk')
                                            ->get();

        // Query dasar untuk iuran manual
        $query = Iuran::where('jenis', 'manual');

        // Filter berdasarkan pencarian nama iuran atau nominal
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('nominal', 'like', '%' . $search . '%');
            });
        }

        // Filter berdasarkan Nomor Kartu Keluarga (no_kk)
        // PENTING: Agar filter ini berfungsi pada data Iuran,
        // model Iuran (atau tabel 'iuran') harus memiliki kolom 'no_kk'
        // atau relasi yang memungkinkan filter berdasarkan no_kk.
        // Jika tidak, baris `$query->where('no_kk', $kkFilter);` di bawah
        // akan menyebabkan error "Unknown column 'no_kk' in 'where clause'".
        // Anda perlu menambahkan kolom 'no_kk' ke tabel 'iuran' jika ini yang Anda inginkan.
        if ($request->filled('no_kk_filter')) { // Menggunakan nama parameter 'no_kk_filter' untuk kejelasan
            $kkFilter = $request->input('no_kk_filter');
            $query->where('no_kk', $kkFilter); // Asumsi: Iuran memiliki kolom 'no_kk'
        }


        // Paginate hasil
        $iuran = $query->orderBy('tgl_tagih', 'desc')->paginate(10); // Menampilkan 10 item per halaman

        return view('rw.tagihan.index', compact('title', 'iuran', 'kartuKeluargaForFilter'));
    }

    /**
     * Menyimpan data iuran manual baru.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        Log::info('Data received for store:', $request->all());

        // Validasi input dari form
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'tgl_tagih' => 'required|date',
            'tgl_tempo' => 'required|date',
            'jenis' => 'required|in:otomatis,manual', // Pastikan jenisnya 'manual'
            'nominal_manual' => 'required_if:jenis,manual|numeric|min:0', // Wajib jika jenis manual
            // Jika Anda ingin menyimpan no_kk saat menambah iuran manual, tambahkan validasi di sini:
            // 'no_kk' => 'nullable|string|max:255', // Contoh: Jika iuran manual bisa terkait dengan KK tertentu
        ], [
            'nama.required' => 'Nama iuran harus diisi.',
            'tgl_tagih.required' => 'Tanggal tagih harus diisi.',
            'tgl_tempo.required' => 'Tanggal tempo harus diisi.',
            'jenis.required' => 'Jenis iuran harus dipilih.',
            'jenis.in' => 'Jenis iuran tidak valid.',
            'nominal_manual.required_if' => 'Nominal manual harus diisi jika jenisnya manual.',
            'nominal_manual.numeric' => 'Nominal manual harus berupa angka.',
            'nominal_manual.min' => 'Nominal manual tidak boleh kurang dari 0.',
        ]);

        // Pastikan hanya jenis 'manual' yang diproses di sini
        if ($validated['jenis'] !== 'manual') {
            return redirect()->back()->with('error', 'Hanya iuran manual yang dapat ditambahkan melalui form ini.');
        }

        try {
            // Simpan data iuran utama
            $iuran = Iuran::create([
                'nama' => $validated['nama'],
                'tgl_tagih' => $validated['tgl_tagih'],
                'tgl_tempo' => $validated['tgl_tempo'],
                'jenis' => 'manual', // Selalu 'manual' karena ini TagihanController manual
                'nominal' => $validated['nominal_manual'], // Simpan nominal manual ke kolom 'nominal'
                // Jika Anda menyimpan no_kk saat menambah iuran manual, tambahkan di sini:
                // 'no_kk' => $request->no_kk, // Contoh
            ]);

            Log::info('Iuran manual created successfully:', $iuran->toArray());

            return redirect()->route('iuran.index')->with('success', 'Iuran manual berhasil ditambahkan.');

        } catch (\Exception $e) {
            Log::error('Error creating iuran manual:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan iuran manual. Error: ' . $e->getMessage());
        }
    }

    /**
     * Memperbarui data iuran manual yang sudah ada.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        Log::info("Data received for update ID {$id}:", $request->all());

        $iuran = Iuran::findOrFail($id);

        // Validasi input dari form
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'tgl_tagih' => 'required|date',
            'tgl_tempo' => 'required|date',
            // Jenis tidak perlu divalidasi karena sudah diasumsikan 'manual' dan tidak diubah via form edit
            'nominal_manual' => 'required|numeric|min:0',
            // Jika Anda mengizinkan update no_kk, tambahkan validasi di sini:
            // 'no_kk' => 'nullable|string|max:255',
        ], [
            'nama.required' => 'Nama iuran harus diisi.',
            'tgl_tagih.required' => 'Tanggal tagih harus diisi.',
            'tgl_tempo.required' => 'Tanggal tempo harus diisi.',
            'nominal_manual.required' => 'Nominal harus diisi.',
            'nominal_manual.numeric' => 'Nominal harus berupa angka.',
            'nominal_manual.min' => 'Nominal tidak boleh kurang dari 0.',
        ]);

        try {
            // Update data iuran utama
            $iuran->update([
                'nama' => $validated['nama'],
                'tgl_tagih' => $validated['tgl_tagih'],
                'tgl_tempo' => $validated['tgl_tempo'],
                'nominal' => $validated['nominal_manual'], // Update nominal manual
                // Jika Anda mengizinkan update no_kk, tambahkan di sini:
                // 'no_kk' => $request->no_kk,
                // Jenis tidak perlu diupdate karena diasumsikan tetap 'manual'
            ]);

            Log::info("Iuran manual ID {$id} updated successfully:", $iuran->toArray());

            return redirect()->route('iuran.index')->with('success', 'Iuran manual berhasil diperbarui.');

        } catch (\Exception $e) {
            Log::error('Error updating iuran manual:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui iuran manual. Error: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus data iuran manual.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $iuran = Iuran::findOrFail($id);    

            // Pastikan hanya iuran manual yang bisa dihapus dari controller ini
            if ($iuran->jenis !== 'manual') {
                return redirect()->back()->with('error', 'Anda tidak dapat menghapus iuran non-manual melalui halaman ini.');
            }

            $iuran->delete();
            Log::info("Iuran manual ID {$id} deleted successfully.");

            return redirect()->route('iuran.index')->with('success', 'Iuran manual berhasil dihapus.');

        } catch (\Exception $e) {
            Log::error('Error deleting iuran manual:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'Gagal menghapus iuran manual. Error: ' . $e->getMessage());
        }
    }
}
