<?php

namespace App\Console\Commands;

use App\Mail\BookingReminder;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendBookingReminders extends Command
{
    protected $signature = 'bookings:send-reminders';

    protected $description = 'Email customers a reminder 1 week and 24 hours before their appointment';

    public function handle(): int
    {
        $now = Carbon::now();

        // ── 1-week (upcoming) reminder ──────────────────────────────────────
        // Bookings more than 24h but within 7 days away that haven't had it yet.
        $weekBookings = Booking::query()
            ->where('status', 'confirmed')
            ->whereNull('reminder_week_sent_at')
            ->where('start_datetime', '>', $now->copy()->addDay())
            ->where('start_datetime', '<=', $now->copy()->addDays(7))
            ->get();

        $weekSent = $this->dispatch($weekBookings, 'week', 'reminder_week_sent_at');

        // ── 24-hour reminder ────────────────────────────────────────────────
        // Bookings within the next 24 hours that haven't had it yet.
        $dayBookings = Booking::query()
            ->where('status', 'confirmed')
            ->whereNull('reminder_day_sent_at')
            ->where('start_datetime', '>', $now)
            ->where('start_datetime', '<=', $now->copy()->addDay())
            ->get();

        $daySent = $this->dispatch($dayBookings, 'day', 'reminder_day_sent_at');

        $this->info("Reminders sent — week: {$weekSent}, 24h: {$daySent}.");

        return self::SUCCESS;
    }

    /**
     * Send the given reminder window to a set of bookings, marking each as sent.
     */
    private function dispatch($bookings, string $window, string $flag): int
    {
        $sent = 0;

        foreach ($bookings as $booking) {
            if (empty($booking->customer_email)) {
                continue;
            }

            try {
                Mail::to($booking->customer_email)->send(new BookingReminder($booking, $window));
                $booking->forceFill([$flag => Carbon::now()])->save();
                $sent++;
                $this->line("  {$window} reminder → {$booking->customer_email} (booking #{$booking->id})");
            } catch (\Throwable $e) {
                $this->error("  Failed {$window} reminder for booking #{$booking->id}: {$e->getMessage()}");
            }
        }

        return $sent;
    }
}
