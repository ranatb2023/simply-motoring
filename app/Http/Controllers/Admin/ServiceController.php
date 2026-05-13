<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = Service::all();
        return view('admin.services.index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::role(['staff', 'admin'])->get();
        $schedules = Schedule::all();
        return view('admin.services.create', compact('users', 'schedules'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'                   => 'required|string|max:100',
            'description'            => 'nullable|string',
            'type'                   => 'nullable|string',
            'duration_minutes'       => 'required|integer|min:1',
            'price'                  => 'required|numeric|min:0',
            'schedule_id'            => 'nullable|exists:schedules,id',
            'buffer_before_minutes'  => 'nullable|integer|min:0',
            'buffer_after_minutes'   => 'nullable|integer|min:0',
            'advance_booking_days'   => 'nullable|integer|min:1',
            'min_notice_hours'       => 'nullable|integer|min:0',
            'time_increment'         => 'nullable|integer|in:15,30,60',
            'max_bookings_per_day'   => 'nullable|integer|min:1',
            'user_ids'               => 'nullable|array',
            'user_ids.*'             => 'exists:users,id',
            'options_label'          => 'nullable|string|max:100',
            'options'                => 'nullable|string',
        ]);

        $data = $request->only([
            'name', 'description', 'duration_minutes', 'price', 'schedule_id',
            'buffer_before_minutes', 'buffer_after_minutes', 'advance_booking_days',
            'min_notice_hours', 'time_increment', 'max_bookings_per_day',
        ]);

        $data['type']                     = $request->input('type', 'service');
        $data['collect_phone']            = $request->boolean('collect_phone');
        $data['collect_vehicle_reg']      = $request->boolean('collect_vehicle_reg');
        $data['send_confirmation_email']  = $request->boolean('send_confirmation_email');
        $data['is_active']                = $request->boolean('is_active');
        $data['options_label']            = $request->input('options_label');
        
        $optionsArray = [];
        if ($request->filled('options')) {
            $optionsArray = array_values(array_filter(array_map('trim', explode(',', $request->options))));
        }
        $data['options'] = empty($optionsArray) ? null : $optionsArray;

        $service = Service::create($data);

        if ($request->filled('user_ids')) {
            $service->staff()->sync($request->user_ids);
        }

        return redirect()->route('admin.services.index')
            ->with('success', 'Service created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        return view('admin.services.show', compact('service'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        $service->load('staff');
        $users = User::role(['staff', 'admin'])->get();
        $schedules = Schedule::all();
        return view('admin.services.edit', compact('service', 'users', 'schedules'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service)
    {
        $request->validate([
            'name'                   => 'required|string|max:100',
            'description'            => 'nullable|string',
            'type'                   => 'nullable|string',
            'duration_minutes'       => 'required|integer|min:1',
            'price'                  => 'required|numeric|min:0',
            'schedule_id'            => 'nullable|exists:schedules,id',
            'buffer_before_minutes'  => 'nullable|integer|min:0',
            'buffer_after_minutes'   => 'nullable|integer|min:0',
            'advance_booking_days'   => 'nullable|integer|min:1',
            'min_notice_hours'       => 'nullable|integer|min:0',
            'time_increment'         => 'nullable|integer|in:15,30,60',
            'max_bookings_per_day'   => 'nullable|integer|min:1',
            'user_ids'               => 'nullable|array',
            'user_ids.*'             => 'exists:users,id',
            'options_label'          => 'nullable|string|max:100',
            'options'                => 'nullable|string',
        ]);

        $data = $request->only([
            'name', 'description', 'duration_minutes', 'price', 'schedule_id',
            'buffer_before_minutes', 'buffer_after_minutes', 'advance_booking_days',
            'min_notice_hours', 'time_increment', 'max_bookings_per_day',
        ]);

        $data['type']                    = $request->input('type', 'service');
        $data['collect_phone']           = $request->boolean('collect_phone');
        $data['collect_vehicle_reg']     = $request->boolean('collect_vehicle_reg');
        $data['send_confirmation_email'] = $request->boolean('send_confirmation_email');
        $data['is_active']               = $request->boolean('is_active');
        $data['options_label']           = $request->input('options_label');

        $optionsArray = [];
        if ($request->filled('options')) {
            $optionsArray = array_values(array_filter(array_map('trim', explode(',', $request->options))));
        }
        $data['options'] = empty($optionsArray) ? null : $optionsArray;

        $service->update($data);

        $service->staff()->sync($request->input('user_ids', []));

        return redirect()->route('admin.services.index')
            ->with('success', 'Service updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()->route('admin.services.index')
            ->with('success', 'Service deleted successfully.');
    }
}
