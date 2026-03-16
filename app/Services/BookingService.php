<?php

namespace App\Services;

use App\Models\Service;
use App\Models\Booking;
use App\Models\Bay;
use App\Models\Holiday;
use App\Models\Availability;
use App\Models\Setting;
use App\Mail\BookingConfirmation;
use Illuminate\Support\Facades\Http;
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

        // 1. Check Date Overrides (Holidays) — only for staff assigned to this service
        $staffIds = $service->staff()->pluck('users.id')->toArray();

        if (!empty($staffIds)) {
            $staffHolidays = Holiday::where('date', $date)
                ->whereIn('staff_id', $staffIds)
                ->get();

            if ($staffHolidays->isNotEmpty()) {
                $closedCount = $staffHolidays->where('is_closed', true)->count();

                // All assigned staff are off — no slots
                if ($closedCount >= count($staffIds)) {
                    return [];
                }
                // Some staff have adjusted hours on this date
                foreach ($staffHolidays as $ov) {
                    if (!$ov->is_closed && $ov->start_time && $ov->end_time) {
                        $allowedIntervals[] = [
                            'start' => Carbon::parse($date . ' ' . $ov->start_time)->timestamp,
                            'end'   => Carbon::parse($date . ' ' . $ov->end_time)->timestamp,
                        ];
                        $isClosedOverride = true;
                    }
                }
            }
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

        // 4. Get Compatible Bays (optional — fall back to service-level concurrency)
        $bayIds = $service->bays()->pluck('bays.id')->toArray();

        if (empty($bayIds)) {
            $bayIds = Bay::where('type', $service->type)->pluck('id')->toArray();
        }

        $useBays   = !empty($bayIds);
        $maxPerDay = $service->max_bookings_per_day ?? null; // null = unlimited

        // 5. Get Existing Bookings
        $startDay = Carbon::parse($date)->startOfDay();
        $endDay   = Carbon::parse($date)->endOfDay();

        $bookingsQuery = Booking::where('service_id', $service->id)
            ->where('status', '!=', 'cancelled')
            ->where(function ($q) use ($startDay, $endDay) {
                $q->whereBetween('start_datetime', [$startDay, $endDay])
                  ->orWhereBetween('end_datetime', [$startDay, $endDay])
                  ->orWhere(function ($q2) use ($startDay, $endDay) {
                      $q2->where('start_datetime', '<=', $startDay)
                         ->where('end_datetime', '>=', $endDay);
                  });
            });

        if ($useBays) {
            $bookingsQuery->whereIn('bay_id', $bayIds);
        }

        $existingBookings = $bookingsQuery->get();

        // 6. Calculate Slots
        $timeIncrement = max(15, (int)($service->time_increment ?? 30));
        $interval      = $timeIncrement * 60;
        $availableSlots = [];

        foreach ($allowedIntervals as $range) {
            $openTime  = $range['start'];
            $closeTime = $range['end'];

            for ($time = $openTime; $time + ($durationMinutes * 60) <= $closeTime; $time += $interval) {
                $slotStart = $time;
                $slotEnd   = $time + ($durationMinutes * 60);

                if ($useBays) {
                    // Bay-based concurrency check
                    $freeBayId = null;
                    foreach ($bayIds as $bayId) {
                        $clashed = false;
                        foreach ($existingBookings as $booking) {
                            if ($booking->bay_id == $bayId) {
                                $bStart = $booking->start_datetime->timestamp;
                                $bEnd   = $booking->end_datetime->timestamp;
                                if ($slotStart < $bEnd && $slotEnd > $bStart) {
                                    $clashed = true;
                                    break;
                                }
                            }
                        }
                        if (!$clashed) {
                            $freeBayId = $bayId;
                            break;
                        }
                    }
                    if ($freeBayId) {
                        $availableSlots[] = [
                            'start'   => date('Y-m-d H:i:s', $slotStart),
                            'display' => date('H:i', $slotStart),
                            'bay_id'  => $freeBayId,
                        ];
                    }
                } else {
                    // No bays — count overlapping bookings for this service
                    $overlapping = 0;
                    foreach ($existingBookings as $booking) {
                        $bStart = $booking->start_datetime->timestamp;
                        $bEnd   = $booking->end_datetime->timestamp;
                        if ($slotStart < $bEnd && $slotEnd > $bStart) {
                            $overlapping++;
                        }
                    }

                    $capacity = $maxPerDay ?? 1; // default: 1 concurrent booking per service
                    if ($overlapping < $capacity) {
                        $availableSlots[] = [
                            'start'   => date('Y-m-d H:i:s', $slotStart),
                            'display' => date('H:i', $slotStart),
                            'bay_id'  => 0, // virtual
                        ];
                    }
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
        DB::beginTransaction();
        try {
            $endDatetime = Carbon::parse($data['start'])->addMinutes($data['duration']);

            $booking = Booking::create([
                'vehicle_reg'    => $data['reg'] ?? null,
                'service_id'     => $data['service_id'],
                'sub_service'    => $data['sub_service'] ?? null,
                'bay_id'         => $data['bay_id'],
                'start_datetime' => $data['start'],
                'end_datetime'   => $endDatetime,
                'customer_name'  => $data['customer']['name'],
                'customer_email' => $data['customer']['email'],
                'customer_phone' => $data['customer']['phone'] ?? null,
                'status'         => 'confirmed',
            ]);

            DB::commit();

            // Load the service relationship for email templates
            $booking->load('service');

            // Send confirmation email to customer (best-effort)
            try {
                Mail::to($booking->customer_email)
                    ->send(new BookingConfirmation($booking, false));
            } catch (\Throwable) {}

            // Send notification email to admin (best-effort)
            try {
                Mail::to('ranatb2023@gmail.com')
                    ->send(new BookingConfirmation($booking, true));
            } catch (\Throwable) {}

            // Add Google Calendar event (best-effort, does not roll back booking on failure)
            $this->addGoogleCalendarEvent($booking, $data);

            return ['success' => true, 'id' => $booking->id];

        } catch (\Exception $e) {
            DB::rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    private function addGoogleCalendarEvent(Booking $booking, array $data): void
    {
        try {
            $calSettings = Setting::where('key', 'google_calendar_settings')->value('value');
            $calSettings = $calSettings ? json_decode($calSettings, true) : [];

            $googleAccounts = Setting::where('key', 'google_accounts')->value('value');
            $googleAccounts = $googleAccounts ? json_decode($googleAccounts, true) : [];

            if (empty($googleAccounts)) {
                return; // No Google account connected at all
            }

            $calendarId   = null;
            $accountEmail = null;

            // 1. Try to find a specific calendar configured for this service
            foreach ($calSettings as $email => $settings) {
                if (isset($settings['service_id']) && (int)$settings['service_id'] === (int)$data['service_id']) {
                    $calendarId   = $settings['google_calendar_id'] ?? 'primary';
                    $accountEmail = $email;
                    break;
                }
            }

            // 2. Fallback: use the first connected Google account with primary calendar
            if (!$accountEmail) {
                $accountEmail = array_key_first($googleAccounts);
                $calendarId   = 'primary';
            }

            if (!isset($googleAccounts[$accountEmail])) {
                return;
            }

            $account = $googleAccounts[$accountEmail];
            $token   = $this->getRefreshedToken($account);

            if (!$token) {
                return;
            }

            // Save refreshed token back if it changed
            if ($token !== ($googleAccounts[$accountEmail]['access_token'] ?? null)) {
                $googleAccounts[$accountEmail]['access_token'] = $token;
                Setting::updateOrCreate(
                    ['key' => 'google_accounts'],
                    ['value' => json_encode($googleAccounts)]
                );
            }

            $service  = Service::find($data['service_id']);
            $svcName  = $service ? $service->name : 'Booking';
            $reg      = !empty($data['reg']) ? $data['reg'] : null;
            $title    = $svcName . ($reg ? ' — ' . strtoupper($reg) : ' — ' . $data['customer']['name']);
            $subLabel = !empty($data['sub_service']) ? "\nOption: {$data['sub_service']}" : '';

            $tz = Setting::where('key', 'timezone')->value('value') ?: config('app.timezone', 'UTC');

            $event = [
                'summary'     => $title,
                'description' => implode("\n", array_filter([
                    "Service: {$svcName}" . (!empty($data['sub_service']) ? " ({$data['sub_service']})" : ''),
                    "Customer: {$data['customer']['name']}",
                    "Email: {$data['customer']['email']}",
                    "Phone: " . ($data['customer']['phone'] ?? '-'),
                    "Reg: " . ($reg ?? '-'),
                ])),
                'start' => ['dateTime' => Carbon::parse($data['start'])->toRfc3339String(), 'timeZone' => $tz],
                'end'   => ['dateTime' => $booking->end_datetime->toRfc3339String(), 'timeZone' => $tz],
            ];

            $response = Http::withToken($token)
                ->post("https://www.googleapis.com/calendar/v3/calendars/" . urlencode($calendarId) . "/events", $event);

            // Store event ID on booking for later deletion
            if ($response->successful()) {
                $eventId = $response->json('id');
                if ($eventId) {
                    $booking->update([
                        'google_event_id'    => $eventId,
                        'google_calendar_id' => $calendarId,
                    ]);
                }
            }

        } catch (\Throwable $e) {
            // Silent fail — booking is already committed
        }
    }

    public function deleteGoogleCalendarEvent(Booking $booking): void
    {
        try {
            if (!$booking->google_event_id) return;

            $googleAccounts = Setting::where('key', 'google_accounts')->value('value');
            $googleAccounts = $googleAccounts ? json_decode($googleAccounts, true) : [];

            if (empty($googleAccounts)) return;

            $accountEmail = array_key_first($googleAccounts);
            $account      = $googleAccounts[$accountEmail];
            $token        = $this->getRefreshedToken($account);

            if (!$token) return;

            $calendarId = $booking->google_calendar_id ?: 'primary';

            Http::withToken($token)
                ->delete("https://www.googleapis.com/calendar/v3/calendars/" . urlencode($calendarId) . "/events/" . urlencode($booking->google_event_id));

        } catch (\Throwable) {
            // Silent fail
        }
    }

    private function getRefreshedToken(array &$account): ?string
    {
        $accessToken  = $account['access_token'];
        $expiresAt    = $account['expires_at'] ?? 0;
        $refreshToken = $account['refresh_token'] ?? null;

        if (time() <= ($expiresAt - 60)) {
            return $accessToken;
        }

        if (!$refreshToken) {
            return null;
        }

        $config = config('services.google');
        $res = Http::post('https://oauth2.googleapis.com/token', [
            'client_id'     => $config['client_id'],
            'client_secret' => $config['client_secret'],
            'refresh_token' => $refreshToken,
            'grant_type'    => 'refresh_token',
        ]);

        if ($res->successful()) {
            $account['access_token'] = $res->json()['access_token'];
            $account['expires_at']   = time() + $res->json()['expires_in'];
            return $account['access_token'];
        }

        return null;
    }
}
