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

    public function create()
    {
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

        $accounts = \App\Models\Setting::where('key', 'google_accounts')->value('value');

        // Instead of fetching from global settings, fetch from session if available
        $google_account = session('staff_wizard_google_account');

        // Fallback or specific logic: 
        // If we want to allow selecting from global accounts (optional future feature), we could keep $accounts.
        // But for "Steps 246", user specifically said "don't fetch... availability page". 
        // So we strictly only show what's in the session (the one they just connected).

        return view('admin.staff.create', compact('services', 'groupedTimezones', 'google_account'));
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
            'timezone' => $request->timezone,
            'password' => Hash::make(Str::random(12)),
        ]);

        $user->assignRole('staff');

        if ($request->has('services')) {
            $user->services()->sync($request->services);
        }

        return redirect()->route('admin.staff.index')->with('success', 'Staff member added successfully.');
    }

    public function edit(User $staff)
    {
        return view('admin.staff.edit', compact('staff'));
    }

    public function update(Request $request, User $staff)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $staff->id,
        ]);

        $staff->update($request->only(['name', 'email', 'phone', 'info']));
        // Note: limit_hours might be needed update here too depending on view inputs.

        return redirect()->route('admin.staff.index')->with('success', 'Staff member updated successfully.');
    }

    public function destroy(User $staff)
    {
        $staff->delete();
        return redirect()->route('admin.staff.index')->with('success', 'Staff member removed successfully.');
    }
}
