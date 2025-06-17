<?php

namespace App\Http\Controllers;

use App\Models\Warga;
use Illuminate\Http\Request;

class WargaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $warga = Warga::all();
        return view('warga', compact('warga'));
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
        //validasi input
        // pastikan nik unik, no_kk ada di tabel kartu_keluarga, nama tidak boleh kosong
        $request->validate([
            'nik' => 'required|unique:warga,nik|max:16',
            'no_kk' => 'required|exists:kartu_keluarga,no_kk|max:16',
            'nama' => 'required|string|max:255',
        ],
        [
            'nik.required' => 'NIK harus diisi.',
            'nik.unique' => 'NIK sudah terdaftar.',
            'no_kk.required' => 'Nomor KK harus diisi.',
            'no_kk.exists' => 'Nomor KK tidak ditemukan.',
            'nama.required' => 'Nama harus diisi.',
        ]);


        // membuat data warga baru
        // pastikan no_kk sudah ada di tabel kartu_keluarga
        Warga::create([
            'nik' => $request->nik,
            'no_kk' => $request->no_kk,
            'nama' => $request->nama,
        ]);
        return redirect()->route('warga.index')->with('success', 'Warga berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $warga = Warga::findOrFail($id);
        return view('warga.show', compact('warga'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //

        $warga = Warga::findOrFail($id);
        return view('warga.edit', compact('warga'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            'nik' => 'required|max:16|unique:warga,nik,' . $id,
            'no_kk' => 'required|exists:kartu_keluarga,no_kk|max:16' .$id,
            'nama' => 'required|string|max:255',
        ]);

        $warga = Warga::findOrFail($id);

        $warga->update([
            'nik' => $request->nik,
            'no_kk' => $request->no_kk,
            'nama' => $request->nama,
        ]);
        return redirect()->route('warga.index')->with('success', 'Warga berhasil diperbarui.');
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $warga = Warga::findOrFail($id);
        $warga->delete();
        return redirect()->route('warga.index')->with('success', 'Warga berhasil dihapus.');
    }
}
