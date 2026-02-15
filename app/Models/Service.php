<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['name', 'type', 'duration_minutes', 'price', 'schedule_id'];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
    public function staff()
    {
        return $this->belongsToMany(Staff::class, 'staff_service');
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
