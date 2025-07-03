<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengumuman extends Model
{
    //
    protected $table = 'pengumuman';
    protected $fillable = [
        'judul',
        'kateogri',
        'isi',
        'tanggal',
        'id_rt',
    ];

    public function rukuntetangga(): BelongsTo
    {
        return $this->belongsTo(Rukun_tetangga::class,'id_rt', 'id');
    }
}
