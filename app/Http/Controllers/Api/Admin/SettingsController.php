<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Availability;
use App\Models\Schedule;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        if (Schedule::count() === 0) {
            Schedule::create([
                'name' => 'Working hours (default)',
                'is_default' => true
            ]);
        }
        // Aggregate fetch
        return response()->json([
            'schedules' => Schedule::all(),
            'availability' => Availability::all(),
            'timezone' => \App\Models\Setting::where('key', 'timezone')->value('value')
        ]);
    }

    public function store(Request $request)
    {
        // Save Availability for a Schedule
        $scheduleId = $request->input('schedule_id');
        $entries = $request->input('availability');

        if (!$scheduleId)
            return response()->json(['success' => false]);

        // Replace logic
        Availability::where('schedule_id', $scheduleId)->delete();

        foreach ($entries as $entry) {
            Availability::create([
                'schedule_id' => $scheduleId,
                'day_of_week' => $entry['day_of_week'],
                'start_time' => $entry['start_time'],
                'end_time' => $entry['end_time'],
                'is_closed' => $entry['is_closed'] ?? false
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function assignServices(Request $request)
    {
        $scheduleId = $request->input('schedule_id');
        $serviceIds = $request->input('service_ids', []); // IDs to be on this schedule

        if (!$scheduleId) {
            return response()->json(['success' => false, 'message' => 'Schedule ID required']);
        }

        // 1. Unassign services that are currently on this schedule BUT NOT in the new list
        \App\Models\Service::where('schedule_id', $scheduleId)
            ->whereNotIn('id', $serviceIds)
            ->update(['schedule_id' => null]);

        // 2. Assign the selected services to this schedule
        if (!empty($serviceIds)) {
            \App\Models\Service::whereIn('id', $serviceIds)
                ->update(['schedule_id' => $scheduleId]);
        }

        return response()->json(['success' => true]);
    }

    public function getTimezone()
    {
        $tz = \App\Models\Setting::where('key', 'timezone')->value('value');
        // fallback
        if (!$tz)
            $tz = config('app.timezone');
        return response()->json(['timezone' => $tz]);
    }

    public function storeTimezone(Request $request)
    {
        $data = $request->validate([
            'timezone' => 'required|string'
        ]);

        \App\Models\Setting::updateOrCreate(
            ['key' => 'timezone'],
            ['value' => $data['timezone']]
        );

        return response()->json(['success' => true]);
    }
}

