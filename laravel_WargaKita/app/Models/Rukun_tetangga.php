<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rukun_tetangga extends Model
{
    //
    protected $table = 'rukun_tetangga';
    protected $fillable = [
        'nik',
        'nomor_rt',
        'nama_ketua_rt',
        'mulai_menjabat',
        'akhir_jabatan',
        'id_rw',
    ];

    public function warga(): HasMany
    {
        return $this->hasMany(Warga::class, 'id_rt');
    }

    public function pengumuman()
    {
        return $this->hasMany(Pengumuman::class, 'id_rt');
    }

    public function pengeluaran(): HasMany
    {
        return $this->hasMany(Pengeluaran::class, 'id_rt');
    }

    public function kartu_keluarga(): HasMany
    {
        return $this->hasMany(Kartu_keluarga::class, 'id_rt');
    }
    public function rw()
    {
        return $this->belongsTo(Rw::class, 'id_rw', 'id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id_rt', 'id_rt');
    }

}
