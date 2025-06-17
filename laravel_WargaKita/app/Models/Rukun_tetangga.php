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
    ];

    public function warga(): HasMany
    {
        return $this->hasMany(Warga::class);
    }

    public function pengumuman(): BelongsToMany
    {
        return $this->belongsToMany(Pengumuman::class);
    }

}
