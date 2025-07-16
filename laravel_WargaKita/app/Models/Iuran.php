<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany; // Tambahkan ini jika belum ada

class Iuran extends Model
{
    protected $table = 'iuran'; // Nama tabel yang terkait dengan model ini
    protected $primaryKey = 'id'; // Primary key dari tabel (default 'id')
    public $incrementing = true; // Apakah primary key auto-increment (default true)
    protected $keyType = 'int'; // Tipe data primary key (default 'int')

    protected $fillable = [
        'nama',
        'tgl_tagih',
        'tgl_tempo',
        'jenis',
        'nominal', // Pastikan 'nominal' ada di $fillable jika digunakan untuk jenis manual
    ];

    /**
     * Definisikan relasi ke model Tagihan.
     * Satu Iuran memiliki satu Tagihan.
     */
    public function tagihan()
    {
        return $this->hasMany(Tagihan::class, 'id_iuran'); 
    }

    /**
     * Definisikan relasi ke model Kategori_golongan.
     * Satu Iuran dimiliki oleh satu Kategori_golongan.
     * (Catatan: Berdasarkan konteks sebelumnya, relasi ini mungkin lebih cocok di model IuranGolongan
     * atau perlu dipertimbangkan ulang jika Iuran langsung terkait dengan Golongan).
     */
    public function golongan(): BelongsTo
    {
        // Sesuaikan foreign key dan owner key jika tidak standar
        // Default foreign key: golongan_id
        // Default owner key: id
        return $this->belongsTo(Kategori_golongan::class, 'id_golongan', 'id_golongan');
    }

    /**
     * Definisikan relasi ke model IuranGolongan.
     * Satu Iuran memiliki banyak IuranGolongan.
     * Nama method ini diubah menjadi 'iuran_golongan' (snake_case)
     * agar sesuai dengan panggilan di controller (e.g., Iuran::with('iuran_golongan')).
     */
    public function iuran_golongan(): HasMany // Tambahkan tipe return HasMany
    {
        return $this->hasMany(IuranGolongan::class, 'id_iuran', 'id');
        // Parameter kedua ('id_iuran') adalah foreign key di tabel iuran_golongan
        // Parameter ketiga ('id') adalah local key di tabel iuran (primary key Iuran)
    }
}
