<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipeKamar extends Model
{
    use HasFactory;

    protected $table = 'tipe_kamar';

    protected $fillable = ['nama_tipe', 'deskripsi', 'harga_per_malam', 'kapasitas', 'fasilitas'];

    protected $casts = [
        'fasilitas'       => 'array',
        'harga_per_malam' => 'decimal:2',
    ];

    // --- Relasi ---
    public function kamar()
    {
        return $this->hasMany(Kamar::class, 'tipe_kamar_id');
    }

    // --- Accessor ---
    public function getHargaFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->harga_per_malam, 0, ',', '.');
    }
}
