<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Note: Google Calendar deletions are reconciled on-view (booking form + admin
// dashboard) — see BookingService::syncDeletedGoogleEvents — so no cron is needed.
// The `bookings:sync-deletions` command remains available for manual/optional runs.
