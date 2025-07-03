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
        'kategori',
        'isi',
        'tanggal',
    ];
}
