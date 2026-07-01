<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkout extends Model
{
    use HasFactory;

    protected $table = 'checkout';

    protected $fillable = [
        'booking_id', 'user_id', 'waktu_checkout',
        'total_tagihan', 'biaya_tambahan', 'keterangan_biaya',
        'metode_pembayaran', 'total_bayar', 'kembalian', 'catatan',
    ];

    protected $casts = [
        'waktu_checkout'   => 'datetime',
        'total_tagihan'    => 'decimal:2',
        'biaya_tambahan'   => 'decimal:2',
        'total_bayar'      => 'decimal:2',
        'kembalian'        => 'decimal:2',
    ];

    // --- Relasi ---
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
