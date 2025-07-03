<?php

namespace App\Http\Controllers\Rw;
use App\Http\Controllers\Controller;

use App\Models\Kartu_keluarga;
use App\Models\Rukun_tetangga;
use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class WargaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = 'Manajemen Warga';
        $search = $request->input('search');
        $filterJenisKelamin = $request->jenis_kelamin;

        $warga = Warga::with('kartuKeluarga')
            ->leftJoin('kartu_keluarga', 'warga.no_kk', '=', 'kartu_keluarga.no_kk')
            ->when($search, function ($query, $search) {
                $query->where('warga.nama', 'like', '%' . $search . '%')
                    ->orWhere('warga.nik', 'like', '%' . $search . '%')
                    ->orWhere('warga.no_kk', 'like', '%' . $search . '%');
            })->when($filterJenisKelamin, function ($query) use ($filterJenisKelamin) {
                $query->where('warga.jenis_kelamin', $filterJenisKelamin);
            })
            ->orderBy('warga.no_kk')
            ->orderBy('warga.nama')
            ->select('warga.*') // agar paginate tetap berfungsi
            ->paginate(10)
            ->withQueryString();


        $kartu_keluarga = Kartu_keluarga::all();

        return view('rw.warga.warga', compact('warga', 'title', 'kartu_keluarga'));
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
        // validasi input
        // pastikan nik unik, no_kk ada di tabel kartu_keluarga, nama tidak boleh kosong
        $validator = validator::make($request->all(),
            [
                'nik' => 'required|unique:warga,nik|max:16',
                'no_kk' => 'required|exists:kartu_keluarga,no_kk|max:16',
                'nama' => 'required|string|max:255',
                'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
                'tempat_lahir' => 'required|string|max:255',
                'tanggal_lahir' => 'required|date',
                'agama' => 'required|string|max:255',
                'pendidikan' => 'required|string|max:255',
                'pekerjaan' => 'required|string|max:255',
                'status_perkawinan' => 'required|string|max:255',
                'status_hubungan_dalam_keluarga' => 'required|in:kepala keluarga,istri,anak',
                'golongan_darah' => 'required|in:A,B,AB,O',
                'kewarganegaraan' => 'required',
                'nama_ayah' => 'required|string|max:255',
                'nama_ibu' => 'required|string|max:255',
            ],
            [
                'nik.required' => 'NIK harus diisi.',
                'nik.unique' => 'NIK sudah terdaftar.',
                'no_kk.required' => 'Nomor KK harus diisi.',
                'no_kk.exists' => 'Nomor KK tidak ditemukan.',
                'nama.required' => 'Nama harus diisi.',
                'jenis_kelamin.required' => 'Jenis kelamin harus dipilih.',
                'tempat_lahir.required' => 'Tempat lahir harus diisi.',
                'tanggal_lahir.required' => 'Tanggal lahir harus diisi.',
                'agama.required' => 'Agama harus diisi.',
                'pendidikan.required' => 'Pendidikan harus diisi.',
                'pekerjaan.required' => 'Pekerjaan harus diisi.',
                'status_perkawinan.required' => 'Status perkawinan harus diisi.',
                'status_hubungan_dalam_keluarga.required' => 'Status hubungan dalam keluarga harus diisi.',
                'status_hubungan_dalam_keluarga.in' => 'Status hubungan dalam keluarga tidak valid.',
                'nik.max' => 'NIK tidak boleh lebih dari 16 karakter.',
                'no_kk.max' => 'Nomor KK tidak boleh lebih dari 16 karakter.',
                'nama.max' => 'Nama tidak boleh lebih dari 255 karakter.',
                'tempat_lahir.max' => 'Tempat lahir tidak boleh lebih dari 255 karakter.',
                'agama.max' => 'Agama tidak boleh lebih dari 255 karakter.',
                'pendidikan.max' => 'Pendidikan tidak boleh lebih dari 255 karakter.',
                'pekerjaan.max' => 'Pekerjaan tidak boleh lebih dari 255 karakter.',
                'golongan_darah.in' => 'Golongan darah tidak valid.',
                'nama_ayah.required' => 'Nama ayah harus diisi.',
                'nama_ibu.required' => 'Nama ibu harus diisi.',
                'golongan_darah.required' => 'Golongan darah harus diisi.',
                'kewarganegaraan.required' => 'Kewarganegaraan harus diisi.',
            ]
        );

        if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput()
            ->with('showModal', 'tambah');
    }


        // membuat data warga baru
        // pastikan no_kk sudah ada di tabel kartu_keluarga
        Warga::create([
            'nik' => $request->nik,
            'no_kk' => $request->no_kk,
            'nama' => $request->nama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'agama' => $request->agama,
            'pendidikan' => $request->pendidikan,
            'pekerjaan' => $request->pekerjaan,
            'status_perkawinan' => $request->status_perkawinan,
            'status_hubungan_dalam_keluarga' => $request->status_hubungan_dalam_keluarga,
            'golongan_darah' => $request->golongan_darah,
            'kewarganegaraan' => $request->kewarganegaraan,
            'nama_ayah' => $request->nama_ayah,
            'nama_ibu' => $request->nama_ibu,
        ]);
        return redirect()->route('warga.index')->with('success', 'Data Warga Berhasil Ditambahkan');
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



    public function update(Request $request, string $nik)
    {
        // 1. Validasi data
        $validator = Validator::make($request->all(), [
            'nik' => [
                'required',
                'digits:16',
                Rule::unique('warga', 'nik')->ignore($nik, 'nik'), // Abaikan nik yang sedang diedit
            ],
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'agama' => 'required|string|max:50',
            'pendidikan' => 'required|string|max:100',
            'pekerjaan' => 'required|string|max:100',
            'status_perkawinan' => 'required|string|max:50',
            'status_hubungan_dalam_keluarga' => 'required|in:kepala keluarga,istri,anak',
            'golongan_darah' => 'required|in:A,B,AB,O',
            'kewarganegaraan' => 'required',
            'nama_ayah' => 'required|string|max:255',
            'nama_ibu' => 'required|string|max:255',
            

        ], [
            'nik.required' => 'NIK harus diisi.',
            'nik.digits' => 'NIK harus terdiri dari 16 digit.',
            'nik.unique' => 'NIK sudah digunakan.',
            'nama.required' => 'Nama tidak boleh kosong.',
            'jenis_kelamin.required' => 'Jenis kelamin harus dipilih.',
            'tempat_lahir.required' => 'Tempat lahir harus diisi.',
            'tanggal_lahir.required' => 'Tanggal lahir harus diisi.',
            'agama.required' => 'Agama tidak boleh kosong.',
            'pendidikan.required' => 'Pendidikan terakhir harus diisi.',
            'pekerjaan.required' => 'Pekerjaan harus diisi.',
            'status_perkawinan.required' => 'Status perkawinan harus diisi.',
            'status_hubungan_dalam_keluarga.required' => 'Hubungan dengan KK harus dipilih.',
            'status_hubungan_dalam_keluarga.in' => 'Pilih hubungan yang sesuai.',
            'golongan_darah.required' => 'Golongan darah harus dipilih.',
            'golongan_darah.in' => 'Golongan darah harus A, B, AB, atau O.',
            'kewarganegaraan.required' => 'Kewarganegaraan harus dipilih.',
            'nama_ayah.required' => 'Nama ayah harus diisi.',
            'nama_ibu.required' => 'Nama ibu harus diisi.',
        ]);

        // 2. Jika validasi gagal, kembali ke halaman & buka modal edit
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('open_edit_modal', $nik); // kirim agar modal bisa dibuka kembali
        }

        // 3. Cari dan update data
        $warga = Warga::findOrFail($nik);

        $warga->update([
            'nik' => $request->nik,
            'nama' => $request->nama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'agama' => $request->agama,
            'pendidikan' => $request->pendidikan,
            'pekerjaan' => $request->pekerjaan,
            'status_perkawinan' => $request->status_perkawinan,
            'status_hubungan_dalam_keluarga' => $request->status_hubungan_dalam_keluarga,
            'golongan_darah' => $request->golongan_darah,
            'kewarganegaraan' => $request->kewarganegaraan,
            'nama_ayah' => $request->nama_ayah,
            'nama_ibu' => $request->nama_ibu,
        ]);

        // 4. Redirect dengan pesan sukses
        return redirect()->route('warga.index')->with('success', 'Data warga berhasil diperbarui.');
    }




    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $nik)
    {
        //
        $warga = Warga::findOrFail($nik);
        $warga->delete();
        return redirect()->route('warga.index')->with('success', 'Warga berhasil dihapus.');
    }
}
