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
            $calc = $this->calculateTotal($data['kamar_ids'], $data['tanggal_checkin'], $data['tanggal_checkout']);

            $booking = Booking::create([
                'kode_booking'     => Booking::generateKode(),
                'tamu_id'          => $data['tamu_id'],
                'user_id'          => auth()->id(),
                'tanggal_checkin'  => $data['tanggal_checkin'],
                'tanggal_checkout' => $data['tanggal_checkout'],
                'jumlah_tamu'      => $data['jumlah_tamu'],
                'status'           => 'confirmed',
                'total_harga'      => $calc['total_harga'],
                'uang_muka'        => $data['uang_muka'] ?? 0,
                'catatan'          => $data['catatan'] ?? null,
            ]);

            $booking->kamar()->attach($calc['kamar_data']);

            return $booking;
        });

        // Kirim Notifikasi WhatsApp jika token tersedia
        $this->notifyBookingConfirmed($booking);

        return $booking;
    }

    public function updateBooking(Booking $booking, array $data)
    {
        return DB::transaction(function () use ($booking, $data) {
            $checkin  = $data['tanggal_checkin'] ?? $booking->tanggal_checkin;
            $checkout = $data['tanggal_checkout'] ?? $booking->tanggal_checkout;
            $kamarIds = $data['kamar_ids'] ?? $booking->kamar->pluck('id')->toArray();

            $calc = $this->calculateTotal($kamarIds, $checkin, $checkout);

            $booking->update([
                'tanggal_checkin'  => $checkin,
                'tanggal_checkout' => $checkout,
                'jumlah_tamu'      => $data['jumlah_tamu'] ?? $booking->jumlah_tamu,
                'total_harga'      => $calc['total_harga'],
                'uang_muka'        => $data['uang_muka'] ?? $booking->uang_muka,
                'catatan'          => $data['catatan'] ?? $booking->catatan,
            ]);

            $booking->kamar()->sync($calc['kamar_data']);

            return $booking;
        });
    }

    protected function calculateTotal(array $kamarIds, $checkinDate, $checkoutDate)
    {
        $checkin  = \Carbon\Carbon::parse($checkinDate);
        $checkout = \Carbon\Carbon::parse($checkoutDate);
        $malam    = max(1, $checkin->diffInDays($checkout));

        $totalHarga = 0;
        $kamarData  = [];

        $kamar = Kamar::with('tipeKamar')->whereIn('id', $kamarIds)->get();

        foreach ($kamar as $kamar) {
            $harga       = $kamar->tipeKamar->harga_per_malam;
            $subtotal    = $harga * $malam;
            $totalHarga += $subtotal;

            $kamarData[$kamar->id] = [
                'harga_malam'  => $harga,
                'jumlah_malam' => $malam,
                'subtotal'     => $subtotal,
            ];
        }

        return [
            'total_harga' => $totalHarga,
            'kamar_data'  => $kamarData,
        ];
    }

    protected function notifyBookingConfirmed(Booking $booking)
    {
        $booking->load('tamu');

        $ci = \Carbon\Carbon::parse($booking->tanggal_checkin)->format('d F Y');
        $co = \Carbon\Carbon::parse($booking->tanggal_checkout)->format('d F Y');

        // WhatsApp Notification
        $message = "Yth. Bapak/Ibu {$booking->tamu->nama_lengkap},\n\n" .
                   "Booking Anda di LuxeHotel telah DIKONFIRMASI.\n" .
                   "Kode Booking: {$booking->kode_booking}\n" .
                   "Check-in: {$ci}\n" .
                   "Check-out: {$co}\n\n" .
                   "Terima kasih telah memilih layanan kami.";
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
        $query = Booking::whereHas('kamar', function ($q) use ($kamarIds) {
            $q->whereIn('kamar.id', $kamarIds);
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
