<?php

namespace App\Console\Commands;

use App\Models\Iuran;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateTagihanIuran extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-tagihan-iuran';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
{
    $today = now()->toDateString();

    $iurans = Iuran::where('jenis', 'otomatis')->whereDate('tgl_tagih', $today)->get();

    foreach ($iurans as $iuran) {
        $golonganNominal = DB::table('iuran_nominal')
            ->where('iuran_id', $iuran->id)
            ->pluck('nominal', 'kategori_golongan_id');

        $keluargaList = DB::table('kartu_keluarga')->get();

        foreach ($keluargaList as $keluarga) {
            $nominal = $golonganNominal[$keluarga->id_golongan] ?? 0;

            // Cek apakah tagihan sudah ada untuk iuran ini & no_kk
            $existing = DB::table('tagihan')
                ->where('id_iuran', $iuran->id)
                ->where('no_kk', $keluarga->no_kk)
                ->exists();

            if (!$existing) {
                DB::table('tagihan')->insert([
                    'no_kk' => $keluarga->no_kk,
                    'id_iuran' => $iuran->id,
                    'status' => 'belum lunas',
                    'tgl_bayar' => null,
                    'kategori_pembayaran' => null,
                    'bukti_pembayaran' => null,
                    'nominal' => $nominal,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    $this->info('Tagihan otomatis berhasil dibuat.');
}

}
