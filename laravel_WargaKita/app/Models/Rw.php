<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rw extends Model
{
    //
    protected $table = 'rw';
    protected $fillable = 
    [
        'nik',
        'nomor_rw',
        'nama_ketua_rw',
        'masa_jabatan',
    ];


    public function kartu_keluarga(): BelongsTo
    {
        return $this->belongsTo(Kartu_keluarga::class, 'id_rw');
    }
}
