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
        // 1. Buat RW terlebih dahulu (tidak ada ketergantungan)
        $rw = Rw::create([
            'nik' => '0000000000000001',
            'nomor_rw' => '01',
            'nama_ketua_rw' => 'Pak RW',
            'mulai_menjabat' => now(),
            'akhir_jabatan' => now()->addYears(3),
        ]);

        // 2. Buat Kartu Keluarga (tanpa id_rt dulu)
        // Kita tidak bisa langsung mengisi id_rt karena $rt belum ada.
        // Kita akan update id_rt ini setelah $rt dibuat.
        $kk_ketua_rt = Kartu_keluarga::create([
            'no_kk' => '1234567890123456',
            'no_registrasi' => '3404.0325983',
            'alamat' => 'Jalan Melati',
            'id_rw' => $rw->id, // id_rw sudah ada
            'kelurahan' => 'Kelurahan Mawar',
            'kecamatan' => 'Kecamatan Indah',
            'kabupaten' => 'Kabupaten Damai',
            'provinsi' => 'Provinsi Sejahtera',
            'kode_pos' => '12345',
            'tgl_terbit' => now(),
            'kategori_iuran' => 'kampung',
            'instansi_penerbit' => 'Dinas Kependudukan dan Pencatatan Sipil',
            'kabupaten_kota_penerbit' => 'Kota Bandung',
            'nama_kepala_dukcapil' => 'Budi Santoso S.Kom',
            'nip_kepala_dukcapil' => '123456789012345678',
        ]);

        // 3. Buat RT (sekarang kita bisa mereferensikan KK Ketua RT)
        $rt = Rukun_tetangga::create([
            'no_kk' => $kk_ketua_rt->no_kk, // no_kk dari KK Ketua RT
            'nik' => '0000000000000002',
            'rt' => '01',
            'nama' => 'Andi Kurniawan', // Menggunakan nama kepala dukcapil sebagai nama RT
            'mulai_menjabat' => now(),
            'akhir_jabatan' => now()->addYears(3),
            'jabatan' => 'ketua',
            'id_rw' => $rw->id, // id_rw sudah ada
        ]);

        // 4. Update Kartu Keluarga yang dibuat sebelumnya dengan id_rt yang baru dibuat
        $kk_ketua_rt->update([
            'id_rt' => $rt->id,
        ]);

        // Buat Warga dummy
        // Gunakan no_kk dari KK yang sudah dibuat
        $warga = Warga::create([
            'no_kk' => $kk_ketua_rt->no_kk,
            'nik' => '4567890987654321',
            'nama' => 'Andi Kurniawan',
            'jenis_kelamin' => 'laki-laki',
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
            'status_warga' => 'penduduk',
            // id_rt dan id_rw tidak disimpan di warga secara langsung,
            // melainkan melalui no_kk yang terhubung ke KK, lalu KK ke RT/RW
        ]);

        // Buat User
        User::create([
            'nik' => '0000000000000001',
            'nama' => 'Admin',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'nik' => '1234567890123452', // Ini NIK dummy untuk user RW, pastikan unik dari NIK RW sebenarnya jika berbeda
            'nama' => 'User RW Test', // Nama yang lebih generik untuk user
            'password' => Hash::make('password'),
            'role' => 'rw',
            'id_rw' => $rw->id,
        ]);

        User::create([
            'nik' => $rt->nik, // Gunakan NIK dari RT yang sudah dibuat
            'nama' => $rt->nama,
            'password' => Hash::make('password'),
            'role' => 'rt',
            'id_rt' => $rt->id,
            'id_rw' => $rw->id,
        ]);

        User::create([
            'nik' => $warga->nik, // Gunakan NIK dari warga yang sudah dibuat
            'nama' => $warga->nama,
            'password' => Hash::make('password'),
            'role' => 'warga',
            'id_rt' => $rt->id, // Mengambil id_rt dari objek $rt
            'id_rw' => $rw->id, // Mengambil id_rw dari objek $rw
        ]);
    }
}
