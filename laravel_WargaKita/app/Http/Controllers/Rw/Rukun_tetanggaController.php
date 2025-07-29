<?php

namespace App\Http\Controllers\Rw;
use App\Http\Controllers\Controller;
use App\Models\Kartu_keluarga;
use App\Models\Rukun_tetangga;
use App\Models\User;
use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class Rukun_tetanggaController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $id_rw = Auth::user()->id_rw; // Dapatkan id_rw dari user yang sedang login

        // Mengambil data RT untuk dropdown filter RT (hanya ketua, unik, dan di RW yang sama)
        $rukun_tetangga_filter = Rukun_tetangga::where('jabatan', 'ketua')
            ->where('id_rw', $id_rw) // Filter berdasarkan id_rw user yang login
            ->select('rt')
            ->distinct()
            ->orderBy('rt') // Urutkan untuk tampilan dropdown yang rapi
            ->get();

        // Data untuk dropdown filter jabatan
        // Anda bisa mengambilnya dari database jika jabatannya dinamis,
        // atau definisikan secara statis jika jabatannya tetap.
        $jabatan_filter = [
            'ketua' => 'Ketua',
            'sekretaris' => 'Sekretaris',
            'bendahara' => 'Bendahara',
            // Tambahkan jabatan lain jika ada
        ];

        // Kartu Keluarga saat ini tidak digunakan dalam query utama Rukun_tetangga,
        // tetapi tetap di-pass ke view jika Anda membutuhkannya di sana.
        $kartu_keluarga = Kartu_keluarga::all();

        $title = 'Rukun Tetangga';

        // Query dasar untuk daftar Rukun Tetangga yang akan ditampilkan
        $query = Rukun_tetangga::with('rw')->where('id_rw', $id_rw); // Filter berdasarkan id_rw

        // --- Menerapkan Filter ---

        // Filter berdasarkan RT yang dipilih di dropdown
        if ($request->has('rt') && $request->rt != '') {
            $query->where('rt', $request->rt);
        }

        // Filter berdasarkan JABATAN yang dipilih di dropdown
        if ($request->has('jabatan') && $request->jabatan != '') {
            $query->where('jabatan', $request->jabatan);
        }

        // Filter berdasarkan pencarian (no_kk atau nama)
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('no_kk', 'like', '%' . $searchTerm . '%')
                  ->orWhere('nama', 'like', '%' . $searchTerm . '%');
                // Jika "alamat" adalah kolom di tabel rukun_tetangga, tambahkan di sini:
                // ->orWhere('alamat', 'like', '%' . $searchTerm . '%');
            });
        }

        // Lanjutkan dengan pengurutan dan paginasi setelah filter diterapkan
        $rukun_tetangga = $query->orderBy('rt')
            ->orderBy('jabatan') // Opsional: urutkan juga berdasarkan jabatan
            ->paginate(10)
            ->withQueryString(); // Memastikan parameter filter tetap ada di URL paginasi

        // Menghitung total RT (opsional, tergantung kebutuhan Anda)
        $total_rt_di_rw = Rukun_tetangga::where('id_rw', $id_rw)->count();

        // Pass 'jabatan_filter' ke view
        return view('rw.data-rt.rukun_tetangga', compact('rukun_tetangga', 'title', 'total_rt_di_rw', 'rukun_tetangga_filter', 'kartu_keluarga', 'jabatan_filter'));
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
        try {
            // Dapatkan ID RW dari pengguna yang login.
            // Ini penting untuk validasi dan penugasan data ke RW yang benar.
            $id_rw = Auth::user()->id_rw;
            if (!$id_rw) {
                // Jika ID RW tidak ditemukan, kemungkinan ada masalah dengan data user.
                // Redirect kembali dengan pesan error.
                return redirect()->back()
                    ->with('error', 'ID RW tidak ditemukan di akun yang login. Mohon hubungi administrator.')
                    ->withInput($request->input()) // Mengembalikan input agar tidak hilang di form
                    ->with('showModal', 'rt_tambah') // Menampilkan kembali modal jika validasi gagal
                    ->with('form_type', 'rt_tambah'); // Mengidentifikasi jenis form
            }

            // --- 1. Validasi Data Masukan ---
            // Memastikan semua data yang diterima dari form sesuai dengan aturan yang ditetapkan.
            $request->validate([
                'no_kk' => [
                    'required', // Wajib diisi
                    'string',   // Harus berupa teks
                    'digits:16', // Harus 16 digit
                    // Memastikan no_kk ada di tabel 'kartu_keluarga'
                    Rule::exists('kartu_keluarga', 'no_kk'),
                ],
                'nik' => [
                    'required', // Wajib diisi
                    'string',   // Harus berupa teks
                    'digits:16', // Harus 16 digit
                    // NIK harus unik di tabel 'rukun_tetangga' HANYA untuk id_rw yang sama
                    Rule::unique('rukun_tetangga', 'nik')->where(function ($query) use ($id_rw) {
                        return $query->where('id_rw', $id_rw);
                    }),
                    // Validasi kustom: Memastikan NIK ada di tabel 'warga'
                    // DAN terdaftar sebagai anggota keluarga di Nomor KK yang dipilih.
                    function ($attribute, $value, $fail) use ($request) {
                        $warga = Warga::where('nik', $value)
                                    ->where('no_kk', $request->no_kk)
                                    ->first();
                        if (!$warga) {
                            $fail('NIK tidak ditemukan atau tidak terdaftar sebagai anggota keluarga di Nomor KK yang dipilih. Mohon periksa kembali.');
                        }
                    },
                ],
                'rt' => [
                    'required', // Wajib diisi
                    'string',   // Harus berupa teks

                ],
                'nama' => [
                    'required', // Wajib diisi
                    'string',   // Harus berupa teks
                    'max:255',  // Maksimal 255 karakter
                    // Validasi kustom: Memastikan nama yang diinput sesuai dengan nama warga berdasarkan NIK.
                    function ($attribute, $value, $fail) use ($request) {
                        $warga = Warga::where('nik', $request->nik)->first();
                        // Jika warga ditemukan dan namanya tidak cocok, berikan pesan error dengan nama yang benar.
                        if ($warga && $warga->nama !== $value) {
                            $fail('Nama tidak sesuai dengan NIK yang dimasukkan. Nama yang benar adalah: ' . $warga->nama);
                        } elseif (!$warga) {
                            // Ini adalah kondisi fallback jika NIK tidak ditemukan, meskipun seharusnya sudah dicek oleh validasi NIK sebelumnya.
                            $fail('NIK tidak ditemukan. Pastikan NIK benar.');
                        }
                    },
                ],
                'mulai_menjabat' => 'required|date', // Wajib diisi dan format tanggal
                'akhir_jabatan' => 'required|date|after:mulai_menjabat', // Wajib diisi, format tanggal, dan setelah tanggal mulai menjabat
                'jabatan' => 'required|in:ketua,sekretaris,bendahara', // Wajib diisi dan salah satu dari pilihan yang tersedia

            ], [
                // --- Pesan Kustom untuk Validasi ---
                'no_kk.required' => 'Nomor KK harus diisi.',
                'no_kk.string' => 'Nomor KK harus berupa teks.',
                'no_kk.digits' => 'Nomor KK harus 16 digit.',
                'no_kk.exists' => 'Nomor KK yang dimasukkan tidak terdaftar di database Kartu Keluarga. Mohon periksa kembali.',

                'nik.required' => 'NIK harus diisi.',
                'nik.string' => 'NIK harus berupa teks.',
                'nik.digits' => 'NIK harus 16 digit.',
                'nik.unique' => 'NIK ini sudah terdaftar sebagai pengurus RT lain di RW Anda.',

                'rt.required' => 'Nomor RT harus diisi.',
                'rt.string' => 'Nomor RT harus berupa teks.',
                'rt.unique' => 'Nomor RT ini sudah ada di RW Anda. Mohon masukkan nomor RT yang berbeda.',

                'nama.required' => 'Nama harus diisi.',
                'nama.string' => 'Nama harus berupa teks.',
                'nama.max' => 'Nama tidak boleh lebih dari 255 karakter.',

                'mulai_menjabat.required' => 'Tanggal Mulai Menjabat harus diisi.',
                'mulai_menjabat.date' => 'Format tanggal Mulai Menjabat tidak valid.',
                'akhir_jabatan.required' => 'Tanggal Akhir Jabatan harus diisi.',
                'akhir_jabatan.date' => 'Format tanggal Akhir Jabatan tidak valid.',
                'akhir_jabatan.after' => 'Tanggal Akhir Jabatan harus setelah Tanggal Mulai Menjabat.',
                'jabatan.required' => 'Jabatan harus dipilih.',
                'jabatan.in' => 'Jabatan tidak valid. Pilih antara ketua, sekretaris, atau bendahara.',
            ]);

            // --- 2. Pembuatan Data Rukun Tetangga Baru ---
            // Membuat entri baru di tabel 'rukun_tetangga' dengan data yang divalidasi.
            $rukun_tetangga = Rukun_tetangga::create([
                'no_kk' => $request->no_kk,
                'nik' => $request->nik,
                'rt' => $request->rt,
                'nama' => $request->nama,
                'mulai_menjabat' => $request->mulai_menjabat,
                'akhir_jabatan' => $request->akhir_jabatan,
                'jabatan' => $request->jabatan,
                'id_rw' => $id_rw, // Menghubungkan RT dengan RW yang sedang login
            ]);

            // Ambil ID dari data RT yang baru saja dibuat.
            $id_rt = $rukun_tetangga->id;

            // --- 3. Pembuatan atau Pembaruan Akun Pengguna (User) untuk RT ---
            // Memeriksa apakah user dengan NIK ini sudah ada. Jika ada, update; jika tidak, buat baru.
            $existingUser = User::where('nik', $request->nik)->first();

            if ($existingUser) {
                // Jika user dengan NIK ini sudah ada, update informasinya.
                $existingUser->id_rt = $id_rt;
                $existingUser->id_rw = $id_rw;
                $existingUser->role = 'rt'; // Tetapkan role sebagai 'rt'
                $existingUser->save();
            } else {
                // Jika belum ada, buat user baru untuk pengurus RT.
                User::create([
                    'nik' => $request->nik,
                    'nama' => $request->nama,
                    // Tetapkan password default. Disarankan untuk menggunakan password yang kuat atau sistem reset password.
                    'password' => Hash::make('password'),
                    'id_rt' => $id_rt, // Kaitkan dengan ID RT yang baru dibuat
                    'id_rw' => $id_rw, // Kaitkan dengan ID RW
                    'role' => 'rt', // Tetapkan role sebagai 'rt'
                ]);
            }

            // --- 4. Pengalihan Halaman dan Pesan Sukses ---
            // Setelah berhasil menyimpan data, redirect pengguna ke halaman indeks Rukun Tetangga
            // dengan pesan sukses yang akan ditampilkan.
            return redirect()->route('rukun_tetangga.index')
                ->with('success', 'Data RT dan akun pengguna berhasil ditambahkan.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Jika terjadi error validasi (misalnya, input tidak sesuai aturan).
            // Redirect kembali ke halaman sebelumnya dengan error validasi, input lama,
            // dan instruksi untuk menampilkan modal kembali.
            return redirect()->back()
                ->withErrors($e->errors()) // Mengirimkan semua error validasi
                ->withInput($request->input()) // Mengembalikan input agar pengguna tidak perlu mengetik ulang
                ->with('showModal', 'rt_tambah') // Variabel sesi untuk membuka modal RT
                ->with('form_type', 'rt_tambah'); // Variabel sesi untuk mengidentifikasi form di old()/errors
        } catch (\Exception $e) {
            // Menangani semua jenis error lainnya yang mungkin terjadi (misalnya, masalah database, dll.).
            // Redirect kembali dengan pesan error umum.
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menambahkan data RT: ' . $e->getMessage())
                ->withInput($request->input())
                ->with('showModal', 'rt_tambah')
                ->with('form_type', 'rt_tambah');
        }
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
        // Temukan data Rukun Tetangga berdasarkan ID. Jika tidak ditemukan, akan otomatis 404.
        $rukunTetangga = Rukun_tetangga::findOrFail($id);

        // --- 1. Verifikasi Otorisasi dan ID RW ---
        $userAuth = Auth::user();
        if (!$userAuth || !isset($userAuth->id_rw)) {
            Log::error('Akses tidak sah atau id_rw tidak ditemukan untuk pengguna: ' . ($userAuth ? $userAuth->id : 'N/A'));
            return redirect()->back()->withErrors(['auth_error' => 'Anda tidak memiliki akses atau ID RW tidak ditemukan di akun Anda.'])->withInput();
        }
        $id_rw = $userAuth->id_rw;

        // --- Tambahan: Pastikan RT yang diedit berada di bawah RW yang sedang login ---
        // Ini penting untuk keamanan, agar user RW tidak bisa mengedit RT di RW lain.
        if ($rukunTetangga->id_rw !== $id_rw) {
            Log::warning('Percobaan update RT tidak sah: User ID ' . $userAuth->id . ' mencoba mengedit RT ID ' . $id . ' dari RW lain.');
            return redirect()->back()->withErrors(['authorization_error' => 'Anda tidak diizinkan untuk mengubah data RT ini.'])->withInput();
        }

        try {
            // --- 2. Validasi Data Masukan ---
            // Memastikan semua data yang diterima dari form sesuai dengan aturan yang ditetapkan.
            $request->validate([
                'no_kk' => [
                    'required', // Wajib diisi
                    'string',   // Harus berupa teks
                    'digits:16', // Harus 16 digit
                    // Memastikan no_kk ada di tabel 'kartu_keluarga'
                    Rule::exists('kartu_keluarga', 'no_kk'),
                ],
                'nik' => [
                    'required', // Wajib diisi
                    'string',   // Harus berupa teks
                    'digits:16', // Harus 16 digit
                    // NIK harus unik di tabel 'rukun_tetangga' HANYA untuk id_rw yang sama,
                    // dan MENGECUALIKAN NIK dari RT yang sedang diedit.
                    Rule::unique('rukun_tetangga', 'nik')
                        ->ignore($rukunTetangga->id) // Abaikan NIK dari data RT yang sedang diupdate
                        ->where(function ($query) use ($id_rw) {
                            return $query->where('id_rw', $id_rw);
                        }),
                    // Validasi kustom: Memastikan NIK ada di tabel 'warga'
                    // DAN terdaftar sebagai anggota keluarga di Nomor KK yang dipilih.
                    function ($attribute, $value, $fail) use ($request) {
                        $warga = Warga::where('nik', $value)
                                    ->where('no_kk', $request->no_kk)
                                    ->first();
                        if (!$warga) {
                            $fail('NIK tidak ditemukan atau tidak terdaftar sebagai anggota keluarga di Nomor KK yang dipilih. Mohon periksa kembali.');
                        }
                    },
                ],
                'rt' => [
                    'required', // Wajib diisi
                    'string',   // Harus berupa teks
                    'max:10',   // Maksimal 10 karakter
                ],
                'nama' => [
                    'required', // Wajib diisi
                    'string',   // Harus berupa teks
                    'max:255',  // Maksimal 255 karakter
                    // Validasi kustom: Memastikan nama yang diinput sesuai dengan nama warga berdasarkan NIK.
                    function ($attribute, $value, $fail) use ($request) {
                        $warga = Warga::where('nik', $request->nik)->first();
                        // Jika warga ditemukan dan namanya tidak cocok, berikan pesan error dengan nama yang benar.
                        if ($warga && $warga->nama !== $value) {
                            $fail('Nama tidak sesuai dengan NIK yang dimasukkan. Nama yang benar adalah: ' . $warga->nama);
                        } elseif (!$warga) {
                            // Ini adalah kondisi fallback jika NIK tidak ditemukan, meskipun seharusnya sudah dicek oleh validasi NIK sebelumnya.
                            $fail('NIK tidak ditemukan. Pastikan NIK benar.');
                        }
                    },
                ],
                'mulai_menjabat' => 'required|date', // Wajib diisi dan format tanggal
                'akhir_jabatan' => 'required|date|after_or_equal:mulai_menjabat', // Wajib diisi, format tanggal, dan setelah atau sama dengan tanggal mulai menjabat
                'jabatan' => 'required|in:ketua,sekretaris,bendahara', // Wajib diisi dan salah satu dari pilihan yang tersedia
            ], [
                // --- Pesan Kustom untuk Validasi ---
                'no_kk.required' => 'Nomor KK harus diisi.',
                'no_kk.string' => 'Nomor KK harus berupa teks.',
                'no_kk.digits' => 'Nomor KK harus 16 digit.',
                'no_kk.exists' => 'Nomor KK yang dimasukkan tidak terdaftar di database Kartu Keluarga. Mohon periksa kembali.',

                'nik.required' => 'NIK harus diisi.',
                'nik.string' => 'NIK harus berupa teks.',
                'nik.digits' => 'NIK harus 16 digit.',
                'nik.unique' => 'NIK ini sudah terdaftar sebagai pengurus RT lain di RW Anda.',

                'rt.required' => 'Nomor RT harus diisi.',
                'rt.string' => 'Nomor RT harus berupa teks.',
                'rt.max' => 'Nomor RT tidak boleh lebih dari 10 karakter.',
                'rt.unique' => 'Nomor RT ini sudah ada di RW Anda. Mohon masukkan nomor RT yang berbeda.',

                'nama.required' => 'Nama harus diisi.',
                'nama.string' => 'Nama harus berupa teks.',
                'nama.max' => 'Nama tidak boleh lebih dari 255 karakter.',

                'mulai_menjabat.required' => 'Tanggal Mulai Menjabat harus diisi.',
                'mulai_menjabat.date' => 'Format tanggal Mulai Menjabat tidak valid.',
                'akhir_jabatan.required' => 'Tanggal Akhir Jabatan harus diisi.',
                'akhir_jabatan.date' => 'Format tanggal Akhir Jabatan tidak valid.',
                'akhir_jabatan.after_or_equal' => 'Tanggal Akhir Jabatan harus setelah atau sama dengan Tanggal Mulai Menjabat.',
                'jabatan.required' => 'Jabatan harus dipilih.',
                'jabatan.in' => 'Jabatan tidak valid. Pilih antara ketua, sekretaris, atau bendahara.',
            ]);

            // --- 3. Memperbarui Data Rukun Tetangga ---
            // Memperbarui atribut-atribut model $rukunTetangga dengan data dari request.
            $rukunTetangga->update([
                'no_kk' => $request->no_kk,
                'nik' => $request->nik,
                'rt' => $request->rt,
                'nama' => $request->nama,
                'mulai_menjabat' => $request->mulai_menjabat,
                'akhir_jabatan' => $request->akhir_jabatan,
                'jabatan' => $request->jabatan,
                // id_rw tidak perlu diupdate karena diasumsikan RT tidak berpindah RW
            ]);

            // --- 4. Memperbarui Data User Terkait (Opsional tapi Direkomendasikan) ---
            // Jika NIK atau Nama pengurus RT berubah, update juga di tabel users.
            $user = User::where('id_rt', $rukunTetangga->id)->first();
            if ($user) {
                // Perbarui NIK dan Nama di tabel users jika ada perubahan
                $user->nik = $request->nik;
                $user->nama = $request->nama;
                $user->save();
            } else {
                // Log atau buat user baru jika tidak ditemukan (skenario jarang terjadi jika alur store benar)
                Log::warning('User tidak ditemukan untuk RT ID: ' . $rukunTetangga->id . '. Mungkin perlu dibuat ulang.');
                // Jika ingin membuat ulang user jika tidak ada, bisa tambahkan logic create user di sini
            }

            // --- 5. Pengalihan Halaman dan Pesan Sukses ---
            // Redirect pengguna kembali ke halaman indeks Rukun Tetangga dengan pesan sukses.
            return redirect()->route('rukun_tetangga.index')->with('success', 'Data Rukun Tetangga berhasil diperbarui. 👍');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Menangkap error validasi dan mengembalikan ke halaman sebelumnya dengan pesan error.
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput($request->input())
                // Anda mungkin perlu variabel sesi untuk modal update jika menggunakan modal
                ->with('showModal', 'rt_edit_' . $rukunTetangga->id); // Contoh untuk modal edit spesifik
        } catch (\Exception $e) {
            // Menangkap error umum lainnya dan mengembalikan dengan pesan error.
            Log::error('Gagal memperbarui data RT ID ' . $rukunTetangga->id . ': ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui data RT: ' . $e->getMessage())
                ->withInput($request->input());
        }
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
