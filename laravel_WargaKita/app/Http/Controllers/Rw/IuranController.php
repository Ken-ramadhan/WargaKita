<?php

namespace App\Http\Controllers\Rw;

use App\Http\Controllers\Controller;
use App\Models\Iuran;
use App\Models\Rukun_tetangga;
use App\Models\IuranGolongan;
use App\Models\Kategori_golongan;
use App\Models\Kartu_keluarga;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IuranController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $rtFilter = $request->input('rt');

        $iuran = Iuran::with('iuran_golongan')
                        ->when($search, function ($query) use ($search) {
                            $query->where('nama', 'like', '%' . $search . '%');
                        })
                        ->paginate(5);

        $kategori_golongan = Kategori_golongan::all();
        $rt = Rukun_tetangga::all();
        $title = 'Iuran';

        return view('rw.iuran.iuran', compact('iuran', 'kategori_golongan', 'rt', 'title'));
    }

   public function store(Request $request)
{
    $validated = $request->validate([
        'nama' => 'required|string|max:255',
        'tgl_tagih' => 'required|date',
        'tgl_tempo' => 'required|date',
        'jenis' => 'required|in:otomatis,manual',
        'nominal_manual' => 'nullable|numeric|min:0',
        'nominal' => 'nullable|array',
        'nominal.*' => 'nullable|numeric|min:0',
    ]);

    // Buat data iuran
    $iuran = Iuran::create([
        'nama' => $request->nama,
        'tgl_tagih' => $request->tgl_tagih,
        'tgl_tempo' => $request->tgl_tempo,
        'jenis' => $request->jenis,
        'nominal' => $request->jenis === 'manual' ? $request->nominal_manual : null,
    ]);

    $kartuKeluargas = Kartu_keluarga::all();

    if ($request->jenis === 'otomatis') {
        $nominalPerGolongan = [];

        foreach ($request->nominal ?? [] as $golonganId => $nominal) {
            $nominalValue = is_numeric($nominal) ? $nominal : 0;
            IuranGolongan::create([
                'nama' => $iuran->nama,
                'id_iuran' => $iuran->id,
                'id_golongan' => $golonganId,
                'nominal' => $nominalValue,
            ]);
            $nominalPerGolongan[$golonganId] = $nominalValue;
        }

        foreach ($kartuKeluargas as $kk) {
            $nominalTagihan = $nominalPerGolongan[$kk->id_golongan] ?? 0;

            try {
                Tagihan::create([
                    'nama' => $iuran->nama,
                    'tgl_tagih' => $iuran->tgl_tagih,
                    'tgl_tempo' => $iuran->tgl_tempo,
                    'jenis' => 'otomatis',
                    'nominal' => $nominalTagihan,
                    'no_kk' => $kk->no_kk,
                    'status_bayar' => 'belum_bayar',
                    'tgl_bayar' => null,
                    'id_iuran' => $iuran->id,
                    'kategori_pembayaran' => null,
                    'bukti_transfer' => null,
                ]);
            } catch (\Exception $e) {
                Log::error("Gagal membuat tagihan otomatis untuk KK {$kk->no_kk}: " . $e->getMessage());
            }
        }
    } else {
        // Manual → buat tagihan langsung ke semua KK pakai nominal_manual
        foreach ($kartuKeluargas as $kk) {
            try {
                Tagihan::create([
                    'nama' => $iuran->nama,
                    'tgl_tagih' => $iuran->tgl_tagih,
                    'tgl_tempo' => $iuran->tgl_tempo,
                    'jenis' => 'manual',
                    'nominal' => $iuran->nominal,
                    'no_kk' => $kk->no_kk,
                    'status_bayar' => 'belum_bayar',
                    'tgl_bayar' => null,
                    'id_iuran' => $iuran->id,
                    'kategori_pembayaran' => null,
                    'bukti_transfer' => null,
                ]);
                Log::info("Tagihan manual dibuat untuk KK {$kk->no_kk} dan Iuran {$iuran->nama}.");
            } catch (\Exception $e) {
                Log::error("Gagal membuat tagihan manual untuk KK {$kk->no_kk}: " . $e->getMessage());
            }
        }
    }

    return redirect()->route('iuran.index')->with('success', 'Iuran dan tagihan berhasil dibuat.');
}


    public function edit(string $id)
    {
        $iuran = Iuran::with('iuran_golongan')->findOrFail($id);
        $kategori_golongan = Kategori_golongan::all();

        return view('rw.iuran.edit', compact('iuran', 'kategori_golongan'));
    }

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
    ]);

    $iuran = Iuran::findOrFail($id);

    $iuran->update([
        'nama' => $request->nama,
        'tgl_tagih' => $request->tgl_tagih,
        'tgl_tempo' => $request->tgl_tempo,
        'jenis' => $request->jenis,
        'nominal' => $request->jenis === 'manual' ? $request->nominal_manual : null,
    ]);

    // ⏬ UPDATE TAGIHAN TERKAIT
    Tagihan::where('id_iuran', $iuran->id)->update([
        'nama' => $iuran->nama,
        'tgl_tagih' => $iuran->tgl_tagih,
        'tgl_tempo' => $iuran->tgl_tempo,
        'jenis' => $iuran->jenis,
        'nominal' => $iuran->jenis === 'manual' ? $iuran->nominal : null,
    ]);

    // ⏬ Khusus untuk iuran otomatis: update nominal per golongan
    if ($request->jenis === 'otomatis') {
        IuranGolongan::where('id_iuran', $iuran->id)->delete();
        foreach ($request->nominal ?? [] as $golonganId => $nominal) {
            IuranGolongan::create([
                'nama' => $iuran->nama,
                'id_iuran' => $iuran->id,
                'id_golongan' => $golonganId,
                'nominal' => is_numeric($nominal) ? $nominal : 0,
            ]);
        }

        // Update nominal tagihan untuk otomatis sesuai golongan
        $golonganNominal = IuranGolongan::where('id_iuran', $iuran->id)
                            ->pluck('nominal', 'id_golongan');

        $kartuKeluargas = Kartu_keluarga::all();
        foreach ($kartuKeluargas as $kk) {
            $nominalTagihan = $golonganNominal[$kk->id_golongan] ?? 0;

            Tagihan::where('id_iuran', $iuran->id)
                ->where('no_kk', $kk->no_kk)
                ->update(['nominal' => $nominalTagihan]);
        }
    } else {
        IuranGolongan::where('id_iuran', $iuran->id)->delete();
    }

    return redirect()->route('iuran.index')->with('success', 'Iuran dan tagihan terkait berhasil diperbarui.');
}


    public function destroy(string $id)
    {
        $iuran = Iuran::findOrFail($id);
        IuranGolongan::where('id_iuran', $iuran->id)->delete();
        $iuran->delete();

        return redirect()->route('iuran.index')->with('success', 'Iuran berhasil dihapus');
    }

    /**
     * Generate tagihan bulanan berdasarkan iuran otomatis
     */
    public function generateMonthlyTagihan()
    {
        $today = now()->startOfDay();

        $iurans = Iuran::where('jenis', 'otomatis')
                    ->whereDay('tgl_tagih', $today->day)
                    ->get();

        foreach ($iurans as $iuran) {
            $golonganNominal = IuranGolongan::where('id_iuran', $iuran->id)
                                ->pluck('nominal', 'id_golongan');

            $kartuKeluargas = Kartu_keluarga::all();

            foreach ($kartuKeluargas as $kk) {
                $nominalTagihan = $golonganNominal[$kk->id_golongan] ?? 0;

                $exists = Tagihan::where('no_kk', $kk->no_kk)
                            ->where('id_iuran', $iuran->id)
                            ->whereMonth('tgl_tagih', $today->month)
                            ->whereYear('tgl_tagih', $today->year)
                            ->exists();

                if (!$exists) {
                    Tagihan::create([
                        'nama' => $iuran->nama,
                        'tgl_tagih' => $today,
                        'tgl_tempo' => $iuran->tgl_tempo ?? $today->copy()->addDays(10),
                        'jenis' => 'otomatis',
                        'nominal' => $nominalTagihan,
                        'no_kk' => $kk->no_kk,
                        'status_bayar' => 'belum_bayar',
                        'tgl_bayar' => null,
                        'id_iuran' => $iuran->id,
                        'kategori_pembayaran' => null,
                        'bukti_transfer' => null,
                    ]);
                }
            }
        }

        return redirect()->route('iuran.index')->with('success', 'Tagihan bulanan berhasil dibuat.');
    }
}
