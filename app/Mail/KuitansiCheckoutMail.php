<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class KuitansiCheckoutMail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Kuitansi Digital Resmi Pelunasan - ' . $this->booking->kode_booking,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.kuitansi_checkout',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}