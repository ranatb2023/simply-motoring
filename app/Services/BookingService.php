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
        
        $tz = Setting::where('key', 'timezone')->value('value') ?: config('app.timezone', 'UTC');
        $localNow = Carbon::now($tz);
        $floatingNow = Carbon::createFromFormat('Y-m-d H:i:s', $localNow->format('Y-m-d H:i:s'), config('app.timezone'));
        
        $minNoticeHours = $service->min_notice_hours ?? 4;
        $minTime = $floatingNow->addHours($minNoticeHours)->timestamp;

        // Google Calendar busy times — hide slots that clash with events added directly in Google Calendar
        $googleBusy = $this->getGoogleBusyIntervals($date, $tz);

        foreach ($allowedIntervals as $range) {
            $openTime  = $range['start'];
            $closeTime = $range['end'];

            for ($time = $openTime; $time + ($durationMinutes * 60) <= $closeTime; $time += $interval) {
                $slotStart = $time;
                $slotEnd   = $time + ($durationMinutes * 60);

                if ($slotStart < $minTime) {
                    continue;
                }

                // Skip slots that overlap a busy period in any connected Google Calendar
                $clashesGoogle = false;
                foreach ($googleBusy as $b) {
                    if ($slotStart < $b['end'] && $slotEnd > $b['start']) {
                        $clashesGoogle = true;
                        break;
                    }
                }
                if ($clashesGoogle) {
                    continue;
                }

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

    /**
     * Fetch busy intervals from all connected Google Calendars for a given date.
     * Returns an array of ['start' => timestamp, 'end' => timestamp] in the same
     * time frame the slots are computed in, so they can be compared directly.
     * Best-effort: never throws — returns [] on any failure so booking slots still work.
     */
    private function getGoogleBusyIntervals(string $date, string $tz): array
    {
        try {
            $accounts = Setting::where('key', 'google_accounts')->value('value');
            $accounts = $accounts ? json_decode($accounts, true) : [];

            if (empty($accounts)) {
                return []; // No Google account connected — nothing to check
            }

            // Business-day window in the business timezone, as absolute RFC3339 instants
            $timeMin = Carbon::createFromFormat('Y-m-d H:i:s', $date . ' 00:00:00', $tz)->toRfc3339String();
            $timeMax = Carbon::createFromFormat('Y-m-d H:i:s', $date . ' 23:59:59', $tz)->toRfc3339String();

            $busy = [];

            foreach ($accounts as $email => $acc) {
                $token = $this->getRefreshedToken($acc); // updates $acc by value; persisted below if changed

                if (!$token) {
                    \Log::warning('GCal conflict-check: no valid token', ['account' => $email]);
                    continue;
                }

                // Persist refreshed token if it changed
                if (($accounts[$email]['access_token'] ?? null) !== $token) {
                    $accounts[$email]['access_token'] = $acc['access_token'];
                    $accounts[$email]['expires_at']   = $acc['expires_at'] ?? ($accounts[$email]['expires_at'] ?? 0);
                    Setting::updateOrCreate(['key' => 'google_accounts'], ['value' => json_encode($accounts)]);
                }

                // List this account's calendars (cached briefly to avoid an extra call on every request)
                $calIds = \Cache::remember("gcal_ids_{$email}", 600, function () use ($token) {
                    $listRes = Http::withToken($token)
                        ->get('https://www.googleapis.com/calendar/v3/users/me/calendarList');
                    if (!$listRes->successful()) {
                        return [];
                    }
                    return collect($listRes->json()['items'] ?? [])
                        ->pluck('id')->filter()->values()->all();
                });

                if (empty($calIds)) {
                    continue;
                }

                $fbRes = Http::withToken($token)->post('https://www.googleapis.com/calendar/v3/freeBusy', [
                    'timeMin' => $timeMin,
                    'timeMax' => $timeMax,
                    'items'   => array_map(fn ($id) => ['id' => $id], $calIds),
                ]);

                if (!$fbRes->successful()) {
                    \Log::warning('GCal conflict-check: freeBusy failed', [
                        'account' => $email, 'status' => $fbRes->status(), 'body' => $fbRes->body(),
                    ]);
                    continue;
                }

                foreach (($fbRes->json()['calendars'] ?? []) as $cal) {
                    foreach (($cal['busy'] ?? []) as $b) {
                        // Convert Google's absolute time to business-tz wall clock, then re-interpret
                        // in the app's default tz — mirroring how slot timestamps are built above.
                        $startWall = Carbon::parse($b['start'])->setTimezone($tz)->format('Y-m-d H:i:s');
                        $endWall   = Carbon::parse($b['end'])->setTimezone($tz)->format('Y-m-d H:i:s');
                        $busy[] = [
                            'start' => Carbon::createFromFormat('Y-m-d H:i:s', $startWall, config('app.timezone'))->timestamp,
                            'end'   => Carbon::createFromFormat('Y-m-d H:i:s', $endWall, config('app.timezone'))->timestamp,
                        ];
                    }
                }
            }

            return $busy;

        } catch (\Throwable $e) {
            \Log::error('GCal conflict-check threw an exception', ['error' => $e->getMessage()]);
            return []; // Best-effort: don't break the booking form
        }
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
            } catch (\Throwable $e) {
                \Log::error('Booking confirmation email (customer) failed', [
                    'booking_id' => $booking->id,
                    'to'         => $booking->customer_email,
                    'error'      => $e->getMessage(),
                ]);
            }

            // Send notification email to admin (best-effort)
            try {
                $adminEmail = env('MAIL_ADMIN_ADDRESS', 'ranatb2023@gmail.com');
                Mail::to($adminEmail)
                    ->send(new BookingConfirmation($booking, true));
            } catch (\Throwable $e) {
                \Log::error('Booking confirmation email (admin) failed', [
                    'booking_id' => $booking->id,
                    'error'      => $e->getMessage(),
                ]);
            }

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
                \Log::warning('GCal sync skipped: no Google account connected', ['booking_id' => $booking->id]);
                return; // No Google account connected at all
            }

            $calendarId   = null;
            $accountEmail = null;

            // 1. Per-service calendar mapping (keyed by service_id)
            $svcKey  = (string) ($data['service_id'] ?? '');
            $mapping = $calSettings[$svcKey] ?? null;

            if ($mapping !== null) {
                // Service explicitly mapped to "no calendar" — skip sync for it
                if (empty($mapping['google_calendar_id'])) {
                    \Log::info('GCal sync skipped: service mapped to no calendar', [
                        'booking_id' => $booking->id, 'service_id' => $svcKey,
                    ]);
                    return;
                }
                $calendarId   = $mapping['google_calendar_id'];
                $accountEmail = $mapping['account_email'] ?? array_key_first($googleAccounts);
            } else {
                // 2. Fallback: first connected account, primary calendar
                $accountEmail = array_key_first($googleAccounts);
                $calendarId   = 'primary';
            }

            if (!isset($googleAccounts[$accountEmail])) {
                \Log::warning('GCal sync skipped: configured account not found', [
                    'booking_id' => $booking->id, 'account' => $accountEmail,
                ]);
                return;
            }

            $account = $googleAccounts[$accountEmail];
            $token   = $this->getRefreshedToken($account);

            if (!$token) {
                \Log::error('GCal sync failed: could not obtain/refresh access token (refresh token may be missing, expired, or revoked)', [
                    'booking_id'    => $booking->id,
                    'account'       => $accountEmail,
                    'has_refresh'   => !empty($account['refresh_token']),
                    'expires_at'    => $account['expires_at'] ?? null,
                ]);
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
                // Parse the booking's wall-clock time AS the business timezone so the
                // RFC3339 offset is correct (otherwise it's read as UTC and lands an hour off).
                'start' => ['dateTime' => Carbon::parse($data['start'], $tz)->toRfc3339String(), 'timeZone' => $tz],
                'end'   => ['dateTime' => Carbon::parse($booking->end_datetime->format('Y-m-d H:i:s'), $tz)->toRfc3339String(), 'timeZone' => $tz],
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
                    \Log::info('GCal event created', [
                        'booking_id' => $booking->id, 'event_id' => $eventId,
                        'account' => $accountEmail, 'calendar' => $calendarId,
                    ]);
                }
            } else {
                \Log::error('GCal sync failed: Google API rejected the event', [
                    'booking_id' => $booking->id,
                    'account'    => $accountEmail,
                    'calendar'   => $calendarId,
                    'status'     => $response->status(),
                    'body'       => $response->body(),
                ]);
            }

        } catch (\Throwable $e) {
            \Log::error('GCal sync threw an exception', [
                'booking_id' => $booking->id ?? null,
                'error'      => $e->getMessage(),
            ]);
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

    /**
     * Reconcile bookings with Google Calendar: if an event was deleted or
     * cancelled directly in Google, cancel the matching booking so its slot
     * becomes bookable again. Returns ['checked' => n, 'cancelled' => n].
     */
    public function syncDeletedGoogleEvents(): array
    {
        $checked = 0;
        $cancelled = 0;

        $googleAccounts = Setting::where('key', 'google_accounts')->value('value');
        $googleAccounts = $googleAccounts ? json_decode($googleAccounts, true) : [];

        if (empty($googleAccounts)) {
            return ['checked' => 0, 'cancelled' => 0];
        }

        $accountEmail = array_key_first($googleAccounts);
        $account      = $googleAccounts[$accountEmail];
        $oldToken     = $account['access_token'] ?? null;
        $token        = $this->getRefreshedToken($account); // updates $account by ref

        if (!$token) {
            \Log::warning('Calendar delete-sync: no valid token', ['account' => $accountEmail]);
            return ['checked' => 0, 'cancelled' => 0];
        }

        // Persist refreshed token if it changed
        if (($account['access_token'] ?? null) !== $oldToken) {
            $googleAccounts[$accountEmail] = $account;
            Setting::updateOrCreate(['key' => 'google_accounts'], ['value' => json_encode($googleAccounts)]);
        }

        // Only check upcoming, still-active bookings that were pushed to a calendar
        $bookings = Booking::whereNotNull('google_event_id')
            ->where('status', '!=', 'cancelled')
            ->where('end_datetime', '>=', Carbon::now()->subDay())
            ->get();

        foreach ($bookings as $booking) {
            $checked++;
            $calendarId = $booking->google_calendar_id ?: 'primary';

            try {
                $resp = Http::withToken($token)->get(
                    'https://www.googleapis.com/calendar/v3/calendars/' . urlencode($calendarId)
                    . '/events/' . urlencode($booking->google_event_id)
                );

                $deleted = false;
                if (in_array($resp->status(), [404, 410], true)) {
                    $deleted = true; // event no longer exists
                } elseif ($resp->successful() && $resp->json('status') === 'cancelled') {
                    $deleted = true; // event cancelled in Google
                }

                if ($deleted) {
                    $booking->update(['status' => 'cancelled']);
                    $cancelled++;
                    \Log::info('Calendar delete-sync: booking cancelled (event removed in Google)', [
                        'booking_id' => $booking->id, 'event_id' => $booking->google_event_id,
                    ]);
                }
            } catch (\Throwable $e) {
                \Log::error('Calendar delete-sync: check failed', [
                    'booking_id' => $booking->id, 'error' => $e->getMessage(),
                ]);
            }
        }

        return ['checked' => $checked, 'cancelled' => $cancelled];
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
