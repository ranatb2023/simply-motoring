<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BlogCommentController;
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

Route::get('/blogs', function () {
    $featuredPost = \App\Models\BlogPost::published()
        ->featured()
        ->latest('published_at')
        ->first();

    // If no featured post manually selected, take the very latest one
    if (!$featuredPost) {
        $featuredPost = \App\Models\BlogPost::published()
            ->latest('published_at')
            ->first();
    }

    $posts = \App\Models\BlogPost::published()
        ->when($featuredPost, function ($query) use ($featuredPost) {
            return $query->where('id', '!=', $featuredPost->id);
        })
        ->latest('published_at')
        ->paginate(9); // 3 columns x 3 rows = 9 posts per page looks good

    return view('blogs', compact('featuredPost', 'posts'));
})->name('blogs');

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

    // Blog Management Routes
    Route::prefix('blog')->name('blog.')->group(function () {
        // Posts
        Route::resource('posts', \App\Http\Controllers\Admin\BlogPostController::class);
        Route::post('posts/bulk-action', [\App\Http\Controllers\Admin\BlogPostController::class, 'bulkAction'])
            ->name('posts.bulk-action');

        // Categories
        Route::post('categories/bulk-action', [\App\Http\Controllers\Admin\BlogCategoryController::class, 'bulkAction'])
            ->name('categories.bulk-action');
        Route::resource('categories', \App\Http\Controllers\Admin\BlogCategoryController::class);


        // Tags
        Route::post('tags/bulk-action', [\App\Http\Controllers\Admin\BlogTagController::class, 'bulkAction'])
            ->name('tags.bulk-action');
        Route::resource('tags', \App\Http\Controllers\Admin\BlogTagController::class);
        Route::post('tags/sync-usage', [\App\Http\Controllers\Admin\BlogTagController::class, 'syncUsageCounts'])
            ->name('tags.sync-usage');
        Route::post('tags/delete-unused', [\App\Http\Controllers\Admin\BlogTagController::class, 'deleteUnused'])
            ->name('tags.delete-unused');

        // Comments
        Route::get('comments', [\App\Http\Controllers\Admin\BlogCommentController::class, 'index'])
            ->name('comments.index');
        Route::get('comments/{comment}', [\App\Http\Controllers\Admin\BlogCommentController::class, 'show'])
            ->name('comments.show');
        Route::post('comments/{comment}/approve', [\App\Http\Controllers\Admin\BlogCommentController::class, 'approve'])
            ->name('comments.approve');
        Route::post('comments/{comment}/spam', [\App\Http\Controllers\Admin\BlogCommentController::class, 'spam'])
            ->name('comments.spam');
        Route::post('comments/{comment}/trash', [\App\Http\Controllers\Admin\BlogCommentController::class, 'trash'])
            ->name('comments.trash');
        Route::post('comments/{comment}/restore', [\App\Http\Controllers\Admin\BlogCommentController::class, 'restore'])
            ->name('comments.restore');
        Route::delete('comments/{comment}', [\App\Http\Controllers\Admin\BlogCommentController::class, 'destroy'])
            ->name('comments.destroy');
        Route::post('comments/{comment}/unflag', [\App\Http\Controllers\Admin\BlogCommentController::class, 'unflag'])
            ->name('comments.unflag');
        Route::post('comments/bulk-action', [\App\Http\Controllers\Admin\BlogCommentController::class, 'bulkAction'])
            ->name('comments.bulk-action');
        Route::delete('comments/empty-trash', [\App\Http\Controllers\Admin\BlogCommentController::class, 'emptyTrash'])
            ->name('comments.empty-trash');
        Route::delete('comments/delete-spam', [\App\Http\Controllers\Admin\BlogCommentController::class, 'deleteSpam'])
            ->name('comments.delete-spam');
    });
});


require __DIR__ . '/auth.php';

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

// --- Blog Routes ---
Route::prefix('blog')->name('blog.')->group(function () {
    // Main blog listing
    Route::get('/', [BlogController::class, 'index'])->name('index');

    // Search
    Route::get('/search', [BlogController::class, 'search'])->name('search');

    // Category archive
    Route::get('/category/{category}', [BlogController::class, 'category'])->name('category');

    // Tag archive
    Route::get('/tag/{tag}', [BlogController::class, 'tag'])->name('tag');

    // Single post with category (Legacy/Alternate)
    Route::get('/{category}/{post}', [BlogController::class, 'show'])->name('show');
});

// Single post (Root URL)
Route::get('/{post}', [BlogController::class, 'post'])->name('blog.post');

// --- Blog Comment Routes ---
Route::prefix('blog')->name('blog.comments.')->group(function () {
    // Submit comment
    Route::post('/{post}/comments', [BlogCommentController::class, 'store'])->name('store');

    // Like/Dislike comments (AJAX)
    Route::post('/comments/{comment}/like', [BlogCommentController::class, 'like'])->name('like');
    Route::post('/comments/{comment}/dislike', [BlogCommentController::class, 'dislike'])->name('dislike');

    // Flag comment (AJAX)
    Route::post('/comments/{comment}/flag', [BlogCommentController::class, 'flag'])->name('flag');

    // Delete own comment (authenticated users)
    Route::delete('/comments/{comment}', [BlogCommentController::class, 'destroy'])
        ->middleware('auth')
        ->name('destroy');
});

// --- Blog Share Tracking (AJAX) ---
Route::post('/blog/{post}/share', [BlogController::class, 'share'])->name('blog.share');

Route::get('/api/reviews', [\App\Http\Controllers\Admin\GoogleReviewsController::class, 'getReviews']);


