<?php

namespace App\Http\Controllers\Rw;

use App\Http\Controllers\Controller;
use App\Models\Kartu_keluarga;
use App\Models\Rukun_tetangga;
use App\Models\User;
use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            })
            ->when($filterJenisKelamin, function ($query) use ($filterJenisKelamin) {
                $query->where('warga.jenis_kelamin', $filterJenisKelamin);
            })
            ->orderBy('warga.no_kk')
            ->orderBy('warga.nama')
            ->select('warga.*')
            ->paginate(10)
            ->withQueryString();
        
         $total_warga = Warga::count();

        $rukun_tetangga = Rukun_tetangga::all();
        $kartu_keluarga = Kartu_keluarga::all();

        return view('rw.warga.warga', compact('warga', 'title', 'kartu_keluarga', 'rukun_tetangga','total_warga'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nik' => 'required|unique:warga,nik|max:16',
            'no_kk' => 'required|exists:kartu_keluarga,no_kk|max:16',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
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
            'jenis' => 'required|in:penduduk,pendatang',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('showModal', 'tambah');
        }

        $kk = Kartu_keluarga::where('no_kk', $request->no_kk)->firstOrFail();

        // Cek apakah KK ini sudah memiliki kepala keluarga
        if ($request->status_hubungan_dalam_keluarga === 'kepala keluarga') {
            $existingKepala = Warga::where('no_kk', $request->no_kk)
                ->where('status_hubungan_dalam_keluarga', 'kepala keluarga')
                ->exists();

            if ($existingKepala) {
                return redirect()->back()
                    ->withErrors(['status_hubungan_dalam_keluarga' => 'Nomor KK ini sudah memiliki Kepala Keluarga.'])
                    ->withInput()
                    ->with('showModal', 'tambah');
            }
        }

        // Buat warga baru
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
            'jenis' => $request->jenis,
            'id_rt' => $kk->id_rt,
            'id_rw' => $kk->id_rw,
        ]);

        // Buat user hanya jika status kepala keluarga
        if ($request->status_hubungan_dalam_keluarga === 'kepala keluarga') {
            User::create([
                'nik' => $request->nik,
                'nama' => $request->nama,
                'password' => bcrypt('123456'),
                'id_rt' => $kk->id_rt,
                'id_rw' => $kk->id_rw,
                'role' => 'warga',
            ]);
        }

        return redirect()->to($request->redirect_to)->with('success', 'Data Warga Berhasil Ditambahkan');
    }

    /**
     * Show the specified resource.
     */
    public function show(string $id)
    {
        $warga = Warga::findOrFail($id);
        return view('warga.show', compact('warga'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $warga = Warga::findOrFail($id);
        return view('warga.edit', compact('warga'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $nik)
    {
        $validator = Validator::make($request->all(), [
            'nik' => [
                'required',
                'digits:16',
                Rule::unique('warga', 'nik')->ignore($nik, 'nik'),
            ],
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
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
            'jenis' => 'required|in:penduduk,pendatang',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('open_edit_modal', $nik);
        }

        $warga = Warga::findOrFail($nik);
        $kk = Kartu_keluarga::where('no_kk', $request->no_kk)->firstOrFail();

        if ($request->status_hubungan_dalam_keluarga === 'kepala keluarga') {
            $existingKepala = Warga::where('no_kk', $request->no_kk)
                ->where('nik', '!=', $nik)
                ->where('status_hubungan_dalam_keluarga', 'kepala keluarga')
                ->exists();

            if ($existingKepala) {
                return redirect()->back()
                    ->withErrors(['status_hubungan_dalam_keluarga' => 'Nomor KK ini sudah memiliki Kepala Keluarga.'])
                    ->withInput()
                    ->with('open_edit_modal', $nik);
            }

            User::updateOrCreate(
                ['nik' => $request->nik],
                [
                    'nama' => $request->nama,
                    'password' => bcrypt('123456'),
                    'id_rt' => $kk->id_rt,
                    'id_rw' => $kk->id_rw,
                    'role' => 'warga',
                ]
            );
        } else {
            User::where('nik', $nik)->delete();
        }

        $warga->update([
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
            'jenis' => $request->jenis,
            'id_rt' => $kk->id_rt,
            'id_rw' => $kk->id_rw,
        ]);

        return redirect()->to($request->redirect_to)->with('success', 'Data warga berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request,string $nik)
    {
        $warga = Warga::findOrFail($nik);
        $warga->delete();
        User::where('nik', $nik)->delete();

        return redirect()->to($request->redirect_to)->with('success', 'Warga berhasil dihapus.');
    }

}
