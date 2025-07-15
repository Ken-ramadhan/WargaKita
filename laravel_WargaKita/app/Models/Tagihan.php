<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tagihan extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika bukan 'tagihans' (plural dari Tagihan)
    protected $table = 'tagihan'; // PASTIKAN NAMA TABEL DI DATABASE ANDA BENAR

    protected $fillable = [
        'nama',
        'tgl_tagih',
        'tgl_tempo',
        'jenis', // Pastikan kolom ini ada di tabel 'tagihan'
        'nominal',
        'no_kk',
        'status_bayar',        // Menggunakan 'status_bayar' sesuai diskusi sebelumnya
        'tgl_bayar',           // Kolom dari model Anda
        'id_iuran',            // Kolom dari model Anda
        // Kolom 'kategori_pembayaran' dan 'bukti_transfer' tidak ada di $fillable Anda
    ];

    /**
     * Relasi ke KartuKeluarga
     */
    public function kartuKeluarga(): BelongsTo
    {
        // Menggunakan Kartu_keluarga::class sesuai dengan model yang Anda berikan
        return $this->belongsTo(Kartu_keluarga::class, 'no_kk', 'no_kk');
    }

    /**
     * Relasi ke Iuran (untuk jenis iuran yang terkait dengan tagihan ini)
     */
    public function iuran(): BelongsTo
    {
        // Menggunakan Iuran::class
        return $this->belongsTo(Iuran::class, 'id_iuran', 'id'); // Asumsi primary key model Iuran adalah 'id'
    }
}
