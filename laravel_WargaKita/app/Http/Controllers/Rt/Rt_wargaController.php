<?php

namespace App\Http\Controllers\Rt;

use App\Http\Controllers\Controller;
use App\Models\Kartu_keluarga;
use App\Models\User;
use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class Rt_wargaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     public function index(Request $request)
    {
        $title = 'Manajemen Warga';
        $search = $request->search;
        // Mengambil nilai jenis_kelamin dari request.
        // Jika tidak ada di URL, atau nilainya kosong (''), akan menjadi null.
        $jenis_kelamin = $request->input('jenis_kelamin');

        // Pastikan user memiliki relasi rukunTetangga
        $userRtData = Auth::user()->rukunTetangga;

        if (!$userRtData) {
            return redirect()->back()->with('error', 'Data Rukun Tetangga Anda tidak ditemukan. Mohon hubungi administrator.');
        }

        $rt_id = $userRtData->id;
        $nomorRtUser = $userRtData->rt;
        $idRwUser = $userRtData->id_rw;

        // --- 1. Query dasar untuk menghitung total warga ---
        // Query ini akan membangun dasar filter warga berdasarkan RT user.
        // Kemudian, filter jenis kelamin akan diterapkan jika ada.
        $totalWargaQuery = Warga::whereHas('kartuKeluarga', function ($query) use ($rt_id) {
            $query->where('id_rt', $rt_id);
        })
        ->whereHas('kartuKeluarga.rukunTetangga', function ($query) use ($nomorRtUser, $idRwUser) {
            $query->where('rt', $nomorRtUser)
                  ->where('id_rw', $idRwUser);
        });

        // Terapkan filter jenis kelamin pada total_warga jika nilai dari dropdown ada dan tidak kosong
        $total_warga = (clone $totalWargaQuery)->when($jenis_kelamin, function ($query) use ($jenis_kelamin) {
            // Kondisi ini hanya ditambahkan jika $jenis_kelamin tidak null, tidak kosong, dan tidak false.
            $query->where('jenis_kelamin', $jenis_kelamin);
        })
        ->count();


        // --- 2. Query utama untuk mengambil data warga yang dipaginasi ---
        // Mulai dengan base query yang sudah difilter berdasarkan RT user
        $warga = $totalWargaQuery
            // Terapkan filter jenis kelamin dari dropdown jika nilai ada
            ->when($jenis_kelamin, function ($query) use ($jenis_kelamin) {
                // Kondisi ini hanya ditambahkan jika $jenis_kelamin tidak null, tidak kosong, dan tidak false.
                $query->where('jenis_kelamin', $jenis_kelamin);
            })
            // Terapkan filter pencarian teks
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', '%' . $search . '%')
                      ->orWhere('nik', 'like', '%' . $search . '%')
                      ->orWhere('no_kk', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('warga.no_kk')
            ->orderBy('warga.nama')
            ->paginate(10)
            ->withQueryString();

        // Mengirim data ke view
        return view('rt.warga.warga', compact('title', 'warga', 'search', 'total_warga', 'jenis_kelamin'));
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
            'status_warga' => 'required|in:penduduk,pendatang',
            'no_paspor' => 'nullable|string|unique:warga,no_paspor',
            'tgl_terbit_paspor' => 'nullable|date',
            'tgl_berakhir_paspor' => 'nullable|date',
            'no_kitas' => 'nullable|string|unique:warga,no_kitas',
            'tgl_terbit_kitas' => 'nullable|date',
            'tgl_berakhir_kitas' => 'nullable|date',
            'no_kitap' => 'nullable|string|unique:warga,no_kitap',
            'tgl_terbit_kitap' => 'nullable|date',
            'tgl_berakhir_kitap' => 'nullable|date',
        ], [
            'nik.unique' => 'NIK sudah terdaftar.',
            'no_kk.exists' => 'Nomor KK tidak ditemukan.',
            'no_paspor.unique' => 'Nomor Paspor sudah terdaftar.',
            'no_kitas.unique' => 'Nomor KITAS sudah terdaftar.',
            'no_kitap.unique' => 'Nomor KITAP sudah terdaftar.',
            'tgl_terbit_paspor.date' => 'Tanggal terbit paspor harus berupa tanggal yang valid.',
            'tgl_berakhir_paspor.date' => 'Tanggal berakhir paspor harus berupa tanggal yang valid.',
            'tgl_terbit_kitas.date' => 'Tanggal terbit KITAS harus berupa tanggal yang valid.',
            'tgl_berakhir_kitas.date' => 'Tanggal berakhir KITAS harus berupa tanggal yang valid.',
            'tgl_terbit_kitap.date' => 'Tanggal terbit KITAP harus berupa tanggal yang valid.',
            'tgl_berakhir_kitap.date' => 'Tanggal berakhir KITAP harus berupa tanggal yang valid.',
            'jenis_kelamin.in' => 'Jenis kelamin harus laki-laki atau perempuan.',
            'status_hubungan_dalam_keluarga.in' => 'Status hubungan dalam keluarga harus kepala keluarga, istri, atau anak.',
            'golongan_darah.in' => 'Golongan darah harus A, B, AB, atau O.',
            'kewarganegaraan.required' => 'Kewarganegaraan harus diisi.',
            'status_warga.in' => 'Status warga harus penduduk atau pendatang.',
            'nama_ayah.required' => 'Nama ayah harus diisi.',
            'nama_ibu.required' => 'Nama ibu harus diisi.',
            'nama.required' => 'Nama harus diisi.',
            'tempat_lahir.required' => 'Tempat lahir harus diisi.',
            'tanggal_lahir.required' => 'Tanggal lahir harus diisi.',
            'agama.required' => 'Agama harus diisi.',
            'pendidikan.required' => 'Pendidikan harus diisi.',
            'pekerjaan.required' => 'Pekerjaan harus diisi.',
            'status_perkawinan.required' => 'Status perkawinan harus diisi.',
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
            'status_warga' => $request->status_warga,
            'no_paspor' => $request->no_paspor,
            'tgl_terbit_paspor' => $request->tgl_terbit_paspor,
            'tgl_berakhir_paspor' => $request->tgl_berakhir_paspor,
            'no_kitas' => $request->no_kitas,
            'tgl_terbit_kitas' => $request->tgl_terbit_kitas,
            'tgl_berakhir_kitas' => $request->tgl_berakhir_kitas,
            'no_kitap' => $request->no_kitap,
            'tgl_terbit_kitap' => $request->tgl_terbit_kitap,
            'tgl_berakhir_kitap' => $request->tgl_berakhir_kitap,
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $warga = Warga::findOrFail($id);
        return view('rt_warga.edit', compact('warga'));
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
            'status_warga' => 'required|in:penduduk,pendatang',
            'no_paspor' => [
                'nullable',
                'string',
                Rule::unique('warga', 'no_paspor')->ignore($nik, 'nik'), // Mengabaikan berdasarkan NIK record yang SEDANG diupdate
            ],
            'no_kitas' => [
                'nullable',
                'string',
                Rule::unique('warga', 'no_kitas')->ignore($nik, 'nik'),
            ],
            'no_kitap' => [
                'nullable',
                'string',
                Rule::unique('warga', 'no_kitap')->ignore($nik, 'nik'),
            ],
            'tgl_terbit_paspor' => 'nullable|date',
            'tgl_berakhir_paspor' => 'nullable|date',
            'tgl_terbit_kitas' => 'nullable|date',
            'tgl_berakhir_kitas' => 'nullable|date',
            'tgl_terbit_kitap' => 'nullable|date',
            'tgl_berakhir_kitap' => 'nullable|date',
        ], [
            'nik.unique' => 'NIK sudah terdaftar.',
            'no_kk.exists' => 'Nomor KK tidak ditemukan.',
            'no_paspor.unique' => 'Nomor Paspor sudah terdaftar.',
            'no_kitas.unique' => 'Nomor KITAS sudah terdaftar.',
            'no_kitap.unique' => 'Nomor KITAP sudah terdaftar.',
            'tgl_terbit_paspor.date' => 'Tanggal terbit paspor harus berupa tanggal yang valid.',
            'tgl_berakhir_paspor.date' => 'Tanggal berakhir paspor harus berupa tanggal yang valid.',
            'tgl_terbit_kitas.date' => 'Tanggal terbit KITAS harus berupa tanggal yang valid.',
            'tgl_berakhir_kitas.date' => 'Tanggal berakhir KITAS harus berupa tanggal yang valid.',
            'tgl_berakhir_kitas.date' => 'Tanggal berakhir KITAS harus berupa tanggal yang valid.',
            'tgl_terbit_kitap.date' => 'Tanggal terbit KITAP harus berupa tanggal yang valid.',
            'tgl_berakhir_kitap.date' => 'Tanggal berakhir KITAP harus berupa tanggal yang valid.',
            'jenis_kelamin.in' => 'Jenis kelamin harus laki-laki atau perempuan.',
            'status_hubungan_dalam_keluarga.in' => 'Status hubungan dalam keluarga harus kepala keluarga, istri, atau anak.',
            'golongan_darah.in' => 'Golongan darah harus A, B, AB, atau O.',
            'kewarganegaraan.required' => 'Kewarganegaraan harus diisi.',
            'status_warga.in' => 'Status warga harus penduduk atau pendatang.',
            'nama_ayah.required' => 'Nama ayah harus diisi.',
            'nama_ibu.required' => 'Nama ibu harus diisi.',
            'nama.required' => 'Nama harus diisi.',
            'tempat_lahir.required' => 'Tempat lahir harus diisi.',
            'tanggal_lahir.required' => 'Tanggal lahir harus diisi.',
            'agama.required' => 'Agama harus diisi.',
            'pendidikan.required' => 'Pendidikan harus diisi.',
            'pekerjaan.required' => 'Pekerjaan harus diisi.',
            'status_perkawinan.required' => 'Status perkawinan harus diisi.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('open_edit_modal', $nik);
        }

        // Temukan catatan Warga yang ada menggunakan NIK asli
        $warga = Warga::findOrFail($nik);
        $kk = Kartu_keluarga::where('no_kk', $request->no_kk)->firstOrFail();

        // Simpan NIK lama sebelum memperbarui catatan warga
        $oldNik = $warga->nik;

        if ($request->status_hubungan_dalam_keluarga === 'kepala keluarga') {
            $existingKepala = Warga::where('no_kk', $request->no_kk)
                ->where('nik', '!=', $request->nik) // Periksa terhadap NIK BARU
                ->where('status_hubungan_dalam_keluarga', 'kepala keluarga')
                ->exists();

            if ($existingKepala) {
                return redirect()->back()
                    ->withErrors(['status_hubungan_dalam_keluarga' => 'Nomor KK ini sudah memiliki Kepala Keluarga.'])
                    ->withInput()
                    ->with('open_edit_modal', $nik);
            }

            // Jika NIK berubah, hapus dulu catatan pengguna lama
            if ($oldNik !== $request->nik) {
                User::where('nik', $oldNik)->delete();
            }

            User::updateOrCreate(
                ['nik' => $request->nik], // Gunakan NIK yang berpotensi baru
                [
                    'nama' => $request->nama,
                    'password' => bcrypt('123456'),
                    'id_rt' => $kk->id_rt,
                    'id_rw' => $kk->id_rw,
                    'role' => 'warga',
                ]
            );
        } else {
            // Jika status bukan lagi 'kepala keluarga', hapus catatan pengguna
            User::where('nik', $oldNik)->delete(); // Gunakan NIK lama untuk memastikan pengguna yang benar dihapus
        }

        // Perbarui catatan Warga dengan data baru
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
            'status_warga' => $request->status_warga,
            'no_paspor' => $request->no_paspor,
            'tgl_terbit_paspor' => $request->tgl_terbit_paspor,
            'tgl_berakhir_paspor' => $request->tgl_berakhir_paspor,
            'no_kitas' => $request->no_kitas,
            'tgl_terbit_kitas' => $request->tgl_terbit_kitas,
            'tgl_berakhir_kitas' => $request->tgl_berakhir_kitas,
            'no_kitap' => $request->no_kitap,
            'tgl_terbit_kitap' => $request->tgl_terbit_kitap,
            'tgl_berakhir_kitap' => $request->tgl_berakhir_kitap,
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
        //
        $warga = Warga::findOrFail($nik);
        $warga->delete();
        User::where('nik', $nik)->delete(); // TANPA FK harus manual
        return redirect()->to($request->redirect_to)->with('success', 'Warga berhasil dihapus.');
    }

}
