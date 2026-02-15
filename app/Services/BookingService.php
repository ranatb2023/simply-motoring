<?php

namespace App\Services;

use App\Models\Service;
use App\Models\Booking;
use App\Models\Bay;
use App\Models\Holiday;
use App\Models\Availability;
use App\Models\Staff;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookingService
{
    /**
     * Replicating gbs_get_slots logic
     */
    public function getSlots($date, $serviceId)
    {
        if (!$date || !$serviceId) {
            throw new \Exception('Date and Service ID required');
        }

        $service = Service::find($serviceId);
        if (!$service)
            return [];

        $durationMinutes = $service->duration_minutes;
        $allowedIntervals = [];
        $isClosedOverride = false;

        // 1. Check Date Overrides (Holidays)
        $overrides = Holiday::where('date', $date)->get();
        if ($overrides->isNotEmpty()) {
            $hasOpenSlots = false;
            foreach ($overrides as $ov) {
                if (!$ov->is_closed && $ov->start_time && $ov->end_time) {
                    $allowedIntervals[] = [
                        'start' => Carbon::parse($date . ' ' . $ov->start_time, 'UTC')->timestamp, // Assuming UTC for calculation consistency or app timezone
                        'end' => Carbon::parse($date . ' ' . $ov->end_time, 'UTC')->timestamp
                    ];
                    $hasOpenSlots = true;
                }
            }

            if (!$hasOpenSlots) {
                return []; // Closed by override
            }
            $isClosedOverride = true;
        }

        // 3. Get Weekly Opening Hours (if not overridden)
        if (!$isClosedOverride) {
            $dayOfWeek = Carbon::parse($date)->dayOfWeek; // 0 (Sunday) to 6 (Saturday) in Carbon/PHP? Wait, PHP date('w') is 0=Sun. Carbon dayOfWeek is 0=Sun.
            // Check Availability model for schedule
            $scheduleId = $service->schedule_id;

            if (!$scheduleId)
                return []; // Auto closed if no schedule

            $rules = Availability::where('day_of_week', $dayOfWeek)
                ->where('schedule_id', $scheduleId)
                ->get();

            if ($rules->isEmpty())
                return []; // No rules -> Closed

            foreach ($rules as $rule) {
                if (!$rule->is_closed && $rule->start_time && $rule->end_time) {
                    $allowedIntervals[] = [
                        'start' => Carbon::parse($date . ' ' . $rule->start_time)->timestamp,
                        'end' => Carbon::parse($date . ' ' . $rule->end_time)->timestamp
                    ];
                }
            }

            if (empty($allowedIntervals))
                return [];
        }

        // 4. Get Compatible Bays
        // A. Explicit assignments via pivot
        $bayIds = $service->bays()->pluck('bays.id')->toArray();

        if (empty($bayIds)) {
            // Fallback: Type matching
            $bayIds = Bay::where('type', $service->type)->pluck('id')->toArray();
        }

        if (empty($bayIds))
            return [];

        // 5. Get Existing Bookings
        $startDay = Carbon::parse($date)->startOfDay();
        $endDay = Carbon::parse($date)->endOfDay();

        $existingBookings = Booking::whereIn('bay_id', $bayIds)
            ->where(function ($query) use ($startDay, $endDay) {
                $query->whereBetween('start_datetime', [$startDay, $endDay])
                    ->orWhereBetween('end_datetime', [$startDay, $endDay])
                    ->orWhere(function ($q) use ($startDay, $endDay) {
                        $q->where('start_datetime', '<=', $startDay)
                            ->where('end_datetime', '>=', $endDay);
                    });
            })->get();

        // 6. Calculate Slots
        $interval = 30 * 60; // 30 mins
        $availableSlots = [];

        foreach ($allowedIntervals as $range) {
            $openTime = $range['start'];
            $closeTime = $range['end'];

            for ($time = $openTime; $time + ($durationMinutes * 60) <= $closeTime; $time += $interval) {
                $slotStart = $time;
                $slotEnd = $time + ($durationMinutes * 60);
                $freeBayId = null;

                foreach ($bayIds as $bayId) {
                    $isClashed = false;
                    foreach ($existingBookings as $booking) {
                        if ($booking->bay_id == $bayId) {
                            $bStart = $booking->start_datetime->timestamp;
                            $bEnd = $booking->end_datetime->timestamp;

                            if ($slotStart < $bEnd && $slotEnd > $bStart) {
                                $isClashed = true;
                                break;
                            }
                        }
                    }
                    if (!$isClashed) {
                        $freeBayId = $bayId;
                        break;
                    }
                }

                if ($freeBayId) {
                    // Logic to dedup times if multiple bays available?
                    // The frontend.php implementation adds them, then dedups by display time at the end.
                    $availableSlots[] = [
                        'start' => date('Y-m-d H:i:s', $slotStart),
                        'display' => date('H:i', $slotStart), // 24H format
                        'bay_id' => $freeBayId
                    ];
                }
            }
        }

        // Dedup by time
        $uniqueSlots = [];
        foreach ($availableSlots as $slot) {
            $t = $slot['display'];
            if (!isset($uniqueSlots[$t])) {
                $uniqueSlots[$t] = $slot;
            }
        }

        $finalSlots = array_values($uniqueSlots);
        usort($finalSlots, function ($a, $b) {
            return strcmp($a['start'], $b['start']);
        });

        return $finalSlots;
    }

    public function createBooking($data)
    {
        // Re-check availability (simple check)
        // Ideally we'd lock or re-run specific slot check, but for MVP/Conversion we can proceed.

        DB::beginTransaction();
        try {
            $booking = Booking::create([
                'vehicle_reg' => $data['reg'],
                'service_id' => $data['service_id'],
                'bay_id' => $data['bay_id'],
                'start_datetime' => $data['start'],
                'end_datetime' => Carbon::parse($data['start'])->addMinutes($data['duration']),
                'customer_name' => $data['customer']['name'],
                'customer_email' => $data['customer']['email'],
                'customer_phone' => $data['customer']['phone'],
                'status' => 'confirmed'
            ]);

            // Send Email (Laravel Mailable would be better, using raw mail for direct port)
            // Mail::raw("Hi " . $data['customer']['name'] . ",\n\nYour booking for " . $data['reg'] . " is confirmed for " . $data['start'] . ".", function ($message) use ($data) {
            //     $message->to($data['customer']['email'])
            //             ->subject('Booking Confirmation');
            // });

            // Google Sync would go here.

            DB::commit();
            return ['success' => true, 'id' => $booking->id];

        } catch (\Exception $e) {
            DB::rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
