<?php

namespace Database\Seeders;

use App\Models\Kartu_keluarga;
use App\Models\Rukun_tetangga;
use App\Models\Rw;
use App\Models\User;
use App\Models\Warga;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
    {
        // Buat RW dulu
        $rw = Rw::create([
            'nik' => '0000000000000001',
            'nomor_rw' => 'Rw01',
            'nama_ketua_rw' => 'Pak RW',
            'mulai_menjabat' => now(),
            'akhir_jabatan' => now()->addYears(3),
        ]);

        // Buat RT yang terhubung ke RW
        $rt = Rukun_tetangga::create([
            'nik' => '0000000000000002',
            'nomor_rt' => 'Rt01',
            'nama_ketua_rt' => 'Pak RT',
            'mulai_menjabat' => now(),
            'akhir_jabatan' => now()->addYears(3),
            'id_rw' => $rw->id,
        ]);

        $kk = Kartu_keluarga::create([
            'no_kk' => '1234567890123456',
            'alamat' => 'Jalan Melati',
            'id_rt' => $rt->id,
            'id_rw' => $rw->id,
            'kelurahan' => 'Kelurahan Mawar',
            'kecamatan' => 'Kecamatan Indah',
            'kabupaten' => 'Kabupaten Damai',
            'provinsi' => 'Provinsi Sejahtera',
            'kode_pos' => '12345',
            'tgl_terbit' => now(),
            'golongan' => 'kampung',
        ]);


        // Buat Warga dummy (kalau tabel `warga` kamu punya data minimal)
        $warga = Warga::create([
            'no_kk' => '1234567890123456', // pastikan KK-nya ada!
            'nik' => '4567890987654321',
            'nama' => 'Andi Kurniawan',
            'jenis_kelamin' => 'Laki-laki',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2000-01-01',
            'agama' => 'Islam',
            'pendidikan' => 'S1',
            'pekerjaan' => 'PNS',
            'status_perkawinan' => 'menikah',
            'status_hubungan_dalam_keluarga' => 'kepala keluarga',
            'golongan_darah' => 'A',
            'kewarganegaraan' => 'WNI',
            'nama_ayah' => 'Budi Hartono',
            'nama_ibu' => 'Siti Kurniawan',
            'jenis' => 'penduduk',
        ]);

        // Admin
        User::create([
            'nik' => '0000000000000001',
            'nama' => 'Admin',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // RW
        User::create([
            'nik' => '  ',
            'nama' => 'Budi Hartono',
            'password' => Hash::make('password'),
            'role' => 'rw',
            'id_rw' => $rw->id,
        ]);

        // RT
        User::create([
            'nik' => '0000000000000002',
            'nama' => 'Rukun Tetangga',
            'password' => Hash::make('password'),
            'role' => 'rt',
            'id_rt' => $rt->id,
            'id_rw' => $rw->id,
        ]);

        // Warga
        User::create([
            'nik' => $warga->nik,
            'nama' => $warga->nama,
            'password' => Hash::make('password'),
            'role' => 'warga',
            'id_rt' => $rt->id,
            'id_rw' => $rw->id,
        ]);
    }
}
