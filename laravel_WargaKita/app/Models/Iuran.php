<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Iuran extends Model
{
    protected $table = 'iuran';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nama',
        'tgl_tagih',
        'tgl_tempo',
        'jenis',
        'nominal', // hanya untuk iuran manual
    ];

    /**
     * Relasi ke Tagihan
     */
    public function tagihan(): HasMany
    {
        return $this->hasMany(Tagihan::class, 'id_iuran');
    }
    /**
     * jadi jika iuran dihapus, semua tagihan terkait juga akan dihapus.
     */
    protected static function booted()
    {
    static::deleting(function ($iuran) {
        $iuran->tagihan()->delete();
    });
    }

    /**
     * Relasi ke IuranGolongan (saat jenis = otomatis)
     */
    public function iuran_golongan(): HasMany
    {
        return $this->hasMany(IuranGolongan::class, 'id_iuran');
    }

    // 🚫 HAPUS atau NONAKTIFKAN ini jika tidak pakai tabel kategori_golongan:
    // public function golonganKategori() {
    //     return $this->hasManyThrough(...);
    // }

}
