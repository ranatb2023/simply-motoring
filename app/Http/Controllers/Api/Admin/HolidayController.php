<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Holiday;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    public function index()
    {
        return response()->json(Holiday::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'dates' => 'required|array',
            'dates.*' => 'date',
            'description' => 'nullable|string',
            'is_closed' => 'required|boolean',
            'intervals' => 'nullable|array',
            'intervals.*.start' => 'required_if:is_closed,false',
            'intervals.*.end' => 'required_if:is_closed,false',
        ]);

        $userId = auth()->id() ?? 0;

        foreach ($validated['dates'] as $date) {
            // Clear existing overrides for this date
            Holiday::where('date', $date)->delete();

            if ($validated['is_closed']) {
                Holiday::create([
                    'staff_id' => $userId,
                    'date' => $date,
                    'description' => $validated['description'],
                    'is_closed' => true,
                    'start_time' => null,
                    'end_time' => null
                ]);
            } else {
                if (!empty($validated['intervals'])) {
                    foreach ($validated['intervals'] as $interval) {
                        Holiday::create([
                            'staff_id' => $userId,
                            'date' => $date,
                            'description' => $validated['description'],
                            'is_closed' => false,
                            'start_time' => $interval['start'],
                            'end_time' => $interval['end']
                        ]);
                    }
                }
            }
        }

        return response()->json(['success' => true]);
    }

    public function destroy(Request $request)
    {
        Holiday::destroy($request->query('id'));
        return response()->json(['success' => true]);
    }
}
