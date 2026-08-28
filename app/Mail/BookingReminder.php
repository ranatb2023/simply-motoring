<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingReminder extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  string  $window  'week' (1 week before) or 'day' (24 hours before)
     */
    public function __construct(public Booking $booking, public string $window = 'week')
    {
    }

    public function envelope(): Envelope
    {
        $subject = $this->window === 'day'
            ? 'Reminder: Your appointment is tomorrow — Simply Motoring'
            : 'Reminder: Your upcoming appointment — Simply Motoring';

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.booking-reminder',
        );
    }
}
