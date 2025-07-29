<?php

namespace App\Http\Controllers\Rw; // Perhatikan namespace ini
use App\Http\Controllers\Controller;

use App\Models\Kartu_keluarga; // Pastikan ini menunjuk ke model yang benar
use App\Models\Kategori_golongan;
use App\Models\Rukun_tetangga;
use App\Models\Warga; // Warga tampaknya tidak digunakan di sini, bisa dihapus jika tidak perlu
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class Kartu_keluargaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     public function index(Request $request)
    {
        $search = $request->search;
        $filterRtNomor = $request->rt; // Mengganti $filterRt menjadi $filterRtNomor agar lebih jelas

        $userRwId = Auth::user()->id_rw; // Asumsi user RW memiliki id_rw

        // Pastikan relasi di model Kartu_keluarga sudah benar (rukunTetangga, warga, dll)
        $kartu_keluarga_query = Kartu_keluarga::with('rukunTetangga', 'warga')
            ->whereHas('rukunTetangga', function ($q) use ($userRwId) {
                // Filter KK hanya yang berada di RW user yang login
                $q->where('id_rw', $userRwId);
            })
            ->when($search, function ($query) use ($search) {
                $query->where('no_kk', 'like', '%' . $search . '%')
                      ->orWhere('alamat', 'like', '%' . $search . '%')
                      // Tambahkan pencarian berdasarkan nama kepala keluarga
                      ->orWhereHas('warga', function($q) use ($search) {
                          $q->where('nama', 'like', '%' . $search . '%')
                            ->where('status_hubungan_dalam_keluarga', 'Kepala Keluarga');
                      });
            })
            ->when($filterRtNomor, function ($query) use ($filterRtNomor, $userRwId) {
                // Filter berdasarkan nomor RT yang dipilih DAN di RW yang sama
                $query->whereHas('rukunTetangga', function ($q) use ($filterRtNomor, $userRwId) {
                    $q->where('rt', $filterRtNomor) // Filter berdasarkan nomor RT (kolom 'rt')
                      ->where('id_rw', $userRwId); // Pastikan juga di RW yang sama
                });
            });

        $total_kk = (clone $kartu_keluarga_query)->count(); // Hitung total setelah filter awal RW

        $kartu_keluarga = $kartu_keluarga_query->paginate(5)->withQueryString();

        // Ambil daftar nomor RT unik di RW user yang login untuk dropdown filter
        // Ambil nomor RT, bukan ID RT, untuk dropdown
        $rukun_tetangga = Rukun_tetangga::where('id_rw', $userRwId)
            ->select('rt') // Hanya ambil kolom 'rt'
            ->distinct() // Ambil nilai unik
            ->orderBy('rt')
            ->get();

        // ✅ PENTING: Ambil daftar RT LENGKAP (ID dan Nomor RT) untuk DROPDOWN EDIT dan TAMBAH
    $all_rukun_tetangga = Rukun_tetangga::where('id_rw', $userRwId)
                                                ->orderBy('rt')
                                                ->get(['id', 'rt']); // Ambil ID dan nomor RT

        $kategori_iuran = Kategori_golongan::getEnumNama();
        $warga = Warga::all(); // Periksa apakah ini benar-benar digunakan di view. Jika tidak, hapus saja.
        $title = 'Kartu Keluarga';

        return view('rw.kartu-keluarga.kartu_keluarga', compact(
            'kartu_keluarga',
            'all_rukun_tetangga', // Pastikan ini digunakan di view untuk dropdown edit
            'rukun_tetangga', // Menggunakan nama variabel yang lebih deskriptif
            'kategori_iuran',
            'title',
            'total_kk',
            'warga',
            'filterRtNomor' // Kirim kembali nilai filter yang aktif untuk pre-select di dropdown
        ));
    }


    /**
     * Show the form for creating a new resource.
     * Metode ini kosong karena form penambahan KK utama tidak menampilkan foto.
     * Ini mungkin akan menampilkan modal atau redirect ke halaman utama (index)
     * di mana form tambah berada.
     */
    public function create()
    {
        // Biasanya akan return view('rw.kartu-keluarga.create_kk_modal_or_page');
        // Tetapi karena Anda mengimplementasikan form di index via modal,
        // metode ini mungkin tidak langsung dipanggil.
        // Jika form create ada di modal pada halaman index, tidak perlu view terpisah di sini.
        // Jika memang ada form create terpisah (full page), maka Anda perlu:
        // $rws = Rw::where('id', Auth::user()->id_rw)->get(); // Hanya RW user yang login
        // $rts = Rukun_tetangga::where('id_rw', Auth::user()->id_rw)->get();
        // return view('rw.kartu-keluarga.create', compact('rws', 'rts'));
    }

    /**
     * Store a newly created resource in storage.
     * Bagian ini sudah bagus untuk menyimpan data KK tanpa foto.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'no_registrasi' => 'nullable|unique:kartu_keluarga,no_registrasi|max:50',
                'no_kk' => 'required|unique:kartu_keluarga,no_kk|size:16',
                'alamat' => 'required|string|max:255',
                'id_rt' => 'nullable|exists:rukun_tetangga,id',
                'kelurahan' => 'required|string|max:100',
                'kecamatan' => 'required|string|max:100',
                'kabupaten' => 'required|string|max:100',
                'provinsi' => 'required|string|max:100',
                'kode_pos' => 'required|string|max:10',
                'tgl_terbit' => 'required|date',
                'kategori_iuran' => ['required', Rule::in(Kategori_golongan::getEnumNama())],
                'instansi_penerbit' => 'required|string|max:100',
                'kabupaten_kota_penerbit' => 'required|string|max:100',
                'nama_kepala_dukcapil' => 'required|string|max:100',
                'nip_kepala_dukcapil' => 'required|string|max:20',
            ], [
                'no_kk.unique' => 'Nomor Kartu Keluarga sudah terdaftar.',
                'no_kk.size' => 'Nomor Kartu Keluarga tidak valid.',
                'no_registrasi.unique' => 'Nomor Registrasi sudah terdaftar.',
                'no_registrasi.max' => 'Nomor Registrasi tidak boleh lebih dari 50 karakter.',
                'alamat.required' => 'Alamat harus diisi.',
                'alamat.max' => 'Alamat tidak boleh lebih dari 255 karakter.',
                'id_rt.exists' => 'RT yang dipilih tidak valid.',
                'kelurahan.required' => 'Kelurahan harus diisi.',
                'kelurahan.max' => 'Kelurahan tidak boleh lebih dari 100 karakter.',
                'kecamatan.required' => 'Kecamatan harus diisi.',
                'kecamatan.max' => 'Kecamatan tidak boleh lebih dari 100 karakter.',
                'kabupaten.required' => 'Kabupaten harus diisi.',
                'kabupaten.max' => 'Kabupaten tidak boleh lebih dari 100 karakter.',
                'provinsi.required' => 'Provinsi harus diisi.',
                'provinsi.max' => 'Provinsi tidak boleh lebih dari 100 karakter.',
                'kode_pos.required' => 'Kode Pos harus diisi.',
                'kode_pos.max' => 'Kode Pos tidak boleh lebih dari 10 karakter.',
                'tgl_terbit.required' => 'Tanggal Terbit harus diisi.',
                'tgl_terbit.date' => 'Tanggal Terbit tidak valid.',
                'kategori_iuran.required' => 'Kategori Iuran harus dipilih.',
                'instansi_penerbit.required' => 'Instansi Penerbit harus diisi.',
                'instansi_penerbit.max' => 'Instansi Penerbit tidak boleh lebih dari 100 karakter.',
                'kabupaten_kota_penerbit.required' => 'Kabupaten Kota Penerbit harus diisi.',
                'kabupaten_kota_penerbit.max' => 'Kabupaten Kota Penerbit tidak boleh lebih dari 100 karakter.',
                'nama_kepala_dukcapil.required' => 'Nama Kepala Dukcapil harus diisi.',
                'nama_kepala_dukcapil.max' => 'Nama Kepala Dukcapil tidak boleh lebih dari 100 karakter.',
                'nip_kepala_dukcapil.required' => 'NIP Kepala Dukcapil harus diisi.',
                'nip_kepala_dukcapil.max' => 'NIP Kepala Dukcapil tidak boleh lebih dari 20 karakter.',
            ]);

            $kartu_keluarga = Kartu_keluarga::create([
                'no_kk' => $request->no_kk,
                'alamat' => $request->alamat,
                'id_rt' => $request->id_rt ?: null,
                'id_rw' => Auth::user()->id_rw,
                'kelurahan' => $request->kelurahan,
                'kecamatan' => $request->kecamatan,
                'kabupaten' => $request->kabupaten,
                'provinsi' => $request->provinsi,
                'kode_pos' => $request->kode_pos,
                'tgl_terbit' => $request->tgl_terbit,
                'kategori_iuran' => $request->kategori_iuran,
                'instansi_penerbit' => $request->instansi_penerbit,
                'kabupaten_kota_penerbit' => $request->kabupaten_kota_penerbit,
                'nama_kepala_dukcapil' => $request->nama_kepala_dukcapil,
                'nip_kepala_dukcapil' => $request->nip_kepala_dukcapil,
                'no_registrasi' => $request->no_registrasi,
                'foto_kk_path' => null, // Pastikan ini null saat pertama kali dibuat
            ]);

            return redirect()->route('kartu_keluarga.index')
                ->with('success', 'Data Kartu Keluarga berhasil disimpan. Sekarang, silakan unggah foto Kartu Keluarga.');
        }
         catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput($request->input())
                ->with('showModal', 'kk_tambah')
                ->with('form_type', 'kk_tambah');
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Pastikan eager loading relasi jika Anda menampilkannya di view
        $kartu_keluarga = Kartu_keluarga::with('rukunTetangga', 'rw')->findOrFail($id);
        // Pastikan view path benar
        return view('rw.kartu-keluarga.show', compact('kartu_keluarga'));
    }

    /**
     * Show the form for editing the specified resource.
     * Metode ini juga bisa digunakan untuk mengedit data teks KK dan foto jika Anda mau.
     * Jika Anda hanya ingin mengedit foto di halaman terpisah, maka hapus input foto dari view ini.
     */
    public function edit(string $id)
    {
        $kartu_keluarga = Kartu_keluarga::findOrFail($id);
        // Load juga RT dan RW untuk dropdown di form edit
        $rukun_tetangga = Rukun_tetangga::where('id_rw', Auth::user()->id_rw)->select('id', 'nama_rt')->get();
        $kategori_iuran = Kategori_golongan::getEnumNama();
        // Pastikan view path benar
        return view('rw.kartu-keluarga.edit', compact('kartu_keluarga', 'rukun_tetangga', 'kategori_iuran'));
    }

    /**
     * Update the specified resource in storage.
     * Fokus metode ini hanya pada pembaruan data teks KK, tanpa foto.
     * Jika Anda memutuskan untuk menghapus fitur upload foto di `edit` view,
     * maka tidak perlu ada logika penanganan foto di sini.
     */
    public function update(Request $request, string $no_kk)
    {
        try {
            // 1. Validasi input dari request
            $request->validate([
                'no_kk' => [
                    'required',
                    'digits:16',
                    Rule::unique('kartu_keluarga', 'no_kk')->ignore($no_kk, 'no_kk'),
                ],
                'no_registrasi' => [
                    'nullable',
                    'string',
                    'max:50',
                    Rule::unique('kartu_keluarga', 'no_registrasi')->ignore($no_kk, 'no_kk'), // Abaikan no_registrasi yang saat ini diupdate
                ],
                'alamat' => 'required|string|max:255',
                'id_rt' => 'nullable|exists:rukun_tetangga,id',
                'kelurahan' => 'required|string|max:100',
                'kecamatan' => 'required|string|max:100',
                'kabupaten' => 'required|string|max:100',
                'provinsi' => 'required|string|max:100',
                'kode_pos' => 'required|string|max:10',
                'tgl_terbit' => 'required|date',
                'kategori_iuran' => ['required', Rule::in(Kategori_golongan::getEnumNama())],
                'instansi_penerbit' => 'required|string|max:100',
                'kabupaten_kota_penerbit' => 'required|string|max:100',
                'nama_kepala_dukcapil' => 'required|string|max:100',
                'nip_kepala_dukcapil' => 'required|string|max:20',
                // HAPUS VALIDASI 'foto_kk' DI SINI JIKA DIEDIT TERPISAH
            ], [
                // Pesan error validasi (sudah ada)
            ]);

            $kartu_keluarga = Kartu_keluarga::where('no_kk', $no_kk)->firstOrFail();

            $kartu_keluarga->update([
                'no_kk' => $request->no_kk,
                'alamat' => $request->alamat,
                'id_rt' => $request->id_rt ?: null,
                'id_rw' => Auth::user()->id_rw,
                'kelurahan' => $request->kelurahan,
                'kecamatan' => $request->kecamatan,
                'kabupaten' => $request->kabupaten,
                'provinsi' => $request->provinsi,
                'kode_pos' => $request->kode_pos,
                'tgl_terbit' => $request->tgl_terbit,
                'kategori_iuran' => $request->kategori_iuran,
                'instansi_penerbit' => $request->instansi_penerbit,
                'kabupaten_kota_penerbit' => $request->kabupaten_kota_penerbit,
                'nama_kepala_dukcapil' => $request->nama_kepala_dukcapil,
                'nip_kepala_dukcapil' => $request->nip_kepala_dukcapil,
                'no_registrasi' => $request->no_registrasi,
                // 'foto_kk_path' tidak diubah di sini
            ]);

            return redirect()->route('kartu_keluarga.index')
                ->with('success', 'Data kartu keluarga berhasil diperbarui.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput($request->input());
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
{
    $kartu_keluarga = Kartu_keluarga::with('warga')->findOrFail($id);

    // Hapus semua warga yang terkait dengan KK ini
    foreach ($kartu_keluarga->warga as $warga) {
        $warga->delete();
    }

    // Hapus file KK dari storage jika ada
    if ($kartu_keluarga->foto_kk) {
        Storage::delete('public/kartu_keluarga/' . $kartu_keluarga->foto_kk);
    }

    // Hapus data KK
    $kartu_keluarga->delete();

    return redirect()->back()->with('success', 'KK dan semua anggota keluarganya berhasil dihapus.');
}


    /**
     * Show the form for uploading KK photo for an existing resource.
     */
    public function uploadFoto(Request $request, Kartu_keluarga $kartuKeluarga)
    {
        $request->validate([
            'kk_file' => 'required|file|mimes:jpeg,png,jpg,pdf|max:5120', // Validasi file: gambar atau PDF, maks 5MB
        ]);

        $file = $request->file('kk_file'); // Gunakan 'kk_file' sesuai nama input
        $originalExtension = $file->getClientOriginalExtension();
        $fileName = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $originalExtension;

        // Hapus dokumen lama jika ada
        if ($kartuKeluarga->foto_kk) {
            // Pastikan path yang dihapus sesuai dengan yang disimpan di DB
            Storage::disk('public')->delete($kartuKeluarga->foto_kk);
        }

        // Simpan di disk 'public' di dalam folder 'kartu_keluarga'
        $path = $file->storeAs('kartu_keluarga', $fileName, 'public');

        // Simpan path relatif ke database (contoh: 'kartu_keluarga/nama_file_anda.ext')
        $kartuKeluarga->foto_kk = $path;
        $kartuKeluarga->save();

        return redirect()->back()->with('success', 'Dokumen Kartu Keluarga berhasil diunggah!');
    }

    /**
     * Delete the KK photo/document for an existing resource.
     */
    public function deleteFoto(Kartu_keluarga $kartuKeluarga)
    {
        if ($kartuKeluarga->foto_kk) {
            Storage::disk('public')->delete($kartuKeluarga->foto_kk);
            $kartuKeluarga->foto_kk = null; // Setel kolom ke null setelah dihapus
            $kartuKeluarga->save();
            return redirect()->back()->with('success', 'Dokumen Kartu Keluarga berhasil dihapus!');
        }
        return redirect()->back()->with('error', 'Tidak ada dokumen untuk dihapus.');
    }
}
