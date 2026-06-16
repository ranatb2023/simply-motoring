<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Reconcile bookings against Google Calendar: cancel bookings whose event was
// deleted in Google so the slot frees up. Requires a server cron running
// `php artisan schedule:run` every minute.
Schedule::command('bookings:sync-deletions')->everyFifteenMinutes()->withoutOverlapping();
