<?php

namespace App\Services;

use App\Models\Booking;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use chillerlan\QRCode\Common\EccLevel;
use chillerlan\QRCode\Common\Version;
use chillerlan\QRCode\Output\QRMarkupSVG;
use chillerlan\QRCode\Output\QRGdImagePNG;

/**
 * QrCodeService
 *
 * Meng-generate QR Code untuk booking menggunakan chillerlan/php-qrcode.
 */
class QrCodeService
{
    /**
     * Data yang di-encode dalam QR Code.
     */
    public static function buildPayload(Booking $booking): string
    {
        return json_encode([
            'kode'    => $booking->kode_booking,
            'tamu'    => $booking->tamu->nama_lengkap,
            'ci'      => $booking->tanggal_checkin->format('Y-m-d'),
            'co'      => $booking->tanggal_checkout->format('Y-m-d'),
            'kamar'   => $booking->kamar->pluck('nomor_kamar')->implode(','),
            'ts'      => now()->timestamp,
        ]);
    }

    /**
     * Generate QR Code sebagai SVG image source (base64).
     */
    public static function generate(Booking $booking, int $size = 200): string
    {
        $options = new QROptions([
            'version'         => Version::AUTO,
            'eccLevel'        => EccLevel::L,
            'outputInterface' => QRMarkupSVG::class,
            'addQuietzone'    => true,
            'svgAddXmlHeader' => false,
            'outputBase64'    => true,
        ]);

        $dataUri = (new QRCode($options))->render(self::buildPayload($booking));

        return sprintf('<img src="%s" width="%d" height="%d" alt="Booking QR" class="mx-auto" />', $dataUri, $size, $size);
    }

    /**
     * Generate QR Code sebagai base64 PNG data URI.
     */
    public static function generateBase64(Booking $booking, int $size = 150): string
    {
        $options = new QROptions([
            'version'         => Version::AUTO,
            'eccLevel'        => EccLevel::L,
            'outputInterface' => QRGdImagePNG::class,
            'addQuietzone'    => true,
            'outputBase64'    => true,
        ]);

        return (new QRCode($options))->render(self::buildPayload($booking));
    }

    /**
     * Simpan QR Code ke storage/app/public/qrcodes/{kode_booking}.png
     */
    public static function saveToFile(Booking $booking, int $size = 200): string
    {
        $directory = storage_path('app/public/qrcodes');
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $filename = $booking->kode_booking . '.png';
        $fullPath = $directory . '/' . $filename;

        $options = new QROptions([
            'version'         => Version::AUTO,
            'eccLevel'        => EccLevel::L,
            'outputInterface' => QRGdImagePNG::class,
            'addQuietzone'    => true,
            'outputBase64'    => false,
        ]);

        (new QRCode($options))->render(self::buildPayload($booking), $fullPath);

        return 'qrcodes/' . $filename;
    }
}
