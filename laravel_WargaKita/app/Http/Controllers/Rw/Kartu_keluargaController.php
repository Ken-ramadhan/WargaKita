<?php

namespace App\Http\Controllers\Rw;
use App\Http\Controllers\Controller;

use App\Models\Kartu_keluarga;
use App\Models\Kategori_golongan;
use App\Models\Rukun_tetangga;
use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
    

    $kartu_keluarga = Kartu_keluarga::with('rukunTetangga' , 'warga')
        ->when($search, function ($query) use ($search) {
            $query->where('no_kk', 'like', '%' . $search . '%')
                ->orWhere('alamat', 'like', '%' . $search . '%');
        })
        ->when($filterRt, function ($query) use ($filterRt) {
            $query->whereHas('rukunTetangga', function ($q) use ($filterRt) {
                $q->where('nomor_rt', $filterRt);
            });
        })
        ->paginate(5)
        ->withQueryString();
    
    $total_kk = Kartu_keluarga::count();
    $rukun_tetangga = Rukun_tetangga::all();
    $kategori_golongan = Kategori_golongan::getEnumNama();
    $warga = Warga::all();
    $title = 'Kartu Keluarga';

    return view('rw.kartu-keluarga.kartu_keluarga', compact('kartu_keluarga', 'rukun_tetangga', 'kategori_golongan', 'warga', 'title','total_kk'));
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
    $request->validate([
        'no_kk' => 'required|unique:kartu_keluarga,no_kk|size:16',
        'alamat'     => 'required|string',
        'id_rt'      => 'required|exists:rukun_tetangga,id',
        'kelurahan'  => 'required|string|max:100',
        'kecamatan'  => 'required|string|max:100',
        'kabupaten'  => 'required|string|max:100',
        'provinsi'   => 'required|string|max:100',
        'kode_pos'   => 'required|string|max:10',
        'tgl_terbit' => 'required|date',
        'golongan'   => 'required',
    ], [
        // Pesan error custom jika perlu
    ]);

    // Ambil ID RW user yang login
    $id_rw = Auth::user()->id_rw;

    // Simpan data ke database
    Kartu_keluarga::create([
        'no_kk'      => $request->no_kk,
        'alamat'     => $request->alamat,
        'id_rt'      => $request->id_rt,
        'id_rw'      => $id_rw, // ← otomatis
        'kelurahan'  => $request->kelurahan,
        'kecamatan'  => $request->kecamatan,
        'kabupaten'  => $request->kabupaten,
        'provinsi'   => $request->provinsi,
        'kode_pos'   => $request->kode_pos,
        'tgl_terbit' => $request->tgl_terbit,
        'golongan'   => $request->golongan,
    ]);

    return redirect()->route('kartu_keluarga.index')
        ->with('success', 'Data kartu keluarga berhasil ditambahkan.');
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
    $request->validate([
        'no_kk' => ['required', 'size:16', Rule::unique('kartu_keluarga', 'no_kk')->ignore($no_kk, 'no_kk')],
        'alamat' => 'required|string',
        'id_rt' => 'required|exists:rukun_tetangga,id',
        'kelurahan' => 'required|string|max:100',
        'kecamatan' => 'required|string|max:100',
        'kabupaten' => 'required|string|max:100',
        'provinsi' => 'required|string|max:100',
        'kode_pos' => 'required|string|max:10',
        'tgl_terbit' => 'required|date',
        'golongan' => ['required', Rule::in(Kategori_golongan::getEnumNama())],
    ]);

    $kartu_keluarga = Kartu_keluarga::where('no_kk', $no_kk)->firstOrFail();

    $kartu_keluarga->update([
        'no_kk' => $request->no_kk,
        'alamat' => $request->alamat,
        'id_rt' => $request->id_rt,
        'id_rw' => Auth::user()->id_rw, // tetap otomatis
        'kelurahan' => $request->kelurahan,
        'kecamatan' => $request->kecamatan,
        'kabupaten' => $request->kabupaten,
        'provinsi' => $request->provinsi,
        'kode_pos' => $request->kode_pos,
        'tgl_terbit' => $request->tgl_terbit,
        'golongan' => $request->golongan,
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
