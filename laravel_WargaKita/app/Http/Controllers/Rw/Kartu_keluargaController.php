<?php

namespace App\Http\Controllers\Rw;
use App\Http\Controllers\Controller;

use App\Models\Kartu_keluarga;
use App\Models\Kategori_golongan;
use App\Models\Rukun_tetangga;
use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class Kartu_keluargaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index(Request $request)
{
    $search = $request->search;
    $filterRt = $request->rt;

    $kartu_keluarga = Kartu_keluarga::with('rukunTetangga','golongan' , 'warga')
        ->when($search, function ($query) use ($search) {
            $query->where('kepala_kk', 'like', '%' . $search . '%')
                ->orWhere('no_kk', 'like', '%' . $search . '%');
        })
        ->when($filterRt, function ($query) use ($filterRt) {
            $query->whereHas('rukunTetangga', function ($q) use ($filterRt) {
                $q->where('nomor_rt', $filterRt);
            });
        })
        ->paginate(5)
        ->withQueryString();

    $rukun_tetangga = Rukun_tetangga::all();
    $kategori_golongan = Kategori_golongan::all();
    $warga = Warga::all();
    $title = 'Kartu Keluarga';

    return view('rw.kartu-keluarga.kartu_keluarga', compact('kartu_keluarga', 'rukun_tetangga', 'kategori_golongan', 'warga', 'title'));
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
    // Validasi input
    $request->validate(
        [
            'no_kk'      => 'required|unique:kartu_keluarga,no_kk|size:16',
            'alamat'     => 'required|string',
            'id_rt'      => 'required|exists:rukun_tetangga,id',
            'rw'         => 'required|max:5',
            'kelurahan'  => 'required|string|max:100',
            'kecamatan'  => 'required|string|max:100',
            'kabupaten'  => 'required|string|max:100',
            'provinsi'   => 'required|string|max:100',
            'kode_pos'   => 'required|string|max:10',
            'tgl_terbit' => 'required|date',
            'id_golongan' => 'required|exists:kategori_golongan,id',
            'jenis'      => 'required'
        ],
        [
            'no_kk.required'      => 'Nomor KK harus diisi.',
            'no_kk.unique'        => 'Nomor KK sudah terdaftar.',
            'no_kk.size'          => 'Nomor KK harus terdiri dari 16 digit.',
            'alamat.required'     => 'Alamat harus diisi.',
            'id_rt.required'      => 'Nomor RT harus diisi.',
            'id_rt.exists'        => 'Nomor RT tidak ditemukan.',
            'rw.required'         => 'Nomor RW harus diisi.',
            'kelurahan.required'  => 'Kelurahan harus diisi.',
            'kecamatan.required'  => 'Kecamatan harus diisi.',
            'kabupaten.required'  => 'Kabupaten harus diisi.',
            'provinsi.required'   => 'Provinsi harus diisi.',
            'kode_pos.required'   => 'Kode pos harus diisi.',
            'tgl_terbit.required' => 'Tanggal terbit harus diisi.',
            'tgl_terbit.date'     => 'Format tanggal terbit tidak valid.',
            'id_golongan.required' => 'Golongan harus dipilih.',
            'id_golongan.exists'   => 'Golongan tidak ditemukan.',
            'jenis.required'        => 'Jenis harus dipilih.',
        ]
    );

    // Simpan data ke database
    Kartu_keluarga::create([
        'no_kk'      => $request->no_kk,
        'alamat'     => $request->alamat,
        'id_rt'      => $request->id_rt,
        'rw'         => $request->rw,
        'kelurahan'  => $request->kelurahan,
        'kecamatan'  => $request->kecamatan,
        'kabupaten'  => $request->kabupaten,
        'provinsi'   => $request->provinsi,
        'kode_pos'   => $request->kode_pos,
        'tgl_terbit' => $request->tgl_terbit,
        'id_golongan' => $request->id_golongan,
        'jenis'      => $request->jenis,
    ]);

    return redirect()->route('kartu_keluarga.index')->with('success', 'Data kartu keluarga berhasil ditambahkan.');
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $kartu_keluarga = Kartu_keluarga::findOrFail($id);
        return view('kartu_keluarga.show', compact('kartu_keluarga'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $kartu_keluarga = Kartu_keluarga::findOrFail($id);
        return view('kartu_keluarga.edit', compact('kartu_keluarga'));
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, string $no_kk)
{
    // Validasi
    $request->validate([
        'no_kk'        => ['required', 'size:16', Rule::unique('kartu_keluarga', 'no_kk')->ignore($no_kk, 'no_kk')],
        'alamat'       => 'required|string',
        'id_rt'        => 'required|exists:rukun_tetangga,id',
        'rw'           => 'required|max:5',
        'kelurahan'    => 'required|string|max:100',
        'kecamatan'    => 'required|string|max:100',
        'kabupaten'    => 'required|string|max:100',
        'provinsi'     => 'required|string|max:100',
        'kode_pos'     => 'required|string|max:10',
        'tgl_terbit'   => 'required|date',
        'id_golongan'  => 'required|exists:kategori_golongan,id',
        'jenis'        => 'required',
    ], [
        'no_kk.required'      => 'Nomor KK harus diisi.',
        'no_kk.size'          => 'Nomor KK harus terdiri dari 16 digit.',
        'no_kk.unique'        => 'Nomor KK sudah terdaftar.',
        'alamat.required'     => 'Alamat harus diisi.',
        'id_rt.required'      => 'RT harus dipilih.',
        'id_rt.exists'        => 'RT tidak ditemukan.',
        'rw.required'         => 'RW harus diisi.',
        'kelurahan.required'  => 'Kelurahan harus diisi.',
        'kecamatan.required'  => 'Kecamatan harus diisi.',
        'kabupaten.required'  => 'Kabupaten harus diisi.',
        'provinsi.required'   => 'Provinsi harus diisi.',
        'kode_pos.required'   => 'Kode pos harus diisi.',
        'tgl_terbit.required' => 'Tanggal terbit harus diisi.',
        'tgl_terbit.date'     => 'Format tanggal terbit tidak valid.',
        'id_golongan.required' => 'Golongan harus dipilih.',
        'id_golongan.exists'   => 'Golongan tidak ditemukan.',
        'jenis.required'        => 'Jenis harus dipilih.',
    ]);

    // Ambil data KK lama
    $kartu_keluarga = Kartu_keluarga::where('no_kk', $no_kk)->firstOrFail();

    // Update data
    $kartu_keluarga->update([
        'no_kk'        => $request->no_kk,
        'alamat'       => $request->alamat,
        'id_rt'        => $request->id_rt,
        'rw'           => $request->rw,
        'kelurahan'    => $request->kelurahan,
        'kecamatan'    => $request->kecamatan,
        'kabupaten'    => $request->kabupaten,
        'provinsi'     => $request->provinsi,
        'kode_pos'     => $request->kode_pos,
        'tgl_terbit'   => $request->tgl_terbit,
        'id_golongan'  => $request->id_golongan,
        'jenis'        => $request->jenis,
    ]);

    return redirect()->route('kartu_keluarga.index')
        ->with('success', 'Data kartu keluarga berhasil diperbarui.');
}
    /**
     * Remove the specified resource from storage.
     */
public function destroy(string $id)
    {
        //
         try {
        Kartu_keluarga::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'KK berhasil dihapus.');
    } catch (\Illuminate\Database\QueryException $e) {
        return redirect()->back()->with('error', 'Tidak bisa menghapus KK karena masih digunakan.');
    }
    }
}
