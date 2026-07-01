<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BookingPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Booking $booking): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Booking $booking): bool
    {
        return in_array($booking->status, ['pending', 'confirmed']);
    }

    public function delete(User $user, Booking $booking): bool
    {
        return $user->isAdmin() && $booking->status === 'cancelled';
    }

    public function cancel(User $user, Booking $booking): bool
    {
        return !in_array($booking->status, ['checkin', 'checkout', 'cancelled']);
    }
}
