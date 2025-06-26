<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    //
    protected $table = 'pengeluaran';
    protected $fillable = [
        'id_rt',
        'nama_pengeluaran',
        'jumlah',
        'tanggal',
        'keterangan',
    ];

    public function rukunTetangga()
    {
        return $this->belongsTo(Rukun_tetangga::class, 'id_rt');
    }
}
