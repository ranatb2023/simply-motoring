<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Services\BookingService;

class BookingApiController extends Controller
{
    protected $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function getServices()
    {
        return response()->json(Service::all());
    }

    public function getSlots(Request $request)
    {
        try {
            $slots = $this->bookingService->getSlots($request->date, $request->service_id);
            return response()->json($slots);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function book(Request $request)
    {
        $result = $this->bookingService->createBooking($request->all());
        return response()->json($result);
    }
}
