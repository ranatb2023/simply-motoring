<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = ['name', 'is_default'];

    public function availabilities()
    {
        return $this->hasMany(Availability::class);
    }
    public function staff()
    {
        return $this->hasMany(Staff::class);
    }
    public function services()
    {
        return $this->hasMany(Service::class);
    }
}
