<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tagihan extends Model
{
    use HasFactory;

    protected $table = 'tagihan';

    protected $fillable = [
        'nama',
        'tgl_tagih',
        'tgl_tempo',
        'jenis',
        'nominal',
        'no_kk',
        'status_bayar',
        'tgl_bayar',
        'id_iuran',
        'kategori_pembayaran', // Ditambahkan ke $fillable
        'bukti_transfer',      // Ditambahkan ke $fillable
    ];

    /**
     * Relasi ke KartuKeluarga
     */
    public function kartuKeluarga(): BelongsTo
    {
        return $this->belongsTo(Kartu_keluarga::class, 'no_kk', 'no_kk');
    }

    /**
     * Relasi ke Iuran (untuk jenis iuran yang terkait dengan tagihan ini)
     */
    public function iuran()
    {
        return $this->belongsTo(Iuran::class, 'id_iuran'); // Asumsi primary key model Iuran adalah 'id'
    }
}
