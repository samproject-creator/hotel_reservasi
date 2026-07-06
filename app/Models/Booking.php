<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'booking';

    protected $fillable = [
        'kode_booking', 'tamu_id', 'user_id',
        'tanggal_checkin', 'tanggal_checkout',
        'jumlah_tamu', 'status', 'total_harga', 'uang_muka', 'catatan',
    ];

    protected $casts = [
        'tanggal_checkin'  => 'date',
        'tanggal_checkout' => 'date',
        'total_harga'      => 'decimal:2',
        'uang_muka'        => 'decimal:2',
    ];

    // --- Relasi ---
    public function tamu()
    {
        return $this->belongsTo(Tamu::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Many-to-Many dengan Kamar
    public function kamar()
    {
        return $this->belongsToMany(Kamar::class, 'booking_kamar')
                    ->withPivot('harga_malam', 'jumlah_malam', 'subtotal')
                    ->withTimestamps();
    }

    public function checkin()
    {
        return $this->hasOne(Checkin::class);
    }

    public function checkout()
    {
        return $this->hasOne(Checkout::class);
    }

    // --- Helper / Business Logic ---
    public function getJumlahMalamAttribute(): int
    {
        return $this->tanggal_checkin->diffInDays($this->tanggal_checkout);
    }

    public function getSisaTagihanAttribute(): float
    {
        return (float) $this->total_harga - (float) $this->uang_muka;
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending'   => '<span class="badge bg-secondary">Pending</span>',
            'confirmed' => '<span class="badge bg-primary">Confirmed</span>',
            'checkin'   => '<span class="badge bg-info">Check-In</span>',
            'checkout'  => '<span class="badge bg-success">Check-Out</span>',
            'cancelled' => '<span class="badge bg-danger">Cancelled</span>',
            default     => '-',
        };
    }

    // Generate kode booking otomatis
    public static function generateKode(): string
    {
        $tanggal = Carbon::now()->format('Ymd');
        $last    = static::whereDate('created_at', today())->count() + 1;
        return 'BK-' . $tanggal . '-' . str_pad($last, 4, '0', STR_PAD_LEFT);
    }

    // --- Scope ---
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByTanggal($query, string $dari, string $sampai)
    {
        return $query->whereBetween('tanggal_checkin', [$dari, $sampai]);
    }
}
