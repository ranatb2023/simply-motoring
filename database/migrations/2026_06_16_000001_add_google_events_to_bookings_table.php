<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Stores all Google Calendar events for a booking, e.g. a split
            // "Service + MOT" creates two: [{event_id, calendar_id}, ...]
            $table->json('google_events')->nullable()->after('google_calendar_id');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('google_events');
        });
    }
};
