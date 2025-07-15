<?php

namespace App\Http\Controllers\Rw;

use App\Http\Controllers\Controller;
use App\Models\Kartu_keluarga;
use App\Models\Tagihan; // MENGGUNAKAN MODEL TAGIHAN SEKARANG
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Untuk debugging dengan Log

class TagihanController extends Controller
{
    /**
     * Menampilkan daftar tagihan manual.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $title = 'Data Tagihan Manual'; // Judul diubah

        // Mengambil semua nomor Kartu Keluarga (no_kk) unik dari tabel kartu_keluarga
        // untuk mengisi dropdown filter.
        $kartuKeluargaForFilter = Kartu_keluarga::select('no_kk')
                                            ->distinct()
                                            ->orderBy('no_kk')
                                            ->get();

        // Query dasar untuk tagihan manual
        $query = Tagihan::where('jenis','manual'); // MENGGUNAKAN MODEL TAGIHAN

        // Filter berdasarkan pencarian nama tagihan atau nominal
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('nominal', 'like', '%' . $search . '%');
            });
        }

        // Filter berdasarkan Nomor Kartu Keluarga (no_kk)
        // PENTING: Agar filter ini berfungsi pada data Tagihan,
        // model Tagihan (atau tabel 'tagihan') harus memiliki kolom 'no_kk'.
        if ($request->filled('no_kk_filter')) {
            $kkFilter = $request->input('no_kk_filter');
            $query->where('no_kk', $kkFilter); // Asumsi: Tagihan memiliki kolom 'no_kk'
        }

        // Paginate hasil
        $tagihan = $query->orderBy('tgl_tagih', 'desc')->paginate(10); // Variabel diubah menjadi $tagihan

        // Mengirimkan variabel $tagihan ke view
        return view('rw.iuran.tagihan', compact('title', 'tagihan', 'kartuKeluargaForFilter')); // Variabel diubah menjadi $tagihan
    }

    /**
     * Menyimpan data tagihan manual baru.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        Log::info('Data received for store tagihan:', $request->all()); // Pesan log diubah

        // Validasi input dari form
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'tgl_tagih' => 'required|date',
            'tgl_tempo' => 'required|date',
            'jenis' => 'required|in:otomatis,manual', // Pastikan jenisnya 'manual'
            'nominal_manual' => 'required_if:jenis,manual|numeric|min:0', // Wajib jika jenis manual
            'no_kk' => 'required|string|max:255|exists:kartu_keluarga,no_kk', // Validasi no_kk
            'status_bayar' => 'required|in:sudah_bayar,belum_bayar', // Validasi status_bayar
        ], [
            'nama.required' => 'Nama tagihan harus diisi.', // Pesan validasi diubah
            'tgl_tagih.required' => 'Tanggal tagih harus diisi.',
            'tgl_tempo.required' => 'Tanggal tempo harus diisi.',
            'jenis.required' => 'Jenis tagihan harus dipilih.', // Pesan validasi diubah
            'jenis.in' => 'Jenis tagihan tidak valid.', // Pesan validasi diubah
            'nominal_manual.required_if' => 'Nominal manual harus diisi jika jenisnya manual.',
            'nominal_manual.numeric' => 'Nominal manual harus berupa angka.',
            'nominal_manual.min' => 'Nominal manual tidak boleh kurang dari 0.',
            'no_kk.required' => 'Nomor Kartu Keluarga harus diisi.',
            'no_kk.exists' => 'Nomor Kartu Keluarga tidak ditemukan di database.',
            'status_bayar.required' => 'Status pembayaran harus dipilih.',
            'status_bayar.in' => 'Status pembayaran tidak valid.',
        ]);

        // Pastikan hanya jenis 'manual' yang diproses di sini
        if ($validated['jenis'] !== 'manual') {
            return redirect()->back()->with('error', 'Hanya tagihan manual yang dapat ditambahkan melalui form ini.'); // Pesan error diubah
        }

        try {
            // Simpan data tagihan
            $tagihan = Tagihan::create([ // MENGGUNAKAN MODEL TAGIHAN
                'nama' => $validated['nama'],
                'tgl_tagih' => $validated['tgl_tagih'],
                'tgl_tempo' => $validated['tgl_tempo'],
                'jenis' => 'manual', // Selalu 'manual' karena ini TagihanController manual
                'nominal' => $validated['nominal_manual'], // Simpan nominal manual ke kolom 'nominal'
                'no_kk' => $validated['no_kk'], // Simpan no_kk
                'status_bayar' => $validated['status_bayar'], // Simpan status_bayar
            ]);

            Log::info('Tagihan manual created successfully:', $tagihan->toArray()); // Pesan log diubah

            return redirect()->route('iuran.index')->with('success', 'Tagihan manual berhasil ditambahkan.'); // Pesan sukses diubah

        } catch (\Exception $e) {
            Log::error('Error creating tagihan manual:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]); // Pesan log diubah
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan tagihan manual. Error: ' . $e->getMessage()); // Pesan error diubah
        }
    }

    /**
     * Memperbarui data tagihan manual yang sudah ada.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        Log::info("Data received for update tagihan ID {$id}:", $request->all()); // Pesan log diubah

        $tagihan = Tagihan::findOrFail($id); // MENGGUNAKAN MODEL TAGIHAN

        // Validasi input dari form
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'tgl_tagih' => 'required|date',
            'tgl_tempo' => 'required|date',
            'nominal_manual' => 'required|numeric|min:0',
            'no_kk' => 'required|string|max:255|exists:kartu_keluarga,no_kk', // Validasi no_kk
            'status_bayar' => 'required|in:sudah_bayar,belum_bayar', // Validasi status_bayar
        ], [
            'nama.required' => 'Nama tagihan harus diisi.', // Pesan validasi diubah
            'tgl_tagih.required' => 'Tanggal tagih harus diisi.',
            'tgl_tempo.required' => 'Tanggal tempo harus diisi.',
            'nominal_manual.required' => 'Nominal harus diisi.',
            'nominal_manual.numeric' => 'Nominal harus berupa angka.',
            'nominal_manual.min' => 'Nominal tidak boleh kurang dari 0.',
            'no_kk.required' => 'Nomor Kartu Keluarga harus diisi.',
            'no_kk.exists' => 'Nomor Kartu Keluarga tidak ditemukan di database.',
            'status_bayar.required' => 'Status pembayaran harus dipilih.',
            'status_bayar.in' => 'Status pembayaran tidak valid.',
        ]);

        try {
            // Update data tagihan
            $tagihan->update([ // MENGGUNAKAN MODEL TAGIHAN
                'nama' => $validated['nama'],
                'tgl_tagih' => $validated['tgl_tagih'],
                'tgl_tempo' => $validated['tgl_tempo'],
                'nominal' => $validated['nominal_manual'],
                'no_kk' => $validated['no_kk'], // Update no_kk
                'status_bayar' => $validated['status_bayar'], // Update status_bayar
            ]);

            Log::info("Tagihan manual ID {$id} updated successfully:", $tagihan->toArray()); // Pesan log diubah

            return redirect()->route('iuran.index')->with('success', 'Tagihan manual berhasil diperbarui.'); // Pesan sukses diubah

        } catch (\Exception $e) {
            Log::error('Error updating tagihan manual:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]); // Pesan log diubah
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui tagihan manual. Error: ' . $e->getMessage()); // Pesan error diubah
        }
    }

    /**
     * Menghapus data tagihan manual.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $tagihan = Tagihan::findOrFail($id); // MENGGUNAKAN MODEL TAGIHAN

            // Pastikan hanya tagihan manual yang bisa dihapus dari controller ini
            if ($tagihan->jenis !== 'manual') {
                return redirect()->back()->with('error', 'Anda tidak dapat menghapus tagihan non-manual melalui halaman ini.'); // Pesan error diubah
            }

            $tagihan->delete();
            Log::info("Tagihan manual ID {$id} deleted successfully."); // Pesan log diubah

            return redirect()->route('iuran.index')->with('success', 'Tagihan manual berhasil dihapus.'); // Pesan sukses diubah

        } catch (\Exception $e) {
            Log::error('Error deleting tagihan manual:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]); // Pesan log diubah
            return redirect()->back()->with('error', 'Gagal menghapus tagihan manual. Error: ' . $e->getMessage()); // Pesan error diubah
        }
    }
}
