<?php

namespace App\Http\Controllers\Rw;

use App\Http\Controllers\Controller;
use App\Models\Iuran;
use App\Models\Rukun_tetangga;
use App\Models\IuranGolongan;
use App\Models\Kartu_keluarga;
use App\Models\Kategori_golongan;
use App\Models\Tagihan;
use Illuminate\Http\Request;

class IuranController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $iuran = Iuran::with('iuran_golongan')
            ->when($search, fn($query) => $query->where('nama', 'like', '%' . $search . '%'))
            ->paginate(5);

        $golongan_list = Kategori_golongan::getEnumNama();
        $rt = Rukun_tetangga::all();
        $title = 'Iuran';

        return view('rw.iuran.iuran', compact('iuran', 'golongan_list', 'rt', 'title'));
    }

 public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'tgl_tagih' => 'required|date',
            'tgl_tempo' => 'required|date',
            'jenis' => 'required|in:manual,otomatis',
            // 'nominal' => 'required_if:jenis,manual|numeric|min:0',
        ]);

        $iuran = Iuran::create([
            'nama' => $request->nama,
            'tgl_tagih' => $request->tgl_tagih,
            'tgl_tempo' => $request->tgl_tempo,
            'jenis' => $request->jenis,
            'nominal' => $request->jenis === 'manual' ? $request->nominal : null,
        ]);

        // ⬇⬇⬇ Tambahkan ini untuk jenis manual
        if ($request->jenis === 'manual') {
            $kartuKeluargaList = Kartu_keluarga::all();

            foreach ($kartuKeluargaList as $kk) {
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
            }
        }

        // Kalau kamu belum butuh otomatis, bagian ini dikomen saja
        // if ($request->jenis === 'otomatis') {
        //     ... logic iuran otomatis ...
        // }

        return redirect()->route('iuran.index')->with('success', 'Iuran berhasil dibuat beserta tagihannya.');
    }


    public function edit(string $id)
    {
        $iuran = Iuran::with('iuran_golongan')->findOrFail($id);
        $golongan_list = Kartu_keluarga::select('golongan')->distinct()->pluck('golongan');

        return view('rw.iuran.edit', compact('iuran', 'golongan_list'));
    }

    public function update(Request $request, string $id)
    {
        $iuran = Iuran::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'tgl_tagih' => 'required|date',
            'tgl_tempo' => 'required|date',
            'jenis' => 'required|in:manual,otomatis',
        ]);

        $iuran->update([
            'nama' => $request->nama,
            'tgl_tagih' => $request->tgl_tagih,
            'tgl_tempo' => $request->tgl_tempo,
            'jenis' => $request->jenis,
            'nominal' => $request->jenis === 'manual' ? $request->nominal : null,
        ]);

        IuranGolongan::where('id_iuran', $iuran->id)->delete();

        if ($request->jenis === 'otomatis') {
            foreach ($request->input('nominal', []) as $golongan => $nominal) {
                if ($nominal !== null) {
                    IuranGolongan::create([
                        'id_iuran' => $iuran->id,
                        'golongan' => $golongan,
                        'nominal' => $nominal,
                    ]);
                }
            }

            $kkList = Kartu_keluarga::all();
            $iuranNominals = IuranGolongan::where('id_iuran', $iuran->id)->pluck('nominal', 'golongan');

            foreach ($kkList as $kk) {
                $nominalTagihan = $iuranNominals[$kk->golongan] ?? 0;

                Tagihan::where('id_iuran', $iuran->id)
                    ->where('no_kk', $kk->no_kk)
                    ->update(['nominal' => $nominalTagihan]);
            }
        }

        return redirect()->route('iuran.index')->with('success', 'Iuran berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $iuran = Iuran::findOrFail($id);
        IuranGolongan::where('id_iuran', $iuran->id)->delete();
        $iuran->delete();

        return redirect()->route('iuran.index')->with('success', 'Iuran berhasil dihapus.');
    }

    public function generateMonthlyTagihan()
    {
        $today = now()->startOfDay();

        $iurans = Iuran::where('jenis', 'otomatis')
            ->whereDay('tgl_tagih', $today->day)
            ->get();

        foreach ($iurans as $iuran) {
            $iuranNominals = IuranGolongan::where('id_iuran', $iuran->id)
                ->pluck('nominal', 'golongan');

            $kkList = Kartu_keluarga::all();

            foreach ($kkList as $kk) {
                $nominalTagihan = $iuranNominals[$kk->golongan] ?? 0;

                $exists = Tagihan::where('id_iuran', $iuran->id)
                    ->where('no_kk', $kk->no_kk)
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
