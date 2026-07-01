<?php

namespace App\Services;

use App\Models\Booking;

/**
 * QrCodeService
 *
 * Meng-generate QR Code untuk booking sebagai fitur nilai tambahan.
 *
 * Membutuhkan package: composer require simplesoftwareio/simple-qrcode
 *
 * Cara penggunaan:
 *   $svg    = QrCodeService::generate($booking);          // SVG string
 *   $base64 = QrCodeService::generateBase64($booking);   // base64 PNG untuk <img>
 *   $path   = QrCodeService::saveToFile($booking);       // simpan ke storage
 */
class QrCodeService
{
    /**
     * Data yang di-encode dalam QR Code.
     * Format: JSON string berisi info booking yang esensial.
     */
    public static function buildPayload(Booking $booking): string
    {
        return json_encode([
            'kode'    => $booking->kode_booking,
            'tamu'    => $booking->tamu->nama_lengkap,
            'ci'      => $booking->tanggal_checkin->format('Y-m-d'),
            'co'      => $booking->tanggal_checkout->format('Y-m-d'),
            'kamar'   => $booking->kamars->pluck('nomor_kamar')->implode(','),
            'ts'      => now()->timestamp, // timestamp generate
        ]);
    }

    /**
     * Generate QR Code sebagai SVG string.
     * Tampilkan langsung di blade: {!! QrCodeService::generate($booking) !!}
     */
    public static function generate(Booking $booking, int $size = 200): string
    {
        // Cek package tersedia
        if (!class_exists(\SimpleSoftwareIO\QrCode\Facades\QrCode::class)) {
            throw new \RuntimeException(
                'Package QR Code belum di-install. Jalankan: composer require simplesoftwareio/simple-qrcode'
            );
        }

        return \SimpleSoftwareIO\QrCode\Facades\QrCode::size($size)
            ->format('svg')
            ->generate(self::buildPayload($booking));
    }

    /**
     * Generate QR Code sebagai base64 PNG.
     * Digunakan untuk disisipkan ke PDF atau email.
     * Contoh: <img src="data:image/png;base64,{{ $base64 }}">
     */
    public static function generateBase64(Booking $booking, int $size = 150): string
    {
        if (!class_exists(\SimpleSoftwareIO\QrCode\Facades\QrCode::class)) {
            throw new \RuntimeException(
                'Package QR Code belum di-install. Jalankan: composer require simplesoftwareio/simple-qrcode'
            );
        }

        $image = \SimpleSoftwareIO\QrCode\Facades\QrCode::size($size)
            ->format('png')
            ->generate(self::buildPayload($booking));

        return base64_encode($image);
    }

    /**
     * Simpan QR Code ke storage/app/public/qrcodes/{kode_booking}.png
     * Kembalikan path relatif yang bisa diakses via Storage::url().
     */
    public static function saveToFile(Booking $booking, int $size = 200): string
    {
        if (!class_exists(\SimpleSoftwareIO\QrCode\Facades\QrCode::class)) {
            throw new \RuntimeException(
                'Package QR Code belum di-install. Jalankan: composer require simplesoftwareio/simple-qrcode'
            );
        }

        $directory = storage_path('app/public/qrcodes');
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $filename = $booking->kode_booking . '.png';
        $fullPath = $directory . '/' . $filename;

        $image = \SimpleSoftwareIO\QrCode\Facades\QrCode::size($size)
            ->format('png')
            ->generate(self::buildPayload($booking));

        file_put_contents($fullPath, $image);

        return 'qrcodes/' . $filename; // relative path untuk Storage::url()
    }
}
