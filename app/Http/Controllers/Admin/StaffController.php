<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StaffController extends Controller
{
    public function index()
    {
        // Get users with role 'staff'
        $staff = User::role('staff')->get();
        return view('admin.staff.index', compact('staff'));
    }

    public function create(Request $request)
    {
        // If this is a fresh page visit (not a return from Google OAuth callback),
        // always clear the old wizard session so the previous staff's calendar doesn't show.
        if (!$request->query('google_connected')) {
            session()->forget('staff_wizard_google_account');
            session()->forget('staff_wizard_data');
        }

        $services = \App\Models\Service::all();

        $rawTimezones = \DateTimeZone::listIdentifiers();
        $groupedTimezones = [];
        foreach ($rawTimezones as $tz) {
            $parts = explode('/', $tz, 2);
            $region = $parts[0];
            $city = isset($parts[1]) ? str_replace('_', ' ', $parts[1]) : $region;

            try {
                $now = new \DateTime('now', new \DateTimeZone($tz));
                $time = $now->format('g:i a');
            } catch (\Exception $e) {
                $time = '';
            }

            $groupedTimezones[$region][] = [
                'value' => $tz,
                'name' => $city,
                'time' => $time
            ];
        }

        // Only show the Google account that was connected during THIS wizard session
        $google_account = session('staff_wizard_google_account');

        // Retrieve saved wizard data (for restoring after Google OAuth redirect)
        $wizardData = session('staff_wizard_data', []);

        return view('admin.staff.create', compact('services', 'groupedTimezones', 'google_account', 'wizardData'));
    }

    /**
     * Save wizard form data to session (AJAX, called before Google OAuth redirect)
     */
    public function saveWizardData(Request $request)
    {
        session(['staff_wizard_data' => $request->all()]);
        return response()->json(['success' => true]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'info' => $request->info,
            'limit_hours' => $request->limit_hours ?? 8,
            'timezone' => $request->timezone ?? 'UTC',
            'password' => Hash::make(Str::random(12)),
        ]);

        $user->assignRole('staff');

        // Save services (Step 3)
        if ($request->has('services')) {
            $user->services()->sync($request->services);
        }

        // Save weekly schedule (Step 4)
        if ($request->filled('schedule_json')) {
            $scheduleData = json_decode($request->schedule_json, true);
            if (is_array($scheduleData)) {
                $schedule = \App\Models\Schedule::create([
                    'name' => $user->name . ' Schedule',
                    'is_default' => 0,
                ]);

                foreach ($scheduleData as $index => $day) {
                    if (!empty($day['on']) && !empty($day['slots'])) {
                        foreach ($day['slots'] as $slot) {
                            \App\Models\Availability::create([
                                'schedule_id' => $schedule->id,
                                'day_of_week' => $index,
                                'start_time' => $slot['s'],
                                'end_time' => $slot['e'],
                                'is_closed' => 0,
                            ]);
                        }
                    } else {
                        \App\Models\Availability::create([
                            'schedule_id' => $schedule->id,
                            'day_of_week' => $index,
                            'start_time' => '00:00',
                            'end_time' => '00:00',
                            'is_closed' => 1,
                        ]);
                    }
                }

                $user->update(['schedule_id' => $schedule->id]);
            }
        }

        // Save days off (Step 5)
        if ($request->filled('days_off_json')) {
            $daysOff = json_decode($request->days_off_json, true);
            if (is_array($daysOff)) {
                foreach ($daysOff as $dateStr) {
                    \App\Models\Holiday::create([
                        'staff_id' => $user->id,
                        'date' => $dateStr,
                        'description' => 'Day off',
                        'is_closed' => 1,
                    ]);
                }
            }
        }

        // Save Google Calendar connection if present in session
        $googleAccount = session('staff_wizard_google_account');
        if ($googleAccount && !empty($googleAccount['email'])) {
            $existingInfo = $user->info ?? '';
            $user->update([
                'info' => $existingInfo . ($existingInfo ? "\n" : '') . 'Google Calendar: ' . $googleAccount['email'],
            ]);
        }

        // Clear the wizard's Google Calendar session
        session()->forget('staff_wizard_google_account');

        return redirect()->route('admin.staff.index')->with('success', 'Staff member added successfully.');
    }

    public function edit(User $staff)
    {
        $services = \App\Models\Service::all();

        // Check if we have temporary wizard data (e.g. after Google OAuth redirect)
        $wizardData = session('staff_wizard_data', []);
        
        // Merge wizardData into the $staff object properties temporarily for the view
        if (!empty($wizardData)) {
            foreach(['name', 'email', 'phone', 'info', 'limit_hours', 'timezone'] as $field) {
                if (isset($wizardData[$field])) {
                    $staff->$field = $wizardData[$field];
                }
            }
        }

        $rawTimezones = \DateTimeZone::listIdentifiers();
        $groupedTimezones = [];
        foreach ($rawTimezones as $tz) {
            $parts = explode('/', $tz, 2);
            $region = $parts[0];
            $city = isset($parts[1]) ? str_replace('_', ' ', $parts[1]) : $region;

            try {
                $now = new \DateTime('now', new \DateTimeZone($tz));
                $time = $now->format('g:i a');
            } catch (\Exception $e) {
                $time = '';
            }

            $groupedTimezones[$region][] = [
                'value' => $tz,
                'name' => $city,
                'time' => $time
            ];
        }

        // Helper for Google Account display (extract from info if available)
        $googleAccount = null;
        if ($staff->info && preg_match('/Google Calendar: (.*)/', $staff->info, $matches)) {
            $googleAccount = ['email' => trim($matches[1])];
        }

        // Selected Services
        $selectedServices = $staff->services->pluck('id')->toArray();

        // Format Schedule for JSON
        $scheduleJson = "[]";
        if ($staff->schedule_id) {
            $availabilities = \App\Models\Availability::where('schedule_id', $staff->schedule_id)->get()->groupBy('day_of_week');
            $formattedSchedule = [];
            $dayLabels = ['S', 'M', 'T', 'W', 'T', 'F', 'S'];
            for ($i = 0; $i < 7; $i++) {
                if (isset($availabilities[$i])) {
                    $dayAvail = $availabilities[$i];
                    if ($dayAvail->first()->is_closed) {
                        $formattedSchedule[$i] = ['l' => $dayLabels[$i], 'on' => false, 'slots' => []];
                    } else {
                        $slots = [];
                        foreach ($dayAvail as $av) {
                            $slots[] = ['s' => substr($av->start_time, 0, 5), 'e' => substr($av->end_time, 0, 5)];
                        }
                        $formattedSchedule[$i] = ['l' => $dayLabels[$i], 'on' => true, 'slots' => $slots];
                    }
                } else {
                    $formattedSchedule[$i] = ['l' => $dayLabels[$i], 'on' => false, 'slots' => []];
                }
            }
            $scheduleJson = json_encode($formattedSchedule);
        }

        // Format Days Off for JSON
        $daysOff = \App\Models\Holiday::where('staff_id', $staff->id)->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('Y-m-d'))->toArray();
        $daysOffJson = json_encode($daysOff);

        return view('admin.staff.edit', compact('staff', 'services', 'groupedTimezones', 'googleAccount', 'selectedServices', 'scheduleJson', 'daysOffJson'));
    }

    public function update(Request $request, User $staff)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $staff->id,
        ]);

        // Preserve Google Calendar info if it exists in the bio
        $newInfo = $request->info;
        if ($staff->info && preg_match('/Google Calendar: (.*)/', $staff->info, $matches)) {
            $googleEmail = trim($matches[1]);
            if (!str_contains($newInfo, 'Google Calendar:')) {
                $newInfo = trim($newInfo) . ($newInfo ? "\n" : "") . "Google Calendar: " . $googleEmail;
            }
        }

        $staff->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'info' => $newInfo,
            'limit_hours' => $request->limit_hours ?? 8,
            'timezone' => $request->timezone ?? 'UTC',
        ]);

        // Update services (Step 3)
        if ($request->has('services')) {
            $staff->services()->sync($request->services);
        } else {
            $staff->services()->detach();
        }

        // Update weekly schedule (Step 4)
        if ($request->filled('schedule_json')) {
            $scheduleData = json_decode($request->schedule_json, true);
            if (is_array($scheduleData)) {
                // If the staff has no schedule, create one. Otherwise, clear old availabilities.
                if (!$staff->schedule_id) {
                    $schedule = \App\Models\Schedule::create([
                        'name' => $staff->name . ' Schedule',
                        'is_default' => 0,
                    ]);
                    $staff->update(['schedule_id' => $schedule->id]);
                } else {
                    $schedule = \App\Models\Schedule::find($staff->schedule_id);
                    \App\Models\Availability::where('schedule_id', $schedule->id)->delete();
                }

                foreach ($scheduleData as $index => $day) {
                    if (!empty($day['on']) && !empty($day['slots'])) {
                        foreach ($day['slots'] as $slot) {
                            \App\Models\Availability::create([
                                'schedule_id' => $schedule->id,
                                'day_of_week' => $index,
                                'start_time' => $slot['s'],
                                'end_time' => $slot['e'],
                                'is_closed' => 0,
                            ]);
                        }
                    } else {
                        \App\Models\Availability::create([
                            'schedule_id' => $schedule->id,
                            'day_of_week' => $index,
                            'start_time' => '00:00',
                            'end_time' => '00:00',
                            'is_closed' => 1,
                        ]);
                    }
                }
            }
        }

        // Update days off (Step 5)
        if ($request->filled('days_off_json')) {
            $daysOff = json_decode($request->days_off_json, true);
            if (is_array($daysOff)) {
                // Clear old holidays
                \App\Models\Holiday::where('staff_id', $staff->id)->delete();
                foreach ($daysOff as $dateStr) {
                    \App\Models\Holiday::create([
                        'staff_id' => $staff->id,
                        'date' => $dateStr,
                        'description' => 'Day off',
                        'is_closed' => 1,
                    ]);
                }
            }
        }

        return redirect()->route('admin.staff.index')->with('success', 'Staff member updated successfully.');
    }

    public function destroy(User $staff)
    {
        $staff->delete();
        return redirect()->route('admin.staff.index')->with('success', 'Staff member removed successfully.');
    }
}
