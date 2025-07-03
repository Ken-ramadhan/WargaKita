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
        'nomor_rw',
        'nama_ketua_rw',
        'masa_jabatan',
    ];


    public function rw(): BelongsTo
    {
        return $this->belongsTo(Kartu_keluarga::class, 'id_rw');
    }
}
