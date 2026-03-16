<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->unsignedSmallInteger('buffer_before_minutes')->default(0)->after('duration_minutes');
            $table->unsignedSmallInteger('buffer_after_minutes')->default(0)->after('buffer_before_minutes');
            $table->unsignedSmallInteger('advance_booking_days')->default(60)->after('buffer_after_minutes');
            $table->unsignedSmallInteger('min_notice_hours')->default(4)->after('advance_booking_days');
            $table->unsignedSmallInteger('time_increment')->default(30)->after('min_notice_hours');
            $table->unsignedSmallInteger('max_bookings_per_day')->nullable()->after('time_increment');
            $table->boolean('collect_phone')->default(false)->after('max_bookings_per_day');
            $table->boolean('collect_vehicle_reg')->default(true)->after('collect_phone');
            $table->boolean('send_confirmation_email')->default(true)->after('collect_vehicle_reg');
            $table->boolean('is_active')->default(true)->after('send_confirmation_email');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn([
                'buffer_before_minutes', 'buffer_after_minutes', 'advance_booking_days',
                'min_notice_hours', 'time_increment', 'max_bookings_per_day',
                'collect_phone', 'collect_vehicle_reg', 'send_confirmation_email', 'is_active',
            ]);
        });
    }
};