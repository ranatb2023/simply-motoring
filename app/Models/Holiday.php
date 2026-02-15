<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    protected $fillable = ['staff_id', 'date', 'description', 'start_time', 'end_time', 'is_closed'];

    protected $casts = [
        'date' => 'date',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
