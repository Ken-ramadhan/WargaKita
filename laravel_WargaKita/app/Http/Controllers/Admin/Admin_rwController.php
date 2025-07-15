<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rw;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\ViewName;

class Admin_rwController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $rw = Rw::paginate(10);
        $title = 'RW';
        return View('admin.data-rw.rw', compact('rw', 'title'));
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
        //
        $request->validate([
            'nik' => 'required|unique:rw,nik',
            'nomor_rw' => 'required|string',
            'nama_ketua_rw' => 'required|string|max:255',
            'mulai_menjabat'=> ' required',
            'akhir_jabatan' => 'required',
        ], [
            'nik.required' => 'NIK harus diisi.',
            'nik.unique' => 'NIK sudah terdaftar.',
            'nomor_rw.required' => 'Nomor Rukun Warga harus diisi.',
            'nama_ketua_rw.required' => 'Nama Ketua Rukun Warga harus diisi.',
            'mulai_menjabat.required' => 'Mulai Menjabat harus diisi.',
            'akhir_jabatan.required' => 'Akhir Menjabat harus diisi.',
        ]);

        Rw::create([
            'nik' => $request->nik,
            'nomor_rw' => $request->nomor_rw,
            'nama_ketua_rw' => $request->nama_ketua_rw,
            'mulai_menjabat' => $request->mulai_menjabat,
            'akhir_jabatan' => $request->akhir_jabatan,
        ]);

        $id_rw = Rw::where('nik', $request->nik)->value('id');

        User::create([
            'nik' => $request->nik,
            'nama' => $request->nama_ketua_rw,
            'password' => bcrypt('password'),
            'role' => 'rw',
            'id_rw' => $id_rw,
        ]);

        return redirect()->route('data_rw.index')->with('success', 'Rukun Warga berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $rw = Rw::findOrFail($id);
        return view('admin.data-rw.show', compact('rw'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $rw = Rw::findOrFail($id);
        return view('admin.data-rw.edit', compact('rw'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            'nik' => [
                'required',
                Rule::unique('rw')->ignore($id),
            ],
            'nomor_rw' => 'required|string',
            'nama_ketua_rw' => 'required|string|max:255',
            'mulai_menjabat'=> ' required',
            'akhir_jabatan' => 'required',
        ],
        [
            'nik.required' => 'NIK harus diisi.',
            'nik.unique' => 'NIK sudah terdaftar.',
            'nomor_rw.required' => 'Nomor Rukun Warga harus diisi.',
            'nama_ketua_rw.required' => 'Nama Ketua Rukun Warga harus diisi.',
            'mulai_menjabat.required' => 'Mulai Menjabat harus diisi.',
            'akhir_jabatan.required' => 'Akhir Menjabat harus diisi.',
        ]
        );
        $rw = Rw::findOrFail($id);
        $rw->update([
            'nik' => $request->nik,
            'nomor_rw' => $request->nomor_rw,
            'nama_ketua_rw' => $request->nama_ketua_rw,
            'mulai_menjabat' => $request->mulai_menjabat,
            'akhir_jabatan' => $request->akhir_jabatan,
        ]);
        return redirect()->route('data_rw.index')->with('success', 'Rukun Warga berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
         try {
        Rw::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'RW berhasil dihapus.');
    } catch (\Illuminate\Database\QueryException $e) {
        return redirect()->back()->with('error', 'Tidak bisa menghapus RW karena masih digunakan.');
    }
    }
}
