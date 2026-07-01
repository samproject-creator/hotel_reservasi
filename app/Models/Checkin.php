<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkin extends Model
{
    use HasFactory;

    protected $table = 'checkin';

    protected $fillable = ['booking_id', 'user_id', 'waktu_checkin', 'no_identitas', 'catatan'];

    protected $casts = [
        'waktu_checkin' => 'datetime',
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
