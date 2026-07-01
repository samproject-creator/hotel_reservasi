<?php

use Illuminate\Support\Facades\Schedule;

/**
 * Scheduler Laravel 11 (routes/console.php)
 *
 * Aktifkan dengan menambahkan cron job di server:
 *   * * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
 */

// Kirim reminder check-in H-1 setiap pagi pukul 07:00
Schedule::command('hotel:checkin-reminder --days=1')
         ->dailyAt('07:00')
         ->withoutOverlapping()
         ->appendOutputTo(storage_path('logs/checkin-reminder.log'));

// Kirim reminder check-in H-0 (hari ini) pukul 06:00
Schedule::command('hotel:checkin-reminder --days=0')
         ->dailyAt('06:00')
         ->withoutOverlapping();

// Bersihkan activity log lebih dari 90 hari secara otomatis setiap bulan
Schedule::call(function () {
    \App\Models\ActivityLog::where('created_at', '<', now()->subDays(90))->delete();
})->monthly()->at('02:00');
