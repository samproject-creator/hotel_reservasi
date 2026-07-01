<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tamu extends Model
{
    use HasFactory;

    protected $table = 'tamu';

    protected $fillable = [
        'nama_lengkap', 'nik', 'email', 'no_hp',
        'alamat', 'jenis_kelamin', 'tanggal_lahir',
        'pekerjaan', 'kewarganegaraan',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    // --- Relasi ---
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // --- Accessor ---
    public function getJenisKelaminLabelAttribute(): string
    {
        return $this->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
    }

    public function getUmurAttribute(): int
    {
        return $this->tanggal_lahir ? $this->tanggal_lahir->age : 0;
    }
}
