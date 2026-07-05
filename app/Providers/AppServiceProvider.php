<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// Models
use App\Models\Booking;
use App\Models\Kamar;
use App\Models\Tamu;
use App\Models\User;
use App\Models\TipeKamar;

// Observers
use App\Observers\BookingObserver;
use App\Observers\KamarObserver;
use App\Observers\TamuObserver;
use App\Observers\UserObserver;
use App\Observers\TipeKamarObserver;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // ── Daftarkan semua Observer ──────────────────────────────────────
        // Observer akan otomatis mencatat activity log setiap ada perubahan
        // data pada model terkait (create, update, delete).
        Booking::observe(BookingObserver::class);
        Kamar::observe(KamarObserver::class);
        Tamu::observe(TamuObserver::class);
        User::observe(UserObserver::class);
        TipeKamar::observe(TipeKamarObserver::class);
    }
}
