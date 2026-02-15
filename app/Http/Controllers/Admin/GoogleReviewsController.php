<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleReviewsController extends Controller
{
    /**
     * Display the Google Reviews settings page.
     */
    public function index()
    {
        $placeIdSetting = Setting::where('key', 'google_place_id')->first();
        $placeId = $placeIdSetting ? $placeIdSetting->value : null;

        $reviewsDataSetting = Setting::where('key', 'google_reviews_data')->first();
        $reviewsData = $reviewsDataSetting ? json_decode($reviewsDataSetting->value, true) : null;

        return view('admin.google-reviews.index', compact('placeId', 'reviewsData'));
    }

    /**
     * Search for a place using Google Places API (Text Search or Autocomplete).
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        $apiKey = config('services.google.places_api_key'); // Ensure you have this in config/services.php or .env

        if (!$query) {
            return response()->json(['results' => []]);
        }

        // Mock response if no API key is present (for testing/dev without key)
        if (!$apiKey) {
            // Fallback/Mock for demonstration if key is missing
            return response()->json([
                'results' => [
                    [
                        'place_id' => 'mock_place_id_1',
                        'name' => 'Simply Motoring (Mock)',
                        'formatted_address' => '123 Fake St, London, UK',
                    ],
                    [
                        'place_id' => 'mock_place_id_2',
                        'name' => $query . ' Auto Service',
                        'formatted_address' => '456 Test Ave, Manchester, UK',
                    ]
                ]
            ]);
        }

        try {
            $response = Http::get('https://maps.googleapis.com/maps/api/place/textsearch/json', [
                'query' => $query,
                'key' => $apiKey,
            ]);

            return response()->json($response->json());
        } catch (\Exception $e) {
            Log::error('Google Places Search Error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch results'], 500);
        }
    }

    /**
     * Save the selected Place ID and fetch/store its reviews.
     */
    public function save(Request $request)
    {
        $placeId = $request->input('place_id');
        $apiKey = config('services.google.places_api_key');

        if (!$placeId) {
            return response()->json(['error' => 'Place ID is required'], 400);
        }

        // 1. Save Place ID
        Setting::updateOrCreate(
            ['key' => 'google_place_id'],
            ['value' => $placeId]
        );

        // 2. Fetch Reviews from Google Place Details API
        $reviewsData = [];
        if ($apiKey) {
            try {
                $response = Http::get('https://maps.googleapis.com/maps/api/place/details/json', [
                    'place_id' => $placeId,
                    'fields' => 'name,rating,reviews,formatted_address,url,user_ratings_total',
                    'key' => $apiKey,
                ]);

                if ($response->successful()) {
                    $result = $response->json()['result'] ?? [];
                    // Extract only necessary reviews data
                    if (isset($result['reviews'])) {
                        // Sort by rating (highest first) and take top 20
                        $reviews = collect($result['reviews'])->sortByDesc('rating')->take(20)->values()->all();
                        $result['reviews'] = $reviews;
                    }
                    $reviewsData = $result;
                }
            } catch (\Exception $e) {
                Log::error('Google Place Details Error: ' . $e->getMessage());
                // Don't fail the save if fetch fails, just ensure we have empty data
            }
        } else {
            // Mock Data if API Key missing
            $reviewsData = [
                'name' => 'Simply Motoring',
                'rating' => 5.0,
                'user_ratings_total' => 120,
                'formatted_address' => 'Mock Address, UK',
                'reviews' => [
                    [
                        'author_name' => 'Mock User 1',
                        'rating' => 5,
                        'text' => 'Great service, highly recommended!',
                        'profile_photo_url' => null,
                        'relative_time_description' => '2 weeks ago'
                    ],
                    [
                        'author_name' => 'Mock User 2',
                        'rating' => 5,
                        'text' => 'Fast and efficient MOT.',
                        'profile_photo_url' => null,
                        'relative_time_description' => '1 month ago'
                    ]
                ]
            ];
        }

        // 3. Save Reviews Data
        Setting::updateOrCreate(
            ['key' => 'google_reviews_data'],
            ['value' => json_encode($reviewsData)]
        );

        return response()->json(['success' => true, 'data' => $reviewsData]);
    }

    /**
     * Public API endpoint to get reviews (used by frontend).
     */
    public function getReviews()
    {
        $reviewsDataSetting = Setting::where('key', 'google_reviews_data')->first();

        if (!$reviewsDataSetting) {
            return response()->json(['reviews' => []]);
        }

        $data = json_decode($reviewsDataSetting->value, true);

        return response()->json($data);
    }
}
