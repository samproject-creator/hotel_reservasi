<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
    <h3>LAPORAN FINANSIAL RESERVASI HOTEL</h3>
    <p>Periode: {{ $dari->format('d/m/Y') }} s/d {{ $sampai->format('d/m/Y') }}</p>

    <table border="1">
        <thead>
            <tr style="background-color: #f2f2f2; font-weight: bold;">
                <th>Kode Booking</th>
                <th>Nama Tamu</th>
                <th>Tanggal Check-in</th>
                <th>Tanggal Check-out</th>
                <th>Uang Muka (DP)</th>
                <th>Total Harga</th>
                <th>Status Finansial</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bookings as $booking)
            <tr>
                <td>{{ $booking->kode_booking }}</td>
                <td>{{ $booking->tamu->nama_lengkap ?? '-' }}</td>
                <td>{{ $booking->tanggal_checkin->format('d/m/Y') }}</td>
                <td>{{ $booking->tanggal_checkout->format('d/m/Y') }}</td>
                <td>{{ $booking->uang_muka }}</td>
                <td>{{ $booking->total_harga }}</td>
                <td>{{ $booking->status == 'checkout' ? 'LUNAS' : 'BELUM PELUNASAN' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>