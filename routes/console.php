<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Send appointment reminder emails (1 week before + 24 hours before).
// Runs hourly so 24-hour reminders are timely. Requires the Laravel
// scheduler cron on the server: * * * * * php artisan schedule:run
Schedule::command('bookings:send-reminders')->hourly();

// Note: Google Calendar deletions are reconciled on-view (booking form + admin
// dashboard) — see BookingService::syncDeletedGoogleEvents — so no cron is needed.
// The `bookings:sync-deletions` command remains available for manual/optional runs.
