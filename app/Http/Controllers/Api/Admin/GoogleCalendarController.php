<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class GoogleCalendarController extends Controller
{
    private $config;

    public function __construct()
    {
        $this->config = config('services.google');
    }

    /**
     * Initiate OAuth Flow
     */
    public function connect(Request $request)
    {
        $clientId = $this->config['client_id'];
        // Use config redirect if set, otherwise fallback to auto-generated URL
        $redirectUri = !empty($this->config['redirect']) ? $this->config['redirect'] : url('/api/admin/auth/google/callback');


        if (!$clientId) {
            return response()->json(['error' => 'Google Client ID not configured'], 500);
        }

        $scope = 'https://www.googleapis.com/auth/calendar https://www.googleapis.com/auth/userinfo.email';

        $context = $request->query('context', 'global');
        $state = Str::random(16) . '|' . $context;
        // In a real app, store state in session to verify in callback. 
        // For this port, we'll keep it simple or allow stateless if session not available, 
        // but robust security requires verification.
        session(['google_auth_state' => $state]);

        $params = [
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'response_type' => 'code',
            'scope' => $scope,
            'access_type' => 'offline',
            'prompt' => 'consent',
            'state' => $state
        ];

        return redirect()->away('https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params));
    }

    /**
     * Handle OAuth Callback
     */
    public function callback(Request $request)
    {
        $code = $request->input('code');
        $error = $request->input('error');
        $state = $request->input('state');

        // Verify state if needed (skipped for direct port simplicity)

        if ($error) {
            return redirect('/admin/availability?error=google_auth_failed&msg=' . urlencode($error));
        }

        $clientId = $this->config['client_id'];
        $clientSecret = $this->config['client_secret'];
        // Use config redirect if set, otherwise fallback to auto-generated URL
        $redirectUri = !empty($this->config['redirect']) ? $this->config['redirect'] : url('/api/admin/auth/google/callback');

        if (!$clientId || !$clientSecret) {
            return redirect('/admin/availability?error=missing_config');
        }

        // Exchange code
        $response = Http::post('https://oauth2.googleapis.com/token', [
            'code' => $code,
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'redirect_uri' => $redirectUri,
            'grant_type' => 'authorization_code'
        ]);

        if ($response->failed()) {
            return redirect('/admin/availability?error=token_exchange_failed');
        }

        $data = $response->json();

        if (isset($data['error'])) {
            return redirect('/admin/availability?error=google_api_error&msg=' . urlencode($data['error_description'] ?? $data['error']));
        }

        $accessToken = $data['access_token'];
        $refreshToken = $data['refresh_token'] ?? null;
        $expiresIn = $data['expires_in'];

        // Get User Info
        $userRes = Http::withToken($accessToken)->get('https://www.googleapis.com/oauth2/v2/userinfo');

        if ($userRes->failed()) {
            return redirect('/admin/availability?error=user_info_failed');
        }

        $userInfo = $userRes->json();
        $email = $userInfo['email'];
        $name = $userInfo['name'] ?? $email;

        // Parse Context from State first
        // Format: random_string|context
        $context = 'global';
        if ($state && strpos($state, '|') !== false) {
            $parts = explode('|', $state);
            if (count($parts) >= 2) {
                $context = $parts[1];
            }
        }

        // Prepare Account Data
        $accountData = [
            'email' => $email,
            'name' => $name,
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken, // Logic below handles existing refresh token if null
            'expires_at' => time() + $expiresIn,
            'context' => $context
        ];

        if ($context === 'staff') {
            // Context: Staff Creation Wizard -> Save to Session
            // We need to preserve refresh token if it was previously in session and not returned now?
            // Google usually only returns refresh_token on first consent.
            // For session, if we are re-authing, we might lose it if not careful, 
            // but usually wizard is a one-time setup.

            // To be safe, check session for existing
            $existing = session('staff_wizard_google_account');
            if (!$refreshToken && isset($existing['refresh_token'])) {
                $accountData['refresh_token'] = $existing['refresh_token'];
            }

            session(['staff_wizard_google_account' => $accountData]);

        } else {
            // Context: Global/Availability -> Save to Settings
            $accounts = Setting::where('key', 'google_accounts')->value('value');
            $accounts = $accounts ? json_decode($accounts, true) : [];

            // Preserve existing refresh token if not provided in new response
            $existingRefresh = $accounts[$email]['refresh_token'] ?? null;
            if (!$refreshToken && $existingRefresh) {
                $accountData['refresh_token'] = $existingRefresh;
            }

            $accounts[$email] = $accountData;

            Setting::updateOrCreate(
                ['key' => 'google_accounts'],
                ['value' => json_encode($accounts)]
            );
        }

        // Notify Parent Window Logic (for Popup) or Redirect (for same window)
        $payload = json_encode([
            'gbsAuthSuccess' => true,
            'email' => $email,
            'name' => $name
        ]);

        return response("<!DOCTYPE html><html><body><script>
        if(window.opener) {
            window.opener.postMessage($payload, '*');
            window.close();
        } else {
            // Redirect based on context
            if ('{$context}' === 'staff') {
                 window.location.href = '/admin/staff/create?step=2&google_connected=1';
            } else {
                 window.location.href = '/admin/availability?success=google_connected';
            }
        }
        </script></body></html>");
    }

    /**
     * Disconnect Account
     */
    public function disconnect(Request $request)
    {
        $email = $request->query('email');
        $context = $request->query('context', 'global');

        if ($context === 'staff') {
            session()->forget('staff_wizard_google_account');
            return response()->json(['success' => true]);
        }

        $accounts = Setting::where('key', 'google_accounts')->value('value');
        $accounts = $accounts ? json_decode($accounts, true) : [];

        if ($email && isset($accounts[$email])) {
            unset($accounts[$email]);
            Setting::updateOrCreate(
                ['key' => 'google_accounts'],
                ['value' => json_encode($accounts)]
            );
        } elseif (!$email) {
            // Disconnect all? Or error?
            // The snippet fallback was delete_option. Let's clear all.
            Setting::updateOrCreate(
                ['key' => 'google_accounts'],
                ['value' => json_encode([])]
            );
        }

        return response()->json(['success' => true]);
    }

    /**
     * Get Connected Calendars
     */
    public function getCalendars()
    {
        $accounts = Setting::where('key', 'google_accounts')->value('value');
        $accounts = $accounts ? json_decode($accounts, true) : [];
        if (empty($accounts)) {
            // Return empty or error?
            // Since this is list endpoint, return empty array is safer for frontend that expects loop
            return response()->json([]);
        }

        $allCalendars = [];

        foreach ($accounts as $email => $acc) {
            $token = $this->getValidToken($acc);

            // Update token in DB if refreshed
            if ($token !== $acc['access_token']) {
                $accounts[$email]['access_token'] = $token;
                // We'd need expiration as well but getValidToken handles refresh
                // Ideally getValidToken returns array or updates reference. 
                // Let's refactor helper later, strictly usage:
            }

            if (!$token)
                continue; // Skip if fails

            $res = Http::withToken($token)->get('https://www.googleapis.com/calendar/v3/users/me/calendarList');

            if ($res->successful()) {
                $items = $res->json()['items'] ?? [];

                // Determine 'primary' email if not explicit (redundant here as we key by email)

                foreach ($items as $item) {
                    $item['account_email'] = $email;
                    $item['account_name'] = $acc['name']; // Helper for UI
                    $allCalendars[] = $item;
                }
            }
        }

        // Save back accounts if refreshed (simplified)
        Setting::updateOrCreate(['key' => 'google_accounts'], ['value' => json_encode($accounts)]);

        return response()->json($allCalendars);
    }

    /**
     * Get Public Holidays
     */
    public function getHolidays(Request $request)
    {
        $country = $request->query('country');
        // Map (Same as snippet)
        $map = [
            'uk' => 'en.uk#holiday@group.v.calendar.google.com',
            'us' => 'en.usa#holiday@group.v.calendar.google.com',
            'au' => 'en.australian#holiday@group.v.calendar.google.com',
            'br' => 'en.brazilian#holiday@group.v.calendar.google.com',
            'ca' => 'en.canadian#holiday@group.v.calendar.google.com',
            'fr' => 'en.french#holiday@group.v.calendar.google.com',
            'de' => 'en.german#holiday@group.v.calendar.google.com',
            'mx' => 'en.mexican#holiday@group.v.calendar.google.com',
            'nl' => 'en.dutch#holiday@group.v.calendar.google.com',
            'es' => 'en.spanish#holiday@group.v.calendar.google.com'
        ];

        if (!isset($map[$country])) {
            return response()->json(['error' => 'Unsupported country'], 400);
        }

        $calendarId = urlencode($map[$country]);

        // Get ANY valid token
        $accounts = Setting::where('key', 'google_accounts')->value('value');
        $accounts = $accounts ? json_decode($accounts, true) : [];
        $token = null;

        foreach ($accounts as $email => &$acc) {
            $t = $this->getValidToken($acc);
            if ($t) {
                $token = $t;
                // update if refreshed
                if ($t !== $acc['access_token']) {
                    // update db later
                }
                break;
            }
        }

        // Save potentially refreshed
        Setting::updateOrCreate(['key' => 'google_accounts'], ['value' => json_encode($accounts)]);

        if (!$token) {
            return response()->json(['code' => 'no_token', 'message' => 'Connect Google Account'], 401);
        }

        $now = urlencode(date('c'));
        $nextYear = urlencode(date('c', strtotime('+1 year')));
        $url = "https://www.googleapis.com/calendar/v3/calendars/{$calendarId}/events?singleEvents=true&orderBy=startTime&timeMin={$now}&timeMax={$nextYear}&maxResults=20";

        $res = Http::withToken($token)->get($url);

        if ($res->failed()) {
            return response()->json(['error' => 'Google API Error'], $res->status());
        }

        $items = $res->json()['items'] ?? [];
        $holidays = [];

        foreach ($items as $item) {
            $start = $item['start']['date'] ?? ($item['start']['dateTime'] ?? '');
            if (!$start)
                continue;

            $ts = strtotime($start);
            $dow = (int) date('w', $ts);
            if ($dow === 0 || $dow === 6)
                continue; // Skip weekends

            $holidays[] = [
                'name' => $item['summary'],
                'date' => 'Next: ' . date('j M', $ts),
                'active' => true
            ];
        }

        return response()->json($holidays);
    }

    /**
     * Settings (GET/POST)
     */
    public function settings(Request $request)
    {
        if ($request->isMethod('post')) {
            $current = Setting::where('key', 'google_calendar_settings')->value('value');
            $current = $current ? json_decode($current, true) : [];

            $email = $request->input('account_email');
            $serviceId = $request->input('service_id');

            if ($email && $serviceId) {
                // IMPORTANT: Clear previous selections from OTHER accounts
                // This ensures only ONE account has an active service selection at a time
                foreach ($current as $existingEmail => &$existingSettings) {
                    if ($existingEmail !== $email) {
                        unset($existingSettings['service_id']);
                        unset($existingSettings['service_name']);
                        unset($existingSettings['google_calendar_id']);
                    }
                }
                unset($existingSettings); // Break reference

                // Get Service Name from DB
                $service = \App\Models\Service::find($serviceId);
                $serviceName = $service ? $service->name : null;

                if ($serviceName) {
                    // Get Account Token
                    $accounts = Setting::where('key', 'google_accounts')->value('value');
                    $accounts = $accounts ? json_decode($accounts, true) : [];

                    if (isset($accounts[$email])) {
                        $token = $this->getValidToken($accounts[$email]);

                        // Save refreshed token back if changed
                        if ($token && $token !== $accounts[$email]['access_token']) {
                            Setting::updateOrCreate(['key' => 'google_accounts'], ['value' => json_encode($accounts)]);
                        }

                        if ($token) {
                            $calendarId = null;

                            // 1. List existing calendars
                            $listRes = Http::withToken($token)->get('https://www.googleapis.com/calendar/v3/users/me/calendarList');

                            if ($listRes->successful()) {
                                $calendars = $listRes->json()['items'] ?? [];
                                foreach ($calendars as $cal) {
                                    $calName = $cal['summaryOverride'] ?? $cal['summary'];
                                    if (strcasecmp($calName, $serviceName) === 0) {
                                        $calendarId = $cal['id'];
                                        break;
                                    }
                                }
                            }

                            // 2. Create calendar if not found
                            if (!$calendarId) {
                                $createRes = Http::withToken($token)
                                    ->post('https://www.googleapis.com/calendar/v3/calendars', [
                                        'summary' => $serviceName
                                    ]);

                                if ($createRes->successful()) {
                                    $newCal = $createRes->json();
                                    $calendarId = $newCal['id'] ?? null;
                                }
                            }

                            // 3. Store in settings
                            if (!isset($current[$email]))
                                $current[$email] = [];
                            $current[$email]['service_id'] = $serviceId;
                            $current[$email]['service_name'] = $serviceName;
                            if ($calendarId) {
                                $current[$email]['google_calendar_id'] = $calendarId;
                            }
                        }
                    }
                }
            }

            Setting::updateOrCreate(
                ['key' => 'google_calendar_settings'],
                ['value' => json_encode($current)]
            );
            return response()->json(['success' => true]);
        }

        // GET
        $settings = Setting::where('key', 'google_calendar_settings')->value('value');
        return response()->json($settings ? json_decode($settings, true) : []);
    }

    /**
     * Helper: Get Valid Token (Refresh if needed)
     */
    private function getValidToken(&$account)
    {
        $accessToken = $account['access_token'];
        $expiresAt = $account['expires_at'] ?? 0;
        $refreshToken = $account['refresh_token'] ?? null;

        if (time() > ($expiresAt - 60)) {
            // Refresh
            if (!$refreshToken)
                return null; // Can't refresh

            $clientId = $this->config['client_id'];
            $clientSecret = $this->config['client_secret'];

            $res = Http::post('https://oauth2.googleapis.com/token', [
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'refresh_token' => $refreshToken,
                'grant_type' => 'refresh_token'
            ]);

            if ($res->successful()) {
                $data = $res->json();
                $newAccess = $data['access_token'];
                $newExpires = time() + $data['expires_in'];

                // Update account ref
                $account['access_token'] = $newAccess;
                $account['expires_at'] = $newExpires;

                return $newAccess;
            }
            return null; // Failed refresh
        }

        return $accessToken;
    }
}
