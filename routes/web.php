<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/service', function () {
    return view('service');
})->name('service');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::get('/service/brake-discs-and-pads', function () {
    return view('services.brake-discs-and-pads');
})->name('service.brake-discs-and-pads');

Route::get('/service/brake-fluid-change', function () {
    return view('services.brake-fluid-change');
})->name('service.brake-fluid-change');

Route::get('/service/full-service', function () {
    return view('services.full-service');
})->name('service.full-service');

Route::get('/service/interim-service', function () {
    return view('services.interim-service');
})->name('service.interim-service');

Route::get('/service/major-service', function () {
    return view('services.major-service');
})->name('service.major-service');

Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::resource('services', \App\Http\Controllers\Admin\ServiceController::class);
    Route::resource('staff', \App\Http\Controllers\Admin\StaffController::class);
    Route::resource('bookings', \App\Http\Controllers\Admin\BookingController::class);
    Route::get('/availability', [\App\Http\Controllers\Admin\AvailabilityController::class, 'index'])->name('availability.index');
    Route::get('/google-reviews', [\App\Http\Controllers\Admin\GoogleReviewsController::class, 'index'])->name('google-reviews.index');
});

// --- API Routes (Consumed by Admin JS) ---
Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('api/admin')->group(function () {
        Route::get('services', [\App\Http\Controllers\Api\Admin\ServicesController::class, 'index']);
        Route::post('services', [\App\Http\Controllers\Api\Admin\ServicesController::class, 'store']);
        Route::delete('services', [\App\Http\Controllers\Api\Admin\ServicesController::class, 'destroy']);

        Route::get('staff', [\App\Http\Controllers\Api\Admin\StaffController::class, 'index']);
        Route::post('staff', [\App\Http\Controllers\Api\Admin\StaffController::class, 'store']);
        Route::delete('staff', [\App\Http\Controllers\Api\Admin\StaffController::class, 'destroy']);

        Route::post('schedules', [\App\Http\Controllers\Api\Admin\ScheduleController::class, 'store']);
        Route::delete('schedules', [\App\Http\Controllers\Api\Admin\ScheduleController::class, 'destroy']);

        Route::get('settings', [\App\Http\Controllers\Api\Admin\SettingsController::class, 'index']); // Aggregate
        Route::post('settings', [\App\Http\Controllers\Api\Admin\SettingsController::class, 'store']); // Save Avail

        Route::get('holidays', [\App\Http\Controllers\Api\Admin\HolidayController::class, 'index']);
        Route::post('holidays', [\App\Http\Controllers\Api\Admin\HolidayController::class, 'store']);
        Route::delete('holidays', [\App\Http\Controllers\Api\Admin\HolidayController::class, 'destroy']);

        // Missing Routes (Mock/Simple)
        Route::get('timezone', [\App\Http\Controllers\Api\Admin\SettingsController::class, 'getTimezone']);
        Route::post('timezone', [\App\Http\Controllers\Api\Admin\SettingsController::class, 'storeTimezone']);

        Route::get('google/calendars', [\App\Http\Controllers\Api\Admin\GoogleCalendarController::class, 'getCalendars']);
        Route::get('google/settings', [\App\Http\Controllers\Api\Admin\GoogleCalendarController::class, 'settings']);
        Route::post('google/settings', [\App\Http\Controllers\Api\Admin\GoogleCalendarController::class, 'settings']);

        Route::get('general-settings', function () {
            return response()->json(['business_name' => config('app.name')]);
        });
        Route::post('general-settings', function () {
            return response()->json(['success' => true]);
        });
        Route::get('meeting-limits', function () {
            $setting = \App\Models\Setting::where('key', 'meeting_limits')->first();
            $limits = $setting ? json_decode($setting->value, true) : [];
            return response()->json($limits);
        });
        Route::post('meeting-limits', function (\Illuminate\Http\Request $request) {
            $limits = $request->input('limits', []); // Expects array of { limit, unit }
            \App\Models\Setting::updateOrCreate(
                ['key' => 'meeting_limits'],
                ['value' => json_encode($limits)]
            );
            return response()->json(['success' => true]);
        });

        Route::get('google/holidays', [\App\Http\Controllers\Api\Admin\GoogleCalendarController::class, 'getHolidays']);

        Route::post('holidays/country', function (\Illuminate\Http\Request $request) {
            $country = $request->input('country', 'other');
            \App\Models\Setting::updateOrCreate(
                ['key' => 'holidays_country'],
                ['value' => $country]
            );
            return response()->json(['success' => true, 'country' => $country]);
        });
        Route::get('holidays/country', function () {
            $setting = \App\Models\Setting::where('key', 'holidays_country')->first();
            return response()->json(['country' => $setting ? $setting->value : 'other']);
        });

        Route::get('auth/google/connect', [\App\Http\Controllers\Api\Admin\GoogleCalendarController::class, 'connect']);
        Route::get('auth/google/callback', [\App\Http\Controllers\Api\Admin\GoogleCalendarController::class, 'callback']);
        Route::delete('auth/google/disconnect', [\App\Http\Controllers\Api\Admin\GoogleCalendarController::class, 'disconnect']);

        // Social Media / Google Reviews
        Route::get('google-reviews/search', [\App\Http\Controllers\Admin\GoogleReviewsController::class, 'search']);
        Route::post('google-reviews/save', [\App\Http\Controllers\Admin\GoogleReviewsController::class, 'save']);

        Route::post('assign/staff-services', function () {
            return response()->json(['success' => true]);
        });
        Route::post('assign/schedule-services', [\App\Http\Controllers\Api\Admin\SettingsController::class, 'assignServices']);
    });
});

Route::get('/api/reviews', [\App\Http\Controllers\Admin\GoogleReviewsController::class, 'getReviews']);

require __DIR__ . '/auth.php';
