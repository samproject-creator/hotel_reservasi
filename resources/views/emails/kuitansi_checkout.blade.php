<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Receipt - LuxeHotel</title>
</head>
<body style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #0b0b0b; color: #e5e5e5; padding: 40px 20px; margin: 0; -webkit-font-smoothing: antialiased;">
    
    <div style="max-width: 550px; margin: 0 auto; background-color: #121212; border: 1px solid #d4af37; padding: 40px; border-radius: 4px; box-shadow: 0 20px 40px rgba(0,0,0,0.5);">
        
        <div style="text-align: center; border-bottom: 1px solid rgba(212, 175, 55, 0.2); padding-bottom: 25px; margin-bottom: 30px;">
            <h2 style="color: #d4af37; font-family: 'Playfair Display', 'Georgia', serif; font-size: 28px; font-weight: 300; letter-spacing: 4px; margin: 0; text-transform: uppercase;">LuxeHotel</h2>
            <p style="color: #a3a3a3; font-size: 10px; letter-spacing: 2px; text-transform: uppercase; margin: 5px 0 0 0;">Official Digital Receipt</p>
        </div>

        <div style="margin-bottom: 30px;">
            <p style="font-size: 15px; color: #ffffff; margin-bottom: 10px;">Dear <strong>{{ $booking->tamu->nama_lengkap }}</strong>,</p>
            <p style="font-size: 14px; color: #a3a3a3; line-height: 1.6; margin: 0;">Thank you for choosing LuxeHotel as your sanctuary of refinement. It was our absolute pleasure to host you. Please find the details of your settled account below:</p>
        </div>
        
        <table style="width: 100%; text-align: left; margin: 30px 0; border-collapse: collapse; font-size: 13px;">
            <tr>
                <th style="padding: 14px 0; border-bottom: 1px solid rgba(255,255,255,0.05); color: #a3a3a3; font-weight: 400; text-transform: uppercase; letter-spacing: 1px; width: 35%;">Booking Code</th>
                <td style="padding: 14px 0; border-bottom: 1px solid rgba(255,255,255,0.05); font-family: 'Courier New', Courier, monospace; color: #d4af37; font-weight: bold; font-size: 15px; text-align: right;">{{ $booking->kode_booking }}</td>
            </tr>
            <tr>
                <th style="padding: 14px 0; border-bottom: 1px solid rgba(255,255,255,0.05); color: #a3a3a3; font-weight: 400; text-transform: uppercase; letter-spacing: 1px;">Room Allocated</th>
                <td style="padding: 14px 0; border-bottom: 1px solid rgba(255,255,255,0.05); color: #ffffff; text-align: right; font-weight: 500;">
                    @foreach($booking->kamar as $kamar)
                        Room {{ $kamar->nomor_kamar }}@if(!$loop->last), @endif
                    @endforeach
                </td>
            </tr>
            <tr>
                <th style="padding: 14px 0; border-bottom: 1px solid rgba(255,255,255,0.05); color: #a3a3a3; font-weight: 400; text-transform: uppercase; letter-spacing: 1px;">Grand Total</th>
                <td style="padding: 14px 0; border-bottom: 1px solid rgba(255,255,255,0.05); color: #ffffff; text-align: right; font-size: 16px; font-weight: 600;">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th style="padding: 14px 0; color: #a3a3a3; font-weight: 400; text-transform: uppercase; letter-spacing: 1px;">Payment Status</th>
                <td style="padding: 14px 0; color: #10b981; font-weight: bold; text-align: right; letter-spacing: 1px; font-size: 12px;">
                    <span style="background-color: rgba(16, 185, 129, 0.1); padding: 4px 12px; border-radius: 2px; text-transform: uppercase;">Paid (Checked-Out)</span>
                </td>
            </tr>
        </table>
        
        <div style="margin-top: 40px; text-align: center; border-top: 1px solid rgba(212, 175, 55, 0.1); padding-top: 25px;">
            <p style="font-size: 13px; color: #ffffff; font-family: 'Playfair Display', Georgia, serif; font-style: italic; margin: 0 0 5px 0;">We look forward to welcoming you back.</p>
            <p style="font-size: 10px; color: #737373; letter-spacing: 1px; text-transform: uppercase; margin: 0;">LuxeHotel Group &copy; {{ date('Y') }}</p>
        </div>

    </div>
</body>
</html>