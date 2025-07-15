<?php

namespace App\Http\Controllers\Rt;

use App\Http\Controllers\Controller;
use App\Models\Kartu_keluarga;
use App\Models\Kategori_golongan;
use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class Rt_kartu_keluargaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $search = $request->search;

    $rt_id = Auth::user()->rukunTetangga->id;
    $total_kk = Kartu_keluarga::where('id_rt', $rt_id)->count();

    if (!$rt_id) {
        abort(403, 'RT tidak ditemukan. Hubungkan KK dengan RT.');
    }

    $kartuKeluarga = Kartu_keluarga::with(['warga'])
        ->where('id_rt', $rt_id)
        ->when($search, function ($query) use ($search) {
            $query->where('alamat', 'like', '%' . $search . '%')
                  ->orWhere('no_kk', 'like', '%' . $search . '%');
        })
        ->paginate(5)
        ->withQueryString();

    $kategori_golongan = Kategori_golongan::getEnumNama();

    // ✅ Pakai relasi KK → RT
    $warga = Warga::whereHas('kartuKeluarga', function($q) use ($rt_id) {
        $q->where('id_rt', $rt_id);
    })->get();

    $title = 'Kartu Keluarga';

    return view('rt.kartu-keluarga.kartu_keluarga', compact(
        'kartuKeluarga',
        'kategori_golongan',
        'warga',
        'title',
        'total_kk'
    ));
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategori_golongan = Kategori_golongan::getEnumNama();
        $title = 'Tambah Kartu Keluarga';
        return view('rt.kartu_keluarga.create', compact('kategori_golongan', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'no_kk'      => 'required|unique:kartu_keluarga,no_kk|size:16',
                'alamat'     => 'required|string',
                'kelurahan'  => 'required|string|max:100',
                'kecamatan'  => 'required|string|max:100',
                'kabupaten'  => 'required|string|max:100',
                'provinsi'   => 'required|string|max:100',
                'kode_pos'   => 'required|string|max:10',
                'tgl_terbit' => 'required|date',
                'golongan'   => 'required',
            ],
            [
                'no_kk.required'      => 'Nomor Kartu Keluarga harus diisi.',
                'no_kk.unique'        => 'Nomor Kartu Keluarga sudah terdaftar.',
                'no_kk.size'          => 'Nomor Kartu Keluarga harus terdiri dari 16 karakter.',
                'alamat.required'     => 'Alamat harus diisi.',
                'kelurahan.required'  => 'Kelurahan harus diisi.',
                'kecamatan.required'  => 'Kecamatan harus diisi.',
                'kabupaten.required'  => 'Kabupaten harus diisi.',
                'provinsi.required'   => 'Provinsi harus diisi.',
                'kode_pos.required'   => 'Kode Pos harus diisi.',
                'tgl_terbit.required' => 'Tanggal terbit harus diisi.', 
                'golongan.required'   => 'Golongan harus diisi.',
            ]
        );

        $id_rt = Auth::user()->id_rt;
        $id_rw = Auth::user()->rukunTetangga->id_rw;

        Kartu_keluarga::create([
            'no_kk'      => $request->no_kk,
            'alamat'     => $request->alamat,
            'id_rt'      => $id_rt,
            'id_rw'      => $id_rw,
            'kelurahan'  => $request->kelurahan,
            'kecamatan'  => $request->kecamatan,
            'kabupaten'  => $request->kabupaten,
            'provinsi'   => $request->provinsi,
            'kode_pos'   => $request->kode_pos,
            'tgl_terbit' => $request->tgl_terbit,
            'golongan'   => $request->golongan,
        ]);

        return redirect()->route('rt_kartu_keluarga.index')
            ->with('success', 'Data kartu keluarga berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $kartu_keluarga = Kartu_keluarga::findOrFail($id);
        $this->authorizeRt($kartu_keluarga);

        return view('rt_kartu_keluarga.show', compact('kartu_keluarga'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $kartu_keluarga = Kartu_keluarga::findOrFail($id);
        $this->authorizeRt($kartu_keluarga);

        $kategori_golongan = Kategori_golongan::getEnumNama();
        $title = 'Edit Kartu Keluarga';

        return view('rt_kartu_keluarga.edit', compact('kartu_keluarga', 'kategori_golongan', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $no_kk)
    {
        $request->validate(
            [
                'no_kk' => [
                    'required',
                    'size:16',
                    Rule::unique('kartu_keluarga', 'no_kk')->ignore($no_kk, 'no_kk'),
                ],
                'alamat'     => 'required|string',
                'kelurahan'  => 'required|string|max:100',
                'kecamatan'  => 'required|string|max:100',
                'kabupaten'  => 'required|string|max:100',
                'provinsi'   => 'required|string|max:100',
                'kode_pos'   => 'required|string|max:10',
                'tgl_terbit' => 'required|date',
                'golongan'   => 'required',
            ],
            [
                'no_kk.unique' => 'Nomor Kartu Keluarga sudah digunakan.',
                'no_kk.size'   => 'Nomor Kartu Keluarga harus terdiri dari 16 karakter.',
                'no_kk.required' => 'Nomor Kartu Keluarga harus diisi.',
                'alamat.required' => 'Alamat harus diisi.',
                'kelurahan.required' => 'Kelurahan harus diisi.',
                'kecamatan.required' => 'Kecamatan harus diisi.',
                'kabupaten.required' => 'Kabupaten harus diisi.',
                'provinsi.required' => 'Provinsi harus diisi.',
                'kode_pos.required' => 'Kode Pos harus diisi.',
                'kode_pos.max' => 'Kode Pos maksimal 10 karakter.',
                'tgl_terbit.required' => 'Tanggal terbit harus diisi.',
                'golongan.required' => 'Golongan harus diisi.',
            ]

        );

        $kartu_keluarga = Kartu_keluarga::where('no_kk', $no_kk)->firstOrFail();
        $this->authorizeRt($kartu_keluarga);

        $id_rt = Auth::user()->id_rt;
        $id_rw = Auth::user()->rukunTetangga->id_rw;

        $kartu_keluarga->update([
            'no_kk'      => $request->no_kk,
            'alamat'     => $request->alamat,
            'id_rt'      => $id_rt, // tetap fix
            'rw'         => $id_rw,
            'kelurahan'  => $request->kelurahan,
            'kecamatan'  => $request->kecamatan,
            'kabupaten'  => $request->kabupaten,
            'provinsi'   => $request->provinsi,
            'kode_pos'   => $request->kode_pos,
            'tgl_terbit' => $request->tgl_terbit,
            'golongan'   => $request->golongan,
        ]);

        return redirect()->route('rt_kartu_keluarga.index')
            ->with('success', 'Data kartu keluarga berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kartu_keluarga = Kartu_keluarga::findOrFail($id);
        $this->authorizeRt($kartu_keluarga);

        try {
            $kartu_keluarga->delete();
            return redirect()->back()->with('success', 'Kartu keluarga berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()->with('error', 'Tidak bisa menghapus karena data masih digunakan.');
        }
    }

    /**
     * Pastikan user RT hanya bisa mengelola KK di RT-nya.
     */
    private function authorizeRt(Kartu_keluarga $kartu_keluarga)
    {
        if ($kartu_keluarga->id_rt != Auth::user()->id_rt) {
            abort(403, 'Anda tidak berhak mengakses data ini.');
        }
    }
}
