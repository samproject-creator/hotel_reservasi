<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Finansial Reservasi Hotel</title>
    <style>
        /* Pengaturan Dasar Halaman Cetak */
        @page {
            margin: 1.2cm 1.2cm 1.2cm 1.2cm;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1e1b4b; /* Indigo tua */
            font-size: 11px;
            line-height: 1.4;
        }
        
        /* Brand / Header Laporan */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .brand-title {
            font-size: 20px;
            font-weight: bold;
            color: #4c1d95; /* Ungu gelap */
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .report-meta {
            text-align: right;
            color: #64748b;
            font-size: 10px;
        }

        /* Pembatas Estetik */
        .divider {
            height: 3px;
            background-color: #7c3aed; /* Ungu neon */
            margin-bottom: 20px;
        }

        /* Kartu Ringkasan Finansial */
        .summary-box-container {
            width: 100%;
            margin-bottom: 25px;
        }
        .summary-card {
            width: 23%;
            padding: 10px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-left: 4px solid #7c3aed;
            display: inline-block;
            vertical-align: top;
            margin-right: 1.5%;
        }
        .summary-card.last {
            margin-right: 0;
        }
        .summary-label {
            font-size: 9px;
            color: #64748b;
            text-transform: uppercase;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .summary-value {
            font-size: 13px;
            font-weight: bold;
            font-family: 'Courier New', Courier, monospace;
            color: #0f172a;
        }

        /* Gaya Tabel Data */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .data-table th {
            background-color: #4c1d95;
            color: #ffffff;
            padding: 8px 10px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9px;
            text-align: left;
            border: 1px solid #4c1d95;
        }
        .data-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #e2e8f0;
            color: #334155;
        }
        .data-table tr:nth-child(even) td {
            background-color: #f8fafc;
        }

        /* Badge Status */
        .badge {
            padding: 2px 6px;
            font-size: 9px;
            font-weight: bold;
            border-radius: 3px;
            text-transform: uppercase;
        }
        .badge-lunas {
            background-color: #dcfce7;
            color: #15803d;
        }
        .badge-aktif {
            background-color: #e0f2fe;
            color: #0369a1;
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
                <div class="brand-title">NERACA & LAPORAN FINANSIAL</div>
                <div style="color: #64748b; margin-top: 3px;">Manajemen Omzet & Arus Kas Hunian Kamar</div>
            </td>
            <td class="report-meta">
                <strong>Periode:</strong> {{ \Carbon\Carbon::parse($dari)->format('d F Y') }} - {{ \Carbon\Carbon::parse($sampai)->format('d F Y') }}<br>
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
            <div class="summary-value" style="color: #b45309;">Rp {{ number_format($bookings->sum('uang_muka'), 0, ',', '.') }}</div>
        </div>
        <div class="summary-card">
            <div class="summary-label">Pelunasan Selesai</div>
            <div class="summary-value" style="color: #15803d;">
                Rp {{ number_format($bookings->where('status', 'checkout')->sum(fn($b) => $b->total_harga - $b->uang_muka), 0, ',', '.') }}
            </div>
        </div>
        <div class="summary-card last">
            <div class="summary-label">Piutang Berjalan</div>
            <div class="summary-value" style="color: #0369a1;">
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
                <td class="font-mono" style="font-weight: bold; color: #4c1d95;">{{ $booking->kode_booking }}</td>
                <td>{{ $booking->tamu->nama_lengkap ?? '-' }}</td>
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
                <td colspan="6" class="text-center" style="color: #64748b; font-style: italic; padding: 20px;">
                    Tidak ditemukan pergerakan transaksi kas pada range tanggal ini.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>