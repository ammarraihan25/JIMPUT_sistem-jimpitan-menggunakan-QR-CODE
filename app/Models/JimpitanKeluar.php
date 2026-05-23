<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JimpitanKeluar extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nominal',
        'keterangan',
        'tanggal',
    ];

    protected $casts = [
        'nominal' => 'integer',
        'tanggal' => 'date',
        'created_at' => 'datetime',
    ];

    /**
     * Relasi: JimpitanKeluar dicatat oleh satu User (Petugas/Admin)
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
