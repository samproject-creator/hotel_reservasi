<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Kamar;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingConfirmationMail;

class BookingService
{
    protected $fonnte;

    public function __construct(FonnteService $fonnte)
    {
        $this->fonnte = $fonnte;
    }

    public function createBooking(array $data)
    {
        $booking = DB::transaction(function () use ($data) {
            $checkin  = \Carbon\Carbon::parse($data['tanggal_checkin']);
            $checkout = \Carbon\Carbon::parse($data['tanggal_checkout']);
            $malam    = $checkin->diffInDays($checkout);

            $totalHarga = 0;
            $kamarData  = [];

            foreach ($data['kamar_ids'] as $kamarId) {
                $kamar       = Kamar::with('tipeKamar')->findOrFail($kamarId);
                $harga       = $kamar->tipeKamar->harga_per_malam;
                $subtotal    = $harga * $malam;
                $totalHarga += $subtotal;

                $kamarData[$kamarId] = [
                    'harga_malam'  => $harga,
                    'jumlah_malam' => $malam,
                    'subtotal'     => $subtotal,
                ];
            }

            $booking = Booking::create([
                'kode_booking'     => Booking::generateKode(),
                'tamu_id'          => $data['tamu_id'],
                'user_id'          => auth()->id(),
                'tanggal_checkin'  => $data['tanggal_checkin'],
                'tanggal_checkout' => $data['tanggal_checkout'],
                'jumlah_tamu'      => $data['jumlah_tamu'],
                'status'           => 'confirmed',
                'total_harga'      => $totalHarga,
                'uang_muka'        => $data['uang_muka'] ?? 0,
                'catatan'          => $data['catatan'] ?? null,
            ]);

            $booking->kamars()->attach($kamarData);

            return $booking;
        });

        // Kirim Notifikasi WhatsApp jika token tersedia
        $this->notifyBookingConfirmed($booking);

        return $booking;
    }

    protected function notifyBookingConfirmed(Booking $booking)
    {
        $booking->load('tamu');

        // WhatsApp Notification
        $message = "Halo {$booking->tamu->nama_lengkap},\n\nBooking Anda di LuxeHotel telah DIKONFIRMASI!\nKode Booking: {$booking->kode_booking}\nCheck-in: {$booking->tanggal_checkin}\nCheck-out: {$booking->tanggal_checkout}\n\nTerima kasih.";
        $this->fonnte->sendMessage($booking->tamu->no_hp, $message);

        // Email Notification
        if ($booking->tamu->email) {
            try {
                Mail::to($booking->tamu->email)->send(new BookingConfirmationMail($booking));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Failed to send booking confirmation email: ' . $e->getMessage());
            }
        }
    }

    public function checkAvailability($kamarIds, $checkin, $checkout, $excludeBookingId = null)
    {
        // Check if rooms are not in 'maintenance'
        $availableRooms = Kamar::whereIn('id', $kamarIds)
            ->where('status', '!=', 'maintenance')
            ->count();

        if ($availableRooms !== count($kamarIds)) {
            return false;
        }

        // Check for overlapping bookings
        $query = Booking::whereHas('kamars', function ($q) use ($kamarIds) {
            $q->whereIn('kamars.id', $kamarIds);
        })
        ->where(function ($q) use ($checkin, $checkout) {
            $q->where('tanggal_checkin', '<', $checkout)
              ->where('tanggal_checkout', '>', $checkin);
        })
        ->whereNotIn('status', ['cancelled', 'checkout']);

        if ($excludeBookingId) {
            $query->where('id', '!=', $excludeBookingId);
        }

        return !$query->exists();
    }
}
