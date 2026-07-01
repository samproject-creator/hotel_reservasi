<?php

namespace App\Observers;

use App\Models\Booking;
use App\Services\ActivityLogService;

/**
 * BookingObserver
 *
 * Otomatis mencatat log setiap kali data Booking dibuat, diubah, atau dihapus.
 * Daftarkan di App\Providers\AppServiceProvider::boot() atau di Model dengan
 * #[ObservedBy(BookingObserver::class)]
 */
class BookingObserver
{
    public function created(Booking $booking): void
    {
        ActivityLogService::logModel(
            'created',
            'booking',
            $booking,
            'Booking baru dibuat: ' . $booking->kode_booking . ' untuk tamu ID ' . $booking->tamu_id,
        );
    }

    public function updated(Booking $booking): void
    {
        // Jika yang berubah hanya status, beri pesan lebih spesifik
        if ($booking->isDirty('status')) {
            $dari   = $booking->getOriginal('status');
            $ke     = $booking->status;
            $action = match ($ke) {
                'checkin'   => 'checkin',
                'checkout'  => 'checkout',
                'cancelled' => 'deleted',
                default     => 'updated',
            };
            ActivityLogService::log(
                $action,
                'booking',
                $booking->id,
                'Status booking ' . $booking->kode_booking . ' berubah dari "' . $dari . '" menjadi "' . $ke . '"',
                ['status' => $dari],
                ['status' => $ke],
            );
        } else {
            ActivityLogService::logModel(
                'updated',
                'booking',
                $booking,
                'Data booking ' . $booking->kode_booking . ' diperbarui',
            );
        }
    }

    public function deleted(Booking $booking): void
    {
        ActivityLogService::logModel(
            'deleted',
            'booking',
            $booking,
            'Booking ' . $booking->kode_booking . ' dihapus',
        );
    }
}
