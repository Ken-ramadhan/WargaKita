<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori_golongan extends Model
{
    //
    protected $table = 'kategori_golongan';
    protected $fillable = [
        'nama',
        'keterangan',
    ];
}
