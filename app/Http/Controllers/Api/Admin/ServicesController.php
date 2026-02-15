<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServicesController extends Controller
{
    public function index()
    {
        return response()->json(Service::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'type' => 'nullable|string',
            'duration_minutes' => 'nullable|integer',
            'price' => 'nullable|numeric',
        ]);

        $service = Service::create($validated);
        return response()->json(['success' => true, 'id' => $service->id]);
    }

    public function destroy(Request $request)
    {
        $id = $request->query('id');
        Service::destroy($id);
        return response()->json(['success' => true]);
    }
}
