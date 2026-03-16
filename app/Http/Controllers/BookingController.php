<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Holiday;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function __construct(private BookingService $bookingService) {}

    /**
     * Return active services for the booking modal.
     */
    public function services()
    {
        $services = Service::where('is_active', true)
            ->with('schedule.availabilities')
            ->get(['id', 'name', 'duration_minutes', 'price', 'type',
                   'collect_phone', 'collect_vehicle_reg',
                   'schedule_id', 'min_notice_hours', 'advance_booking_days', 'time_increment']);

        $result = $services->map(function ($service) {
            // Collect day_of_week values that are closed (or have no rule = closed)
            $closedDays = [];
            if ($service->schedule) {
                $rules = $service->schedule->availabilities->keyBy('day_of_week');
                for ($d = 0; $d <= 6; $d++) {
                    $rule = $rules->get($d);
                    if (!$rule || $rule->is_closed) {
                        $closedDays[] = $d; // 0=Sunday ... 6=Saturday (Carbon/JS convention)
                    }
                }
            }
            return [
                'id'                  => $service->id,
                'name'                => $service->name,
                'duration_minutes'    => $service->duration_minutes,
                'price'               => $service->price,
                'type'                => $service->type,
                'collect_phone'       => $service->collect_phone,
                'collect_vehicle_reg' => $service->collect_vehicle_reg,
                'min_notice_hours'    => $service->min_notice_hours ?? 4,
                'advance_booking_days'=> $service->advance_booking_days ?? 60,
                'time_increment'      => $service->time_increment ?? 30,
                'closed_days'         => $closedDays, // JS getDay() values: 0=Sun,6=Sat
            ];
        });

        return response()->json($result);
    }

    /**
     * Return specific dates that are fully blocked (holidays / staff days off)
     * within the service's advance booking window.
     * A date is blocked when EVERY staff member assigned to the service has is_closed=true on that date.
     */
    public function closedDates(Request $request)
    {
        $request->validate(['service_id' => 'required|exists:services,id']);

        $service  = Service::findOrFail($request->service_id);
        $staffIds = $service->staff()->pluck('users.id')->toArray();

        $from = Carbon::today();
        $to   = Carbon::today()->addDays($service->advance_booking_days ?? 60);

        // All closed holidays within the window
        $holidays = Holiday::where('is_closed', true)
            ->whereBetween('date', [$from->toDateString(), $to->toDateString()])
            ->get(['staff_id', 'date']);

        $blockedDates = [];

        if (empty($staffIds)) {
            // No staff assigned — no staff-specific dates to block
            return response()->json([]);
        } else {
            // Group closed holidays by date, count how many of the service's staff are off
            $offByDate = [];
            foreach ($holidays as $h) {
                if (in_array($h->staff_id, $staffIds)) {
                    $d = Carbon::parse($h->date)->toDateString();
                    $offByDate[$d] = ($offByDate[$d] ?? 0) + 1;
                }
            }
            $total = count($staffIds);
            foreach ($offByDate as $date => $count) {
                if ($count >= $total) {
                    $blockedDates[] = $date;
                }
            }
        }

        return response()->json(array_values(array_unique($blockedDates)));
    }

    /**
     * Return available slots for a given date + service.
     */
    public function slots(Request $request)
    {
        $request->validate([
            'date'       => 'required|date',
            'service_id' => 'required|exists:services,id',
        ]);

        $slots = $this->bookingService->getSlots($request->date, $request->service_id);

        return response()->json($slots);
    }

    /**
     * Store a new booking from the public booking modal.
     */
    public function store(Request $request)
    {
        $request->validate([
            'service_id'    => 'required|exists:services,id',
            'start'         => 'required|date',
            'bay_id'        => 'nullable|integer',
            'customer_name' => 'required|string|max:100',
            'customer_email'=> 'required|email|max:150',
            'customer_phone'=> 'nullable|string|max:30',
            'vehicle_reg'   => 'nullable|string|max:20',
            'sub_service'   => 'nullable|string|max:100',
        ]);

        $service = Service::findOrFail($request->service_id);

        $result = $this->bookingService->createBooking([
            'service_id'  => $request->service_id,
            'bay_id'      => $request->bay_id,
            'start'       => $request->start,
            'duration'    => $service->duration_minutes,
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
}