<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable; 

    protected $fillable = ['name', 'email', 'password', 'role', 'no_hp', 'is_active', 'google_id'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'is_active'         => 'boolean',
    ];

    // --- Relasi ---
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function checkins()
    {
        return $this->hasMany(Checkin::class);
    }

    public function checkouts()
    {
        return $this->hasMany(Checkout::class);
    }

    // --- Helper ---
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isPetugas(): bool
    {
        return $this->role === 'petugas';
    }
}
