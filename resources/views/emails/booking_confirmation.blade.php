<x-mail::message>
# Booking Confirmed!

Halo {{ $booking->tamu->nama_lengkap }},

Booking Anda di **LuxeHotel** telah dikonfirmasi. Berikut adalah detail pesanan Anda:

**Kode Booking:** {{ $booking->kode_booking }}
**Check-in:** {{ \Carbon\Carbon::parse($booking->tanggal_checkin)->format('d M Y') }}
**Check-out:** {{ \Carbon\Carbon::parse($booking->tanggal_checkout)->format('d M Y') }}

<x-mail::button :url="route('dashboard')">
Lihat Detail Booking
</x-mail::button>

Terima kasih telah memilih LuxeHotel.

Salam,<br>
{{ config('app.name') }}
</x-mail::message>
