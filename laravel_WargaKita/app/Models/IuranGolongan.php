<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IuranGolongan extends Model
{
    protected $table = 'iuran_golongan';
    protected $fillable = [
        'id_iuran',
        'id_golongan',
        'nominal',
    ];

    public function iuran()
    {
        return $this->belongsTo(Iuran::class, 'id_iuran');
    }

    public function golongan()
    {
        return $this->belongsTo(Kategori_golongan::class, 'id_golongan');
    }
}

