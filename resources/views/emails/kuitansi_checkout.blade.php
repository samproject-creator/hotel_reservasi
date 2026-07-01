<!DOCTYPE html>
<html>
<head>
    <title>Kuitansi Digital</title>
</head>
<body style="font-family: sans-serif; background-color: #0f172a; color: #cbd5e1; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #1e1b4b; border: 1px solid #4c1d95; padding: 20px; rounded-style: 8px;">
        <h2 style="color: #e9d5ff; font-family: serif;">Hotel Transylvania</h2>
        <p>Halo, <strong>{{ $booking->tamu->nama_lengkap }}</strong>.</p>
        <p>Terima kasih telah memilih Kastil kami sebagai tempat beristirahat. Berikut adalah bukti pelunasan digital Anda:</p>
        
        <table style="width: 100%; text-align: left; margin: 20px 0; border-collapse: collapse;">
            <tr>
                <th style="padding: 8px; border-bottom: 1px solid #4c1d95;">Kode Booking</th>
                <td style="padding: 8px; border-bottom: 1px solid #4c1d95; font-family: monospace; color: #f59e0b;">{{ $booking->kode_booking }}</td>
            </tr>
            <tr>
                <th style="padding: 8px; border-bottom: 1px solid #4c1d95;">Kamar</th>
                <td style="padding: 8px; border-bottom: 1px solid #4c1d95;">
                    @foreach($booking->kamars as $kamar)
                        {{ $kamar->nomor_kamar }} 
                    @endforeach
                </td>
            </tr>
            <tr>
                <th style="padding: 8px; border-bottom: 1px solid #4c1d95;">Total Harga</th>
                <td style="padding: 8px; border-bottom: 1px solid #4c1d95;">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th style="padding: 8px; border-bottom: 1px solid #4c1d95;">Status Tagihan</th>
                <td style="padding: 8px; border-bottom: 1px solid #4c1d95; color: #10b981; font-weight: bold;">LUNAS (Selesai Check-out)</td>
            </tr>
        </table>
        
        <p style="font-size: 11px; color: #94a3b8; text-align: center; margin-top: 30px;">🦇 Sampai jumpa di malam kegelapan berikutnya! 🦇</p>
    </div>
</body>
</html>