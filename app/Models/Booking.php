<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = ['vehicle_reg', 'service_id', 'sub_service', 'bay_id', 'start_datetime', 'end_datetime', 'customer_name', 'customer_email', 'customer_phone', 'status', 'edit_token', 'google_event_id', 'google_calendar_id', 'google_events'];

    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
        'google_events' => 'array',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Public "manage my booking" URL the customer uses to edit/cancel.
     */
    public function manageUrl(): ?string
    {
        return $this->edit_token ? url('/booking/manage/' . $this->edit_token) : null;
    }
}
