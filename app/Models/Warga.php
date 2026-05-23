<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Warga extends Model
{
    use HasFactory;

    protected $fillable = [
        'qr_token',
        'nama_warga',
        'no_rumah',
        'rt_rw',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    /**
     * Auto-generate unique QR token when creating
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($warga) {
            if (empty($warga->qr_token)) {
                $warga->qr_token = 'token-warga-' . Str::random(12) . '-' . time();
            }
        });
    }

    /**
     * Relasi: Warga punya banyak JimpitanMasuk
     */
    public function jimpitanMasuks()
    {
        return $this->hasMany(JimpitanMasuk::class, 'warga_id');
    }

    /**
     * Total nominal jimpitan yang diterima
     */
    public function getTotalJimpitanAttribute()
    {
        return $this->jimpitanMasuks()->sum('nominal');
    }

    /**
     * Jimpitan bulan ini
     */
    public function jimpitanBulanIni()
    {
        return $this->jimpitanMasuks()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year);
    }
}
