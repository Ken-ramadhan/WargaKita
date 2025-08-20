<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail; // Dihapus karena tidak digunakan
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Pastikan ini ada jika menggunakan type-hinting BelongsTo
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'nik',
        'password',
        'nama',
        'nomor_rw',
        'id_rw',
        'role',
        'id_rt', // Ini adalah ID dari tabel rukun_tetangga
    ];

    public function warga(): BelongsTo
    {
        return $this->belongsTo(Warga::class, 'nik', 'nik');
    }

    public function rukunTetangga(): BelongsTo
    {
        // Relasi ini sudah benar, id_rt di users merujuk ke id di rukun_tetangga
        return $this->belongsTo(Rukun_tetangga::class, 'id_rt', 'id');
    }

    public function rw(): BelongsTo
    {
        return $this->belongsTo(Rw::class, 'id_rw', 'id');
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}