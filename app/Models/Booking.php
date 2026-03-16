<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = ['vehicle_reg', 'service_id', 'sub_service', 'bay_id', 'start_datetime', 'end_datetime', 'customer_name', 'customer_email', 'customer_phone', 'status', 'google_event_id', 'google_calendar_id'];

    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
