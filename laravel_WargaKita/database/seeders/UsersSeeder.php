<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
            User::create([
                'nik' => '1234567890987654',
                'nama' => 'Budi Hartono',
                'nomor_rw' => 'Rw01',
                'password' => bcrypt('password'),
                'role' => 'rw'
            ]);

            User::create([
                'nik' => '4567890987654321',
                'nama' => 'Andi Kurniawan',
                'password' => bcrypt('password'),
                'role' => 'warga',
            ]);
    }
}
