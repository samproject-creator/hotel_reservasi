<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kamar extends Model
{
    use HasFactory;

    protected $table = 'kamar';

    protected $fillable = ['nomor_kamar', 'tipe_kamar_id', 'lantai', 'status', 'keterangan', 'images'];

    protected $casts = [
        'images' => 'array',
    ];
    
    // --- Relasi ---
    public function tipeKamar()
    {
        return $this->belongsTo(TipeKamar::class, 'tipe_kamar_id');
    }

    // Many-to-Many: satu kamar bisa ada di banyak booking (pada waktu berbeda)
    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_kamar')
                    ->withPivot('harga_malam', 'jumlah_malam', 'subtotal')
                    ->withTimestamps();
    }

    // --- Scope ---
    public function scopeTersedia($query)
    {
        return $query->where('status', 'tersedia');
    }

    public function scopeByTipe($query, int $tipeId)
    {
        return $query->where('tipe_kamar_id', $tipeId);
    }

    // --- Accessor ---
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'tersedia'    => '<span class="badge bg-success">Tersedia</span>',
            'ditempati'   => '<span class="badge bg-danger">Ditempati</span>',
            'maintenance' => '<span class="badge bg-warning text-dark">Maintenance</span>',
            default       => '<span class="badge bg-secondary">-</span>',
        };
    }
}
