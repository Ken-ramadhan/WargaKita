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
        'nomor_rt',
        'nama_ketua_rt',
        'masa_jabatan',
        'alamat_rt,'
    ];

    public function warga(): HasMany
    {
        return $this->hasMany(Warga::class);
    }

    public function pengumuman(): BelongsToMany
    {
        return $this->belongsToMany(Pengumuman::class);
    }

    public function pengeluaran(): HasMany
    {
        return $this->hasMany(Pengeluaran::class, 'id_rt');
    }

    public function kartu_keluarga(): HasMany
    {
        return $this->hasMany(Kartu_keluarga::class, 'id_rt');
    }

    

}
