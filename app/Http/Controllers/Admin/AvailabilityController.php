<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    /**
     * Display the availability settings.
     */
    public function index()
    {
        // Placeholder for connected accounts logic
        // In the future, fetch from database or service
        $connected_accounts = []; // e.g. [['email' => 'user@gmail.com', 'provider' => 'google']]

        return view('admin.availability.index', compact('connected_accounts'));
    }
}
