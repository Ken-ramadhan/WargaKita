<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi'; // Ubah dari 'pengeluaran'

    protected $fillable = [
        'rt',
        'tanggal',
        'pemasukan',
        'pengeluaran',
        'nama_transaksi',
        'jumlah',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'decimal:2',
        // pemasukan dan pengeluaran tetap sebagai string
    ];
}
