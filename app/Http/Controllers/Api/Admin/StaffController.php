<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StaffController extends Controller
{
    public function index()
    {
        // Return users with role 'staff', load services relation
        $staff = User::role('staff')->with('services')->get()->map(function ($s) {
            $s->service_ids = $s->services->pluck('id');
            // Ensure compatibility with frontend expecting specific fields
            return $s;
        });

        return response()->json($staff);
    }

    public function store(Request $request)
    {
        $id = $request->input('id');
        $data = $request->except(['id', 'service_ids', 'schedule', 'days_off']);

        if ($id) {
            $user = User::find($id);
            if ($user && $user->hasRole('staff')) {
                // Remove password from data if present and empty, or handle it separately
                // Ideally this endpoint isn't used for password updates unless specified
                $updateData = collect($data)->except(['password'])->toArray();
                $user->update($updateData);
            }
        } else {
            // Create
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'info' => $request->info,
                'limit_hours' => $request->limit_hours ?? 0,
                'schedule_id' => $request->schedule_id ?? 0,
                'timezone' => $request->timezone ?? 'UTC',
                'password' => Hash::make(Str::random(12)),
            ]);
            $user->assignRole('staff');
        }

        // Sync Services if passed
        if ($request->has('service_ids') && isset($user)) {
            $user->services()->sync($request->input('service_ids'));
        }

        return response()->json(['success' => true, 'id' => $user ? $user->id : null]);
    }

    public function destroy(Request $request)
    {
        $id = $request->query('id');
        $user = User::find($id);
        if ($user && $user->hasRole('staff')) {
            $user->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'User not found or not staff']);
    }
}
