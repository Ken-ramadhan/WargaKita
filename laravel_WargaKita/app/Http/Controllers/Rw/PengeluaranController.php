<?php

namespace App\Http\Controllers\Rw;
use App\Http\Controllers\Controller;

use App\Models\Pengeluaran;
use App\Models\Rukun_tetangga;
use Illuminate\Http\Request;

class PengeluaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $tahun = $request->input('tahun');
        $bulan = $request->input('bulan');
        $filterRt = $request->rt;

        $pengeluaran = Pengeluaran::with('rukunTetangga')
            ->when($search, function ($query, $search) {
                $searchLower = strtolower($search);

                $query->where(function ($q) use ($search, $searchLower) {
                    $q->where('keterangan', 'like', '%' . $search . '%')
                        ->orWhere('nama_pengeluaran', 'like', '%' . $search . '%');

                    // Pencarian berdasarkan hari Indonesia
                    $hariList = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'];
                    if (in_array($searchLower, $hariList)) {
                        $q->orWhereRaw("DAYNAME(tanggal) = ?", [$this->indoToEnglishDay($searchLower)]);
                    }

                    // Pencarian berdasarkan nama bulan Indonesia
                    $bulanList = ['januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember'];
                    if (in_array($searchLower, $bulanList)) {
                        $bulanAngka = array_search($searchLower, $bulanList) + 1;
                        $q->orWhereMonth('tanggal', $bulanAngka);
                    }
                });
            })
            ->when($filterRt, function ($query) use ($filterRt) {
                $query->whereHas('rukunTetangga', function ($q) use ($filterRt) {
                    $q->where('nomor_rt', $filterRt);
                });
            })
            ->when($tahun, fn($q) => $q->whereYear('tanggal', $tahun))
            ->when($bulan, fn($q) => $q->whereMonth('tanggal', $bulan))
            ->orderBy('created_at', 'desc')
            ->paginate(5)
            ->withQueryString();

        $rukun_tetangga = Rukun_tetangga::all();
        $title = 'Pengeluaran';

        $daftar_tahun = Pengeluaran::selectRaw('YEAR(tanggal) as tahun')->distinct()->orderByDesc('tahun')->pluck('tahun');
        $daftar_bulan = range(1, 12);

        return view('rw.iuran.pengeluaran', compact(
            'pengeluaran',
            'rukun_tetangga',
            'title',
            'daftar_tahun',
            'daftar_bulan',
        ));
    }


    // Tambahkan di dalam class controller
    private function indoToEnglishDay($day)
    {
        return match (strtolower($day)) {
            'senin' => 'Monday',
            'selasa' => 'Tuesday',
            'rabu' => 'Wednesday',
            'kamis' => 'Thursday',
            'jumat' => 'Friday',
            'sabtu' => 'Saturday',
            'minggu' => 'Sunday',
            default => $day,
        };
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
                'id_rt' => 'required|exists:rukun_tetangga,id',
                'nama_pengeluaran' => 'required|string|max:255',
                'jumlah' => 'required|string', // sementara string karena masih mengandung titik
                'tanggal' => 'required|date',
                'keterangan' => 'nullable|string',
            ],
            [
                'id_rt.required' => 'RT Wajib Diisi',
                'nama_pengeluaran.required' => 'Nama Pengeluaran Wajib Diisi',
                'jumlah.required' => 'Jumlah Pengeluaran Wajib Diisi',
                'jumlah.numeric' => 'Jumlah Pengeluaran Harus Berupa Angka',
                'jumlah.digits_between' => 'Jumlah melebihi batas.',
                'tanggal.required' => 'Tanggal Wajib Diisi',
                'tanggal.date' => 'Harus Menggunakan Format Tanggal',
            ]
        );

        // Bersihkan format angka dari titik
        $jumlahBersih = str_replace(['.', ','], '', $validated['jumlah']);

        Pengeluaran::create([
            'id_rt' => $validated['id_rt'],
            'nama_pengeluaran' => $validated['nama_pengeluaran'],
            'jumlah' => $jumlahBersih, // sudah dibersihkan
            'tanggal' => $validated['tanggal'],
            'keterangan' => $validated['keterangan'],
        ]);

        return redirect()->route('pengeluaran.index')->with('success', 'Data pengeluaran berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $pengeluaran = Pengeluaran::findOrFail($id);
        return view('pengeluaran.show', compact('pengeluaran'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $pengeluaran = Pengeluaran::findOrFail($id);
        return view('pengeluaran.edit', compact('pengeluaran'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate(
            [
                'id_rt' => 'required|exists:rukun_tetangga,id',
                'nama_pengeluaran' => 'required|string|max:255',
                'jumlah' => 'required|string', // sementara string karena masih mengandung titik
                'tanggal' => 'required|date',
                'keterangan' => 'nullable|string',
            ],
            [
                'id_rt.required' => 'RT Wajib Diisi',
                'nama_pengeluaran.required' => 'Nama Pengeluaran Wajib Diisi',
                'jumlah.required' => 'Jumlah Pengeluaran Wajib Diisi',
                'jumlah.numeric' => 'Jumlah Pengeluaran Harus Berupa Angka',
                'jumlah.digits_between' => 'Jumlah melebihi batas.',
                'tanggal.required' => 'Tanggal Wajib Diisi',
                'tanggal.date' => 'Harus Menggunakan Format Tanggal',
            ]
        );

        $pengeluaran = Pengeluaran::findOrFail($id);
        // Bersihkan format angka dari titik
        $jumlahBersih = str_replace(['.', ','], '', $validated['jumlah']);

        $pengeluaran->update([
            'id_rt' => $validated['id_rt'],
            'nama_pengeluaran' => $validated['nama_pengeluaran'],
            'jumlah' => $jumlahBersih, // sudah dibersihkan
            'tanggal' => $validated['tanggal'],
            'keterangan' => $validated['keterangan'],
        ]);

        return redirect()->route('pengeluaran.index')->with('success', 'Data pengeluaran berhasil ditambahkan.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $pengeluaran = Pengeluaran::findOrfail($id);
        $pengeluaran->delete();
        return redirect()->route('pengeluaran.index')->with('success', 'Pengeluaran berhasil dihapus.');
    }
}