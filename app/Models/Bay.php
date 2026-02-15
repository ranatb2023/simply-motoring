<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bay extends Model
{
    protected $fillable = ['name', 'type'];

    public function services()
    {
        return $this->belongsToMany(Service::class, 'service_bay');
    }
}
