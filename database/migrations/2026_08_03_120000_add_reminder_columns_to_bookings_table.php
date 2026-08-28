<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Track when the 1-week and 24-hour appointment reminder emails were sent
 * for each booking, so the scheduled reminder command never sends duplicates.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->timestamp('reminder_week_sent_at')->nullable();
            $table->timestamp('reminder_day_sent_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['reminder_week_sent_at', 'reminder_day_sent_at']);
        });
    }
};
