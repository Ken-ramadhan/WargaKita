<?php

namespace App\Http\Controllers\Rw;

use App\Http\Controllers\Controller;
use App\Models\Rukun_tetangga;
use App\Models\Transaksi; // Pastikan model Transaksi diimpor
use App\Models\RukunTetangga; // Pastikan model RukunTetangga diimpor (gunakan casing ini)
use App\Models\Tagihan; // Pastikan model Tagihan diimpor
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema; // Untuk mengecek keberadaan kolom di database

class TransaksiController extends Controller
{
    /**
     * Menampilkan daftar transaksi dengan filter.
     */
    public function index(Request $request)
    {
        $title = 'Data Transaksi Keuangan';

        $query = Transaksi::query();

        // Logika Filter dan Pencarian
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama_transaksi', 'like', '%' . $search . '%')
                  ->orWhere('rt', 'like', '%' . $search . '%')
                  ->orWhere('keterangan', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal', $request->input('tahun'));
        }

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal', $request->input('bulan'));
        }

        if ($request->filled('rt')) {
            $query->where('rt', $request->input('rt'));
        }

        $transaksi = (clone $query)->orderBy('tanggal', 'desc')->get();
        $paginatedTransaksi = $query->orderBy('tanggal', 'desc')->paginate(10);

        $daftar_tahun = Transaksi::selectRaw('YEAR(tanggal) as tahun')
                                ->distinct()
                                ->orderBy('tahun', 'desc')
                                ->pluck('tahun');
        $daftar_bulan = range(1, 12);

        // Mengambil daftar RT dari tabel rukun_tetangga untuk dropdown di form
        $rukun_tetangga = Rukun_tetangga::orderBy('nomor_rt', 'asc')->pluck('nomor_rt', 'nomor_rt');

        // Mengambil total pemasukan yang belum tercatat (untuk ditampilkan di view)
        // Ini akan memberi tahu user berapa nominal yang akan otomatis masuk
        $totalPemasukanBelumTercatat = $this->getJumlahPemasukanDariTagihanTerbayar(false); // false = jangan tandai tercatat dulu

