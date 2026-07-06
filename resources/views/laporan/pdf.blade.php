<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Finansial Reservasi Hotel - LuxeHotel</title>
    <style>
        /* Pengaturan Dasar Halaman Cetak */
        @page {
            margin: 1.5cm;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #ffffff; /* Tetap putih agar hemat tinta saat dicetak */
            color: #1a1a1a;
            font-size: 11px;
            line-height: 1.4;
            -webkit-font-smoothing: antialiased;
        }
        
        /* Brand / Header Laporan */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .brand-title {
            font-family: 'Playfair Display', 'Georgia', serif;
            font-size: 22px;
            font-weight: 300;
            color: #d4af37; /* Emas Khas LuxeHotel */
            text-transform: uppercase;
            letter-spacing: 3px;
        }
        .brand-subtitle {
            color: #737373;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 4px;
        }
        .report-meta {
            text-align: right;
            color: #737373;
            font-size: 10px;
            line-height: 1.5;
        }

        /* Pembatas Estetik Emas Lux */
        .divider {
            height: 1px;
            background: #d4af37;
            opacity: 0.4;
            margin-bottom: 25px;
        }

        /* Kartu Ringkasan Finansial */
        .summary-box-container {
            width: 100%;
            margin-bottom: 30px;
        }
        .summary-card {
            width: 23%;
            padding: 12px 10px;
            background-color: #121212; /* Hitam Arang khas tema gelap Luxe */
            border: 1px solid rgba(212, 175, 55, 0.3); /* Border emas halus */
            border-top: 3px solid #d4af37; /* Garis aksen emas di atas */
            display: inline-block;
            vertical-align: top;
            margin-right: 1.5%;
            box-shadow: 0 4px 6px rgba(0,0,0,0.02);
        }
        .summary-card.last {
            margin-right: 0;
        }
        .summary-label {
            font-size: 8.5px;
            color: #a3a3a3;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 6px;
            font-weight: bold;
        }
        .summary-value {
            font-size: 12px;
            font-weight: bold;
            font-family: 'Courier New', Courier, monospace;
            color: #ffffff; /* Teks nilai putih di atas background hitam */
        }

        /* Gaya Tabel Data */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .data-table th {
            background-color: #121212; /* Hitam Premium */
            color: #d4af37; /* Teks Emas */
            padding: 10px 12px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9px;
            letter-spacing: 1px;
            text-align: left;
            border: 1px solid #121212;
        }
        .data-table td {
            padding: 9px 12px;
            border-bottom: 1px solid #e5e5e5;
            color: #262626;
        }
        .data-table tr:nth-child(even) td {
            background-color: #fafafa; /* Efek zebra ringan */
        }

        /* Badge Status Premium */
        .badge {
            padding: 3px 8px;
            font-size: 8.5px;
            font-weight: bold;
            border-radius: 2px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-block;
        }
        .badge-lunas {
            background-color: rgba(16, 185, 129, 0.1); /* Hijau Emerald transparan */
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }
        .badge-aktif {
            background-color: rgba(14, 165, 233, 0.1); /* Biru Cerah transparan */
            color: #0ea5e9;
            border: 1px solid rgba(14, 165, 233, 0.2);
        }

        /* Helper Teknis */
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .font-mono {
            font-family: 'Courier New', Courier, monospace;
        }
    </style>
</head>
<body>

    {{-- HEADER LAPORAN --}}
    <table class="header-table">
        <tr>
            <td>
                <div class="brand-title">LuxeHotel</div>
                <div class="brand-subtitle">Neraca & Laporan Finansial Reservasi</div>
            </td>
            <td class="report-meta">
                <strong>Periode:</strong> {{ \Carbon\Carbon::parse($dari)->format('d M Y') }} - {{ \Carbon\Carbon::parse($sampai)->format('d M Y') }}<br>
                <strong>Tanggal Cetak:</strong> {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }}
            </td>
        </tr>
    </table>

    <div class="divider"></div>

    {{-- RINGKASAN KARTU KAS (SUMMARY) --}}
    <div class="summary-box-container">
        <div class="summary-card">
            <div class="summary-label">Total Nilai Kontrak</div>
            <div class="summary-value">Rp {{ number_format($bookings->sum('total_harga'), 0, ',', '.') }}</div>
        </div>
        <div class="summary-card">
            <div class="summary-label">Total Uang Muka</div>
            <div class="summary-value" style="color: #f59e0b;">Rp {{ number_format($bookings->sum('uang_muka'), 0, ',', '.') }}</div>
        </div>
        <div class="summary-card">
            <div class="summary-label">Pelunasan Selesai</div>
            <div class="summary-value" style="color: #10b981;">
                Rp {{ number_format($bookings->where('status', 'checkout')->sum(fn($b) => $b->total_harga - $b->uang_muka), 0, ',', '.') }}
            </div>
        </div>
        <div class="summary-card last">
            <div class="summary-label">Piutang Berjalan</div>
            <div class="summary-value" style="color: #0ea5e9;">
                Rp {{ number_format($bookings->where('status', 'checkin')->sum(fn($b) => $b->total_harga - $b->uang_muka), 0, ',', '.') }}
            </div>
        </div>
    </div>

    {{-- TABEL BREAKDOWN RIWAYAT TRANSAKSI --}}
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 15%;">Kode Booking</th>
                <th style="width: 25%;">Nama Tamu</th>
                <th style="width: 20%;">Bilik Kamar</th>
                <th style="width: 15%; text-align: right;">Uang Muka</th>
                <th style="width: 15%; text-align: right;">Total Tarif</th>
                <th style="width: 10%; text-align: center;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $booking)
            <tr>
                <td class="font-mono" style="font-weight: bold; color: #d4af37;">{{ $booking->kode_booking }}</td>
                <td style="font-weight: 500;">{{ $booking->tamu->nama_lengkap ?? '-' }}</td>
                <td class="font-mono">
                    @foreach($booking->kamar as $kamar)
                        RM-{{ $kamar->nomor_kamar }}{{ !$loop->last ? ',' : '' }}
                    @endforeach
                </td>
                <td class="text-right font-mono">Rp {{ number_format($booking->uang_muka, 0, ',', '.') }}</td>
                <td class="text-right font-mono" style="font-weight: bold;">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</td>
                <td class="text-center">
                    @if($booking->status === 'checkout')
                        <span class="badge badge-lunas">Lunas</span>
                    @else
                        <span class="badge badge-aktif">Aktif</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center" style="color: #737373; font-style: italic; padding: 25px;">
                    Tidak ditemukan pergerakan transaksi kas pada range tanggal ini.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>