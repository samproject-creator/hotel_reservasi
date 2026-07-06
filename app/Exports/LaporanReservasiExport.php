<?php

namespace App\Exports;

use App\Models\Booking;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class LaporanReservasiExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithTitle
{
    public function __construct(
        private string $dari,
        private string $sampai
    ) {}

    public function title(): string
    {
        return 'Laporan Reservasi';
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Booking',
            'Nama Tamu',
            'NIK',
            'No. HP',
            'Kamar',
            'Tipe Kamar',
            'Tgl Check-In',
            'Tgl Check-Out',
            'Jumlah Malam',
            'Jumlah Tamu',
            'Total Harga (Rp)',
            'DP / Uang Muka (Rp)',
            'Status',
            'Petugas',
        ];
    }

    public function collection()
    {
        $bookings = Booking::with(['tamu', 'kamar.tipeKamar', 'user'])
                           ->byTanggal($this->dari, $this->sampai)
                           ->get();

        return $bookings->map(function ($booking, $index) {
            $kamarNomor = $booking->kamar->pluck('nomor_kamar')->implode(', ');
            $kamarTipe  = $booking->kamar->map(fn($k) => $k->tipeKamar->nama_tipe)->unique()->implode(', ');

            return [
                $index + 1,
                $booking->kode_booking,
                $booking->tamu->nama_lengkap,
                $booking->tamu->nik,
                $booking->tamu->no_hp,
                $kamarNomor,
                $kamarTipe,
                $booking->tanggal_checkin->format('d/m/Y'),
                $booking->tanggal_checkout->format('d/m/Y'),
                $booking->jumlah_malam,
                $booking->jumlah_tamu,
                number_format($booking->total_harga, 0, ',', '.'),
                number_format($booking->uang_muka, 0, ',', '.'),
                ucfirst($booking->status),
                $booking->user->name,
            ];
        });
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2563EB']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }
}
