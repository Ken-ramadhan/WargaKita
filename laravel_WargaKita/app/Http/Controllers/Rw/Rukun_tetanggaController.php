<?php

namespace App\Http\Controllers\Rw;
use App\Http\Controllers\Controller;

use App\Models\Rukun_tetangga;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Rukun_tetanggaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $rukun_tetangga = Rukun_tetangga::paginate(5);
        $title = 'Rukun Tetangga';
        $total_rt = Rukun_tetangga::count();
        return view('rw.data-rt.rukun_tetangga', compact('rukun_tetangga', 'title','total_rt'));
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
    $request->validate([
        'nik' => 'required|unique:rukun_tetangga,nik',
        'nomor_rt' => 'required|string',
        'nama_ketua_rt' => 'required|string|max:255',
        'mulai_menjabat'=> 'required',
        'akhir_jabatan' => 'required',
    ]);

    $id_rw = Auth::user()->id_rw;
    if (!$id_rw) {
        abort(403, 'ID RW tidak ditemukan di akun yang login.');
    }

    Rukun_tetangga::create([
        'nik' => $request->nik,
        'nomor_rt' => $request->nomor_rt,
        'nama_ketua_rt' => $request->nama_ketua_rt,
        'mulai_menjabat' => $request->mulai_menjabat,
        'akhir_jabatan' => $request->akhir_jabatan,
        'id_rw' => $id_rw,
    ]);

    $id_rt = Rukun_tetangga::where('nik', $request->nik)->value('id');

    User::create([
        'nik' => $request->nik,
        'nama' => $request->nama_ketua_rt,
        'password' => bcrypt('password'),
        'id_rt' => $id_rt,
        'id_rw' => $id_rw,
        'role' => 'rt',
    ]);

    return redirect()->route('rukun_tetangga.index')->with('success', 'Rukun Tetangga berhasil ditambahkan.');
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $rukun_tetangga = Rukun_tetangga::findOrFail($id);
        return view('rukun_tetangga.show', compact('rukun_tetangga'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $rukun_tetangga = Rukun_tetangga::findOrFail($id);
        return view('rukun_tetangga.edit', compact('rukun_tetangga'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            "nik" => 'required',
            'nomor_rt' => 'required|string|max:255',
            'nama_ketua_rt' => 'required|string|max:255',
            'mulai_menjabat' => 'required',
            'akhir_jabatan' => 'required',
        ], [
            'nomor_rt.required' => 'Nama Rukun Tetangga harus diisi.',
            'nama_ketua_rt.required' => 'Nama Ketua Rukun Tetangga harus diisi.',
            'mulai_menjabat.required' => 'Mulai Menjabat harus diisi.',
            'akhir_jabatan.required' => 'Akhir Jabatan harus diisi.',
        ]);
        $rukun_tetangga = Rukun_tetangga::findOrFail($id);
        $rukun_tetangga->update([
            'nik' => $request->nik,
            'nomor_rt' => $request->nomor_rt,
            'nama_ketua_rt' => $request->nama_ketua_rt,
            'mulai_menjabat' => $request->mulai_menjabat,
            'akhir_jabatan' => $request->akhir_jabatan,
        ]);
        return redirect()->route('rukun_tetangga.index')->with('success', 'Rukun Tetangga berhasil diperbarui.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
         try {
        Rukun_tetangga::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'RT berhasil dihapus.');
    } catch (\Illuminate\Database\QueryException $e) {
        return redirect()->back()->with('error', 'Tidak bisa menghapus RT karena masih digunakan.');
    }
    }
}
