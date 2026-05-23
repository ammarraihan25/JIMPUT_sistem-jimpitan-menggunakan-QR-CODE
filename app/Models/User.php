<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
    ];

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

    /**
     * Cek apakah user adalah admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Cek apakah user adalah petugas
     */
    public function isPetugas(): bool
    {
        return $this->role === 'petugas';
    }

    /**
     * Relasi: User punya banyak JimpitanMasuk (sebagai petugas)
     */
    public function jimpitanMasuks()
    {
        return $this->hasMany(JimpitanMasuk::class, 'user_id');
    }

    /**
     * Relasi: User punya banyak JimpitanKeluar (sebagai pencatat)
     */
    public function jimpitanKeluars()
    {
        return $this->hasMany(JimpitanKeluar::class, 'user_id');
    }


    /**
     * Total jimpitan yang dicatat petugas ini
     */
    public function getTotalCatatanAttribute(): int
    {
        return $this->jimpitanMasuks()->count();
    }
}
