<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Kategori_golongan extends Model
{
    //
    protected $table = 'kategori_golongan';
    protected $fillable = [
        'nama',
        
    ];

   public static function getEnumNama()
    {
        return ['kampung', 'kavling', 'kost', 'kantor', 'kontrakan', 'umkm'];
    }

    public function iuranGolongan()
    {
        return $this->hasMany(IuranGolongan::class, 'id_golongan');
    }
    public function keluarga()
    {
        return $this->hasMany(Kartu_keluarga::class, 'id_golongan');
    }
}
