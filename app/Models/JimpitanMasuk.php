<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JimpitanMasuk extends Model
{
    use HasFactory;

    protected $fillable = [
        'warga_id',
        'user_id',
        'nominal',
        'keterangan',
    ];

    protected $casts = [
        'nominal' => 'integer',
        'created_at' => 'datetime',
    ];

    /**
     * Relasi: JimpitanMasuk milik satu Warga
     */
    public function warga()
    {
        return $this->belongsTo(Warga::class, 'warga_id');
    }

    /**
     * Relasi: JimpitanMasuk dicatat oleh satu User (Petugas)
     */
    public function petugas()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Format nominal ke Rupiah
     */
    public function getNominalFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->nominal, 0, ',', '.');
    }
}
