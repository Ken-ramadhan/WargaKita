<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Iuran extends Model
{
    //
    protected $table = 'iuran';
    protected $fillable = [
        'nama',
        'tgl_tagih',
        'tgl_tempo',
        'jenis',
        'nominal',
    ];


    public function tagihan():HasOne
    { 
        return $this->hasOne(Tagihan::class);
    }

    public function golongan(): BelongsTo
    {
        return $this->belongsTo(Kategori_golongan::class, 'id_golongan', 'id_golongan');
    }

    public function iuranGolongan()
{
    return $this->hasMany(IuranGolongan::class, 'id_iuran');
}
}
