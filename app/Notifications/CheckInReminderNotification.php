<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Pengingat check-in H-1 atau pada hari check-in.
 *
 * Cara kirim (dari Command/Scheduler):
 *   $booking->user->notify(new CheckInReminderNotification($booking));
 */
class CheckInReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly Booking $booking) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $hariIni = $this->booking->tanggal_checkin->isToday();

        return (new MailMessage)
            ->subject('[Hotel] Pengingat Check-In: ' . $this->booking->kode_booking)
            ->greeting('Halo, ' . $notifiable->name . '!')
            ->line($hariIni
                ? 'Tamu dengan booking **' . $this->booking->kode_booking . '** dijadwalkan check-in HARI INI.'
                : 'Tamu dengan booking **' . $this->booking->kode_booking . '** dijadwalkan check-in BESOK.'
            )
            ->line('**Tamu:** ' . $this->booking->tamu->nama_lengkap)
            ->line('**Tanggal Check-In:** ' . $this->booking->tanggal_checkin->isoFormat('D MMMM Y'))
            ->line('**Kamar:** ' . $this->booking->kamar->pluck('nomor_kamar')->implode(', '))
            ->action('Proses Check-In', url('/checkin/' . $this->booking->id))
            ->line('Pastikan kamar sudah siap sebelum tamu tiba.');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'         => 'checkin_reminder',
            'kode_booking' => $this->booking->kode_booking,
            'tamu'         => $this->booking->tamu->nama_lengkap,
            'tanggal_ci'   => $this->booking->tanggal_checkin->format('d/m/Y'),
            'booking_id'   => $this->booking->id,
            'url'          => '/checkin/' . $this->booking->id,
            'message'      => 'Pengingat check-in: ' . $this->booking->kode_booking . ' - ' . $this->booking->tamu->nama_lengkap,
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
