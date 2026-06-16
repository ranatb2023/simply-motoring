<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Service;
use App\Services\BookingService;
use Illuminate\Http\Request;

class BookingManageController extends Controller
{
    public function __construct(private BookingService $bookingService) {}

    private function findOrFail(string $token): Booking
    {
        return Booking::where('edit_token', $token)->firstOrFail();
    }

    /**
     * Public "manage my booking" page (pre-filled editor).
     */
    public function edit(string $token)
    {
        $booking = $this->findOrFail($token);
        $booking->load('service');

        $services = Service::where('is_active', true)
            ->get(['id', 'name', 'duration_minutes', 'price', 'type', 'options_label', 'options'])
            ->map(fn ($s) => [
                'id'               => $s->id,
                'name'             => $s->name,
                'duration_minutes' => $s->duration_minutes,
                'price'            => $s->price,
                'options_label'    => $s->options_label,
                'options'          => is_array($s->options) ? $s->options : null,
            ]);

        return view('booking-manage', compact('booking', 'services'));
    }

    /**
     * Available slots for the edit page (ignores the current booking so its own
     * slot shows as available).
     */
    public function slots(string $token, Request $request)
    {
        $booking = $this->findOrFail($token);
        $request->validate([
            'date'       => 'required|date',
            'service_id' => 'required|exists:services,id',
        ]);

        return response()->json(
            $this->bookingService->getSlots($request->date, $request->service_id, $booking->id)
        );
    }

    /**
     * Save changes to the booking.
     */
    public function update(string $token, Request $request)
    {
        $booking = $this->findOrFail($token);

        if ($booking->status === 'cancelled') {
            return response()->json(['success' => false, 'message' => 'This booking has been cancelled and can no longer be edited.'], 422);
        }

        $request->validate([
            'service_id'     => 'required|exists:services,id',
            'start'          => 'required|date',
            'customer_name'  => 'required|string|max:100',
            'customer_email' => 'required|email|max:150',
            'customer_phone' => 'nullable|string|max:30',
            'vehicle_reg'    => 'nullable|string|max:20',
            'sub_service'    => 'nullable|string|max:100',
        ]);

        $result = $this->bookingService->updateBooking($booking, [
            'service_id'  => $request->service_id,
            'start'       => $request->start,
            'reg'         => $request->vehicle_reg,
            'sub_service' => $request->sub_service,
            'customer'    => [
                'name'  => $request->customer_name,
                'email' => $request->customer_email,
                'phone' => $request->customer_phone,
            ],
        ]);

        return response()->json($result, $result['success'] ? 200 : 422);
    }

    /**
     * Cancel the booking.
     */
    public function cancel(string $token)
    {
        $booking = $this->findOrFail($token);

        if ($booking->status !== 'cancelled') {
            $this->bookingService->cancelBooking($booking);
        }

        return response()->json(['success' => true]);
    }
}
