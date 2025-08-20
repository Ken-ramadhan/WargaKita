<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Warga extends Model
{
    protected $table = 'warga';
    protected $primaryKey = 'nik';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nik',
        'no_kk',
        'nama',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'pendidikan',
        'pekerjaan',
        'status_perkawinan',
        'status_hubungan_dalam_keluarga',
        'golongan_darah',
        'kewarganegaraan',
        'nama_ayah',
        'nama_ibu',
        'status_warga',
        'no_paspor',
        'tgl_terbit_paspor',
        'tgl_berakhir_paspor',
        'no_kitas',
        'tgl_terbit_kitas',
        'tgl_berakhir_kitas',
        'no_kitap',
        'tgl_terbit_kitap',
        'tgl_berakhir_kitap',
    ];

    public function kartuKeluarga(): BelongsTo
    {
        return $this->belongsTo(Kartu_keluarga::class, 'no_kk', 'no_kk');
    }

    public function user(): BelongsTo
{
    return $this->belongsTo(User::class, 'nik', 'nik');
}


    
}
