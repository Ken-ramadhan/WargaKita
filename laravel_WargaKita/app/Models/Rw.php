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
        'mulai_menjabat',
        'akhir_jabatan',
        'id_rw'
    ];


    public function kartu_keluarga()
    {
        return $this->hasMany(Kartu_keluarga::class, 'id_rw');
    }

    public function user()
    {
        return $this->hasMany(User::class, 'id_rw', 'id');
    }

    public function pengumuman()
    {
        return $this->hasMany(Pengumuman::class, 'id_rw', 'id');
    }

     public function rukunTetangga()
    {
        return $this->hasMany(Rukun_tetangga::class, 'id_rw', 'id');
    }
}
