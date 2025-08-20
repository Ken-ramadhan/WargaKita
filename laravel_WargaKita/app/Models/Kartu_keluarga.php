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
    'no_kk',
    'no_registrasi', // Jika ada nomor registrasi internal selain No. KK 16 digit
    'alamat',
    'id_rt',
    'id_rw',
    'kelurahan',
    'kecamatan',
    'kabupaten',
    'provinsi',
    'kode_pos',
    'tgl_terbit',
    'kategori_iuran',
    'instansi_penerbit',         // Contoh: 'Dinas Kependudukan dan Pencatatan Sipil'
    'kabupaten_kota_penerbit',   // Contoh: 'Kota Bandung'
    'nama_kepala_dukcapil',      // Nama lengkap Kepala Dinas Dukcapil
    'nip_kepala_dukcapil',       // NIP Kepala Dinas Dukcapil
    'foto_kk',                   // Path atau URL ke foto Kartu Keluarga
];
    

    public function rukunTetangga(): BelongsTo
    {
        return $this->belongsTo(Rukun_tetangga::class, 'id_rt', 'id');
    }
    
    public function tagihan(): HasMany
    {
        return $this->hasMany(Tagihan::class, 'no_kk', 'no_kk');
    }

    public function warga(): HasMany
    {
        return $this->hasMany(Warga::class, 'no_kk', 'no_kk');
    }
    public function rw(): BelongsTo
    {
        return $this->belongsTo(Rw::class, 'id_rw', 'id');
    }

}