        return view('rw.iuran.transaksi', compact(
            'title',
            'paginatedTransaksi',
            'transaksi',
            'daftar_tahun',
            'daftar_bulan',
            'rukun_tetangga',
            'totalPemasukanBelumTercatat' // Kirimkan ini ke view
        ));
    }

    /**
     * Fungsi untuk mendapatkan jumlah total nominal dari tagihan yang sudah dibayar
     * dan belum tercatat dalam transaksi.
     * @param bool $markAsRecorded Jika true, tagihan akan ditandai sebagai 'tercatat_transaksi'.
     * @return float Total nominal yang sudah dibayar dan belum tercatat.
     */
    private function getJumlahPemasukanDariTagihanTerbayar(bool $markAsRecorded = false): float
    {
        // Ambil semua tagihan yang statusnya 'sudah_bayar'
        $query = Tagihan::where('status_bayar', 'sudah_bayar');

        // Filter hanya yang belum tercatat di transaksi jika kolom 'tercatat_transaksi' ada
        if (Schema::hasColumn('tagihan', 'tercatat_transaksi')) {
             $query->where('tercatat_transaksi', false);
        } else {
            // Jika kolom tidak ada, log peringatan. Ini akan menyebabkan double-counting
            // Anda HARUS menambahkan kolom ini di database!
            Log::warning('Kolom "tercatat_transaksi" tidak ditemukan di tabel "tagihan". Pemasukan mungkin akan dihitung ganda.');
        }

        $tagihanTerbayar = $query->get();
        $totalNominal = 0;

        foreach ($tagihanTerbayar as $tagihan) {
            $totalNominal += $tagihan->nominal;

            // Jika $markAsRecorded true dan kolom 'tercatat_transaksi' ada, tandai tagihan ini sudah tercatat
            if ($markAsRecorded && Schema::hasColumn('tagihan', 'tercatat_transaksi')) {
                $tagihan->tercatat_transaksi = true;
                $tagihan->save();
            }
        }

        return $totalNominal;
    }


    /**
     * Menyimpan data transaksi baru.
     */
    public function store(Request $request)
    {
        // LOG 1: Mencatat semua input yang diterima dari form
        Log::info('Input diterima untuk TransaksiController@store:', $request->all());

        // Validasi input
        $validator = Validator::make($request->all(), [
            'rt' => 'required|string|max:10|exists:rukun_tetangga,nomor_rt',
            'tanggal' => 'required|date',
            'nama_transaksi' => 'required|string|max:255',
            // Pemasukan tidak lagi divalidasi dari input karena akan dihitung otomatis
            'pengeluaran' => 'nullable|numeric|min:0', // Pengeluaran masih diinput manual
            'keterangan' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            // LOG 2: Mencatat jika validasi gagal
            Log::error('Validasi Gagal Saat Menambah Transaksi:', [
                'errors' => $validator->errors()->all(),
                'input' => $request->all()
            ]);
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput()
                        ->with('modal_type', 'add'); // Menandai bahwa kesalahan dari modal tambah
        }

        try {
            $data = $validator->validated();

            // Ambil total pemasukan dari tagihan yang sudah dibayar dan belum tercatat
            // DAN tandai tagihan tersebut sebagai sudah tercatat
            $pemasukanOtomatis = $this->getJumlahPemasukanDariTagihanTerbayar(true); // true = tandai sudah tercatat

            // Set nilai pemasukan di data transaksi
            $data['pemasukan'] = $pemasukanOtomatis;

            // Ambil nilai pengeluaran, default ke 0 jika null
            $pengeluaran = $data['pengeluaran'] ?? 0;

            // Hitung 'jumlah' (saldo akhir) = pemasukan otomatis - pengeluaran manual
            $data['jumlah'] = $pemasukanOtomatis - $pengeluaran;

            // ====================================================================
            // LOGIKA BARU: Menghitung saldo_berjalan
            // ====================================================================
            // Ambil saldo_berjalan terakhir dari transaksi sebelumnya
            $lastTransaksi = Transaksi::orderBy('tanggal', 'desc')
                                    ->orderBy('id', 'desc') // Tambahkan orderBy id untuk konsistensi jika tanggal sama
                                    ->first();
            $lastSaldoBerjalan = $lastTransaksi ? $lastTransaksi->saldo_berjalan : 0;

            // Hitung saldo_berjalan untuk transaksi saat ini
            $data['saldo_berjalan'] = $lastSaldoBerjalan + $data['jumlah'];


            // Validasi tambahan: jika pemasukan otomatis 0 dan pengeluaran 0, berikan error
            if ($pemasukanOtomatis == 0 && $pengeluaran == 0) {
                 return redirect()->back()
                        ->withInput()
                        ->with('error', 'Tidak ada pemasukan dari tagihan terbayar dan pengeluaran kosong. Transaksi tidak dapat dibuat.')
                        ->with('modal_type', 'add');
            }

            // LOG 3: Mencatat data final yang akan disimpan
            Log::info('Data final yang akan disimpan:', $data);

            Transaksi::create($data); // Gunakan $data yang sudah dimodifikasi

            // LOG 4: Mencatat jika proses penyimpanan berhasil
            Log::info('Transaksi berhasil ditambahkan.');
            return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil ditambahkan.');
        } catch (\Exception $e) {
            // LOG 5: Mencatat jika terjadi error saat menyimpan ke database
            Log::error('Gagal menambahkan transaksi:', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'input_saat_error' => $request->all() // Input yang menyebabkan error
            ]);
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat menambahkan transaksi.');
        }
    }

    /**
     * Memperbarui data transaksi.
     * Untuk update, pemasukan tetap diinput manual, karena transaksi ini sudah ada
     * dan mungkin sudah mencatat pemasukan tertentu yang tidak terkait dengan tagihan baru.
     * Logika otomatisasi hanya berlaku untuk pembuatan transaksi baru.
     */
    public function update(Request $request, $id)
    {
        $transaksi = Transaksi::findOrFail($id);

        // LOG 1 (UPDATE): Mencatat input yang diterima sebelum validasi
        Log::info('Input diterima untuk TransaksiController@update (ID: ' . $id . '):', $request->all());

        // Validasi input
        $validator = Validator::make($request->all(), [
            'rt' => 'required|string|max:10|exists:rukun_tetangga,nomor_rt',
            'tanggal' => 'required|date',
            'nama_transaksi' => 'required|string|max:255',
            'pemasukan' => 'nullable|numeric|min:0', // Pemasukan di update bisa diinput manual
            'pengeluaran' => 'nullable|numeric|min:0',
            'keterangan' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            // LOG 2 (UPDATE): Mencatat jika validasi gagal saat update
            Log::error('Validasi Gagal Saat Memperbarui Transaksi (ID: ' . $id . '):', [
                'errors' => $validator->errors()->all(),
                'input' => $request->all()
            ]);
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput()
                        ->with('modal_type', 'edit')
                        ->with('edit_item_id', $id);
        }

        try {
            $data = $validator->validated();

            // Ambil nilai pemasukan dan pengeluaran, default ke 0 jika null
            $pemasukan = $data['pemasukan'] ?? 0;
            $pengeluaran = $data['pengeluaran'] ?? 0;

            // Hitung 'jumlah'
            $data['jumlah'] = $pemasukan - $pengeluaran;

            // ====================================================================
            // LOGIKA BARU: Menghitung saldo_berjalan untuk baris yang diupdate
            // ====================================================================
            // Ambil transaksi sebelumnya untuk mendapatkan saldo_berjalan terakhir
            $previousTransaksi = Transaksi::where('tanggal', '<=', $transaksi->tanggal)
                                        ->where('id', '<', $transaksi->id) // Pastikan ambil yang ID-nya lebih kecil jika tanggal sama
                                        ->orderBy('tanggal', 'desc')
                                        ->orderBy('id', 'desc')
                                        ->first();
            $lastSaldoBerjalanBeforeThis = $previousTransaksi ? $previousTransaksi->saldo_berjalan : 0;

            // Hitung saldo_berjalan baru untuk transaksi yang sedang diupdate
            $data['saldo_berjalan'] = $lastSaldoBerjalanBeforeThis + $data['jumlah'];


            // Validasi tambahan: jika pemasukan dan pengeluaran 0, berikan error
            if ($pemasukan == 0 && $pengeluaran == 0) {
                 return redirect()->back()
                        ->withInput()
                        ->with('error', 'Pemasukan atau Pengeluaran harus diisi salah satu.')
                        ->with('modal_type', 'edit')
                        ->with('edit_item_id', $id);
            }

            // LOG 3 (UPDATE): Mencatat data final yang akan diperbarui
            Log::info('Data final yang akan diperbarui (ID: ' . $id . '):', $data);

            $transaksi->update($data);

            // PENTING: Setelah update, Anda mungkin perlu memicu rekalkulasi saldo_berjalan
            // untuk semua transaksi setelahnya jika akurasi adalah prioritas utama.
            // Ini bisa dilakukan dengan memanggil fungsi rekalkulasi terpisah di sini.
            // Contoh (jika Anda memiliki fungsi rekalkulasi):
            // $this->recalculateSaldoBerjalanFrom($transaksi->tanggal, $transaksi->id);

            // LOG 4 (UPDATE): Mencatat jika proses update berhasil
            Log::info('Transaksi berhasil diperbarui (ID: ' . $id . ').');
            return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil diperbarui.');
        } catch (\Exception $e) {
            // LOG 5 (UPDATE): Mencatat jika terjadi error saat update database
            Log::error('Gagal memperbarui transaksi (ID: ' . $id . '):', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'input_saat_error' => $request->all()
            ]);
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui transaksi.');
        }
    }

    /**
     * Menghapus data transaksi.
     * PENTING: Menghapus transaksi akan membuat saldo_berjalan di transaksi-transaksi setelahnya menjadi tidak akurat.
     * Anda perlu fungsi rekalkulasi ledger terpisah jika ini terjadi.
     */
    public function destroy($id)
    {
        try {
            $transaksi = Transaksi::findOrFail($id);
            $transaksi->delete();

            // PENTING: Setelah penghapusan, Anda mungkin perlu memicu rekalkulasi saldo_berjalan
            // untuk semua transaksi setelahnya jika akurasi adalah prioritas utama.
            // Contoh (jika Anda memiliki fungsi rekalkulasi):
            // $this->recalculateSaldoBerjalanFrom($transaksi->tanggal, $transaksi->id);

            Log::info('Transaksi berhasil dihapus (ID: ' . $id . ').');
            return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Gagal menghapus transaksi (ID: ' . $id . '):', ['message' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus transaksi.');
        }
    }

    // ====================================================================
    // FUNGSI OPSIONAL: Untuk rekalkulasi saldo_berjalan secara massal
    // Gunakan ini jika Anda sering mengedit/menghapus transaksi lama
    // ====================================================================
    // private function recalculateSaldoBerjalanFrom($startDate, $startId = 0)
    // {
    //     // Ambil semua transaksi dari tanggal tertentu (dan ID tertentu jika tanggal sama)
    //     // yang perlu direkalkulasi
    //     $transactionsToRecalculate = Transaksi::where('tanggal', '>=', $startDate)
    //                                         ->when($startId > 0, function ($query) use ($startDate, $startId) {
    //                                             $query->where(function ($q) use ($startDate, $startId) {
    //                                                 $q->where('tanggal', '>', $startDate)
    //                                                   ->orWhere(function ($subQ) use ($startDate, $startId) {
    //                                                       $subQ->where('tanggal', $startDate)
    //                                                            ->where('id', '>=', $startId);
    //                                                   });
    //                                             });
    //                                         })
    //                                         ->orderBy('tanggal', 'asc')
    //                                         ->orderBy('id', 'asc')
    //                                         ->get();

    //     // Ambil saldo_berjalan terakhir sebelum titik mulai rekalkulasi
    //     $lastTransaksiBeforeRecalc = Transaksi::where('tanggal', '<', $startDate)
    //                                         ->orderBy('tanggal', 'desc')
    //                                         ->orderBy('id', 'desc')
    //                                         ->first();
    //     $currentSaldo = $lastTransaksiBeforeRecalc ? $lastTransaksiBeforeRecalc->saldo_berjalan : 0;

    //     foreach ($transactionsToRecalculate as $transaksi) {
    //         $currentSaldo += $transaksi->jumlah;
    //         $transaksi->saldo_berjalan = $currentSaldo;
    //         $transaksi->save();
    //     }
    // }
}
