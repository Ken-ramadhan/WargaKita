<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    use HasFactory;

    // Menentukan nama tabel yang terkait dengan model ini
    protected $table = 'pengeluaran';

    // Mendefinisikan kolom-kolom yang bisa diisi secara massal (mass assignable)
    protected $fillable = [
        'rt',
        'tanggal',
        'pemasukan', // Sekarang ini adalah kolom string
        'pengeluaran', // Sekarang ini adalah kolom string
        'nama_transaksi',
        'jumlah',
        'keterangan',
    ];

    // Opsional: Casting atribut ke tipe data tertentu
    protected $casts = [
        'tanggal' => 'date',     // Mengubah 'tanggal' menjadi objek Carbon
        'jumlah' => 'decimal:2', // Mengubah 'jumlah' menjadi tipe desimal dengan 2 angka di belakang koma
        // Tidak meng-cast 'pemasukan' dan 'pengeluaran' karena mereka string di migrasi
    ];
}