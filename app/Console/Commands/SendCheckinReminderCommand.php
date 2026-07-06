<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\User;
use App\Notifications\CheckInReminderNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

/**
 * Command: php artisan hotel:checkin-reminder
 *
 * Jalankan via Laravel Scheduler di routes/console.php:
 *   Schedule::command('hotel:checkin-reminder')->dailyAt('07:00');
 */
class SendCheckinReminderCommand extends Command
{
    protected $signature   = 'hotel:checkin-reminder {--days=1 : Kirim reminder untuk booking N hari ke depan}';
    protected $description = 'Kirim notifikasi pengingat check-in ke semua petugas';

    public function handle(): int
    {
        $days = (int) $this->option('days');
        $tanggal = now()->addDays($days)->format('Y-m-d');

        $bookings = Booking::with(['tamu', 'kamar', 'user'])
                           ->where('tanggal_checkin', $tanggal)
                           ->whereIn('status', ['confirmed'])
                           ->get();

        if ($bookings->isEmpty()) {
            $this->info('Tidak ada booking check-in pada ' . $tanggal . '.');
            return self::SUCCESS;
        }

        // Ambil semua petugas aktif yang akan menerima notifikasi
        $petugas = User::where('is_active', true)->get();

        foreach ($bookings as $booking) {
            Notification::send($petugas, new CheckInReminderNotification($booking));
            $this->line('  Reminder dikirim untuk: ' . $booking->kode_booking . ' - ' . $booking->tamu->nama_lengkap);
        }

        $this->info('Berhasil mengirim ' . $bookings->count() . ' reminder check-in.');

        return self::SUCCESS;
    }
}
