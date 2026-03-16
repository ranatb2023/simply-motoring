<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public bool $isAdmin;

    public function __construct(public Booking $booking, bool $isAdmin = false)
    {
        $this->isAdmin = $isAdmin;
    }

    public function envelope(): Envelope
    {
        $subject = $this->isAdmin
            ? 'New Booking: ' . $this->booking->customer_name . ' — ' . ($this->booking->service->name ?? 'Service')
            : 'Booking Confirmed — Simply Motoring';

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: $this->isAdmin ? 'emails.booking-admin' : 'emails.booking-customer',
        );
    }
}