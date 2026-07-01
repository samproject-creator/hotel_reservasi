<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Notifikasi dikirim ke admin/petugas saat booking baru dikonfirmasi.
 *
 * Cara kirim:
 *   $petugas->notify(new BookingConfirmedNotification($booking));
 *   // atau via Notification facade ke semua admin:
 *   Notification::send(User::admin()->get(), new BookingConfirmedNotification($booking));
 */
class BookingConfirmedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly Booking $booking) {}

    /**
     * Channel pengiriman: database (in-app) + mail (opsional)
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    // ── Email ─────────────────────────────────────────────────────────────
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('[Hotel] Booking Baru: ' . $this->booking->kode_booking)
            ->greeting('Halo, ' . $notifiable->name . '!')
            ->line('Ada booking baru yang masuk dan perlu ditindaklanjuti.')
            ->line('**Kode Booking:** ' . $this->booking->kode_booking)
            ->line('**Tamu:** ' . $this->booking->tamu->nama_lengkap)
            ->line('**Check-In:** ' . $this->booking->tanggal_checkin->isoFormat('D MMMM Y'))
            ->line('**Check-Out:** ' . $this->booking->tanggal_checkout->isoFormat('D MMMM Y'))
            ->line('**Total Harga:** Rp ' . number_format($this->booking->total_harga, 0, ',', '.'))
            ->action('Lihat Detail Booking', url('/booking/' . $this->booking->id))
            ->line('Terima kasih telah menggunakan Sistem Reservasi Hotel.');
    }

    // ── Database (in-app notification) ────────────────────────────────────
    public function toDatabase(object $notifiable): array
    {
        return [
            'type'          => 'booking_confirmed',
            'kode_booking'  => $this->booking->kode_booking,
            'tamu'          => $this->booking->tamu->nama_lengkap,
            'tanggal_ci'    => $this->booking->tanggal_checkin->format('d/m/Y'),
            'tanggal_co'    => $this->booking->tanggal_checkout->format('d/m/Y'),
            'total_harga'   => $this->booking->total_harga,
            'booking_id'    => $this->booking->id,
            'url'           => '/booking/' . $this->booking->id,
            'message'       => 'Booking baru: ' . $this->booking->kode_booking . ' dari ' . $this->booking->tamu->nama_lengkap,
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
