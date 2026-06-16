<?php

namespace App\Console\Commands;

use App\Services\BookingService;
use Illuminate\Console\Command;

class SyncCalendarDeletions extends Command
{
    protected $signature = 'bookings:sync-deletions';

    protected $description = 'Cancel bookings whose Google Calendar event was deleted, freeing the slot for new bookings';

    public function handle(BookingService $bookingService): int
    {
        $result = $bookingService->syncDeletedGoogleEvents();
        $this->info("Checked {$result['checked']} booking(s); cancelled {$result['cancelled']} whose Google event was removed.");
        return self::SUCCESS;
    }
}
