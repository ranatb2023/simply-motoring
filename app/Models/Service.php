<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Service extends Model
{
    protected $fillable = [
        'name', 'description', 'type', 'duration_minutes', 'price', 'schedule_id',
        'buffer_before_minutes', 'buffer_after_minutes', 'advance_booking_days',
        'min_notice_hours', 'time_increment', 'max_bookings_per_day',
        'collect_phone', 'collect_vehicle_reg', 'send_confirmation_email', 'is_active',
    ];

    protected $casts = [
        'collect_phone' => 'boolean',
        'collect_vehicle_reg' => 'boolean',
        'send_confirmation_email' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
    public function staff()
    {
        return $this->belongsToMany(User::class, 'staff_service', 'service_id', 'user_id');
    }
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function bays()
    {
        return $this->belongsToMany(Bay::class, 'service_bay');
    }
}
