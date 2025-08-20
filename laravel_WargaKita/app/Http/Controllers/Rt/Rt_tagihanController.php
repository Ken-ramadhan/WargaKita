<?php

namespace App\Http\Controllers\Rt;

use App\Http\Controllers\Controller;
use App\Models\Iuran;
use App\Models\Kartu_keluarga;
use App\Models\Tagihan;
use App\Models\RukunTetangga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Rt_tagihanController extends Controller
{
    /**
     * Menampilkan daftar tagihan manual dengan filter dan total nominal.
     */
    public function index(Request $request)
    {
        $title = 'Data Tagihan Manual';

        $kartuKeluargaForFilter = Kartu_keluarga::select('no_kk')->distinct()->orderBy('no_kk')->get();
        $iurans = Iuran::select('id', 'nama', 'nominal')->get(); // Opsional

        $query = Tagihan::where('jenis', 'manual');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('nominal', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('no_kk_filter')) {
            $query->where('no_kk', $request->input('no_kk_filter'));
        }

        // Hitung total tagihan berdasarkan filter
        $totalNominal = $query->sum('nominal');

        // Paginate hasil
        $tagihan = $query->orderBy('tgl_tagih', 'desc')->paginate(10);

        return view('rt.iuran.tagihan', compact('title', 'tagihan', 'kartuKeluargaForFilter', 'iurans', 'totalNominal'));
    }

    /**
     * Menyimpan data tagihan manual baru.
     */
    public function store(Request $request)
    {
        Log::info('Data received for store tagihan:', $request->all());

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'tgl_tagih' => 'required|date',
            'tgl_tempo' => 'required|date',
            'jenis' => 'required|in:otomatis,manual',
            'nominal_manual' => 'required_if:jenis,manual|numeric|min:0',
            'no_kk' => 'required|string|max:255|exists:kartu_keluarga,no_kk',
            'status_bayar' => 'required|in:sudah_bayar,belum_bayar',
            'tgl_bayar' => 'nullable|date',
            'id_iuran' => 'nullable|exists:iuran,id',
            'kategori_pembayaran' => 'nullable|in:transfer,tunai',
            'bukti_transfer' => 'nullable|string|max:255',
        ]);

        if ($validated['jenis'] !== 'manual') {
            return redirect()->back()->with('error', 'Hanya tagihan manual yang dapat ditambahkan melalui form ini.');
        }

        try {
            $dataToStore = [
                'nama' => $validated['nama'],
                'tgl_tagih' => $validated['tgl_tagih'],
                'tgl_tempo' => $validated['tgl_tempo'],
                'jenis' => 'manual',
                'nominal' => $validated['nominal_manual'],
                'no_kk' => $validated['no_kk'],
                'status_bayar' => $validated['status_bayar'],
                'tgl_bayar' => $validated['tgl_bayar'] ?? null,
                'id_iuran' => $validated['id_iuran'] ?? null,
                'kategori_pembayaran' => $validated['kategori_pembayaran'] ?? null,
                'bukti_transfer' => $validated['bukti_transfer'] ?? null,
            ];

            Tagihan::create($dataToStore);

            return redirect()->route('rt_iuran.index')->with('success', 'Tagihan manual berhasil ditambahkan.');

        } catch (\Exception $e) {
            Log::error('Error creating tagihan manual:', ['message' => $e->getMessage()]);
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan tagihan manual. Error: ' . $e->getMessage());
        }
    }

    /**
     * Memperbarui tagihan manual.
     */
    public function update(Request $request, $id)
    {
        Log::info("Data received for update tagihan ID {$id}:", $request->all());

        $tagihan = Tagihan::findOrFail($id);

        $validated = $request->validate([
            'status_bayar' => 'required|in:sudah_bayar,belum_bayar',
            'tgl_bayar' => 'nullable|date',
            'id_iuran' => 'nullable|exists:iuran,id',
            'kategori_pembayaran' => 'nullable|in:transfer,tunai',
            'bukti_transfer' => 'nullable|string|max:255',
        ]);

        try {
            $tagihan->update([
                'status_bayar' => $validated['status_bayar'],
                'tgl_bayar' => $validated['tgl_bayar'] ?? null,
                'id_iuran' => $validated['id_iuran'] ?? null,       
                'kategori_pembayaran' => $validated['kategori_pembayaran'] ?? null,
                'bukti_transfer' => $validated['bukti_transfer'] ?? null,
            ]);

            return redirect()->route('rt_tagihan.index')->with('success', 'Tagihan manual berhasil diperbarui.');

        } catch (\Exception $e) {
            Log::error('Error updating tagihan manual:', ['message' => $e->getMessage()]);
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui tagihan manual. Error: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus tagihan manual.
     */
    public function destroy($id)
    {
        try {
            $tagihan = Tagihan::findOrFail($id);

            if ($tagihan->jenis !== 'manual') {
                return redirect()->back()->with('error', 'Anda tidak dapat menghapus tagihan non-manual.');
            }

            $tagihan->delete();

            return redirect()->route('rt_iuran.index')->with('success', 'Tagihan manual berhasil dihapus.');

        } catch (\Exception $e) {
            Log::error('Error deleting tagihan manual:', ['message' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Gagal menghapus tagihan manual. Error: ' . $e->getMessage());
        }
    }
}
