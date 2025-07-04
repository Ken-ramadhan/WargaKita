<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens,HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
  protected $table = 'users';
    protected $fillable = [
        'nik',
        'password',
        'nama',
        'nomor_rw',
        'role',
        'id_rt',
    ];

    public function warga()
{
    return $this->belongsTo(Warga::class,'nik','nik');
}

 public function rukunTetangga()
{
    return $this->belongsTo(Rukun_tetangga::class,'id_rt','id_rt');
}

// public function rw()
// {
//     return $this->belongsTo(Rw::class,'id_rw','id_rw');
// }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

}
