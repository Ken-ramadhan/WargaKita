<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Kartu_keluarga extends Model
{
    //
    protected $table = 'kartu_keluarga';
    protected $primaryKey = 'no_kk';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'id_rt',
        'kepala_kk',
    ];

    

    public function rukunTetangga(): BelongsTo
    {
        return $this->belongsTo(Rukun_tetangga::class, 'id_rt', 'id_rt');
    }

    public function tagihan(): HasMany
    {
        return $this->hasMany(Tagihan::class);
    }
}
