<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tagihan extends Model
{
    //
    protected $table = 'tagihan';
    protected $fillable = [
        'no_kk',
        'status',
        'tgl_bayar',
        'id_iuran',
        'kategori_pembayaran',
        'bukti_transfer',
    ];

    public function kartuKeluarga(): BelongsTo
    {
        return $this->belongsTo(Kartu_keluarga::class, 'no_kk', 'no_kk');
    }

    public function iuran(): BelongsTo
    {
        return $this->belongsTo(Iuran::class, 'id_iuran', 'id_iuran');
    }
}
