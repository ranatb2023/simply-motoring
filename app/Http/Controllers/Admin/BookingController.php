<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Service;
use App\Mail\BookingCancellation;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with('service')->orderBy('start_datetime', 'desc');

        // Search
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('vehicle_reg', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        // Date filter
        $dateFilter = $request->get('date_filter', 'all');
        if ($dateFilter === 'today') {
            $query->whereDate('start_datetime', Carbon::today());
        } elseif ($dateFilter === 'upcoming') {
            $query->where('start_datetime', '>=', Carbon::now());
        } elseif ($dateFilter === 'past') {
            $query->where('start_datetime', '<', Carbon::today());
        }

        $bookings = $query->paginate(20)->withQueryString();

        // Stats
        $stats = [
            'total'     => Booking::count(),
            'today'     => Booking::whereDate('start_datetime', Carbon::today())->count(),
            'upcoming'  => Booking::where('start_datetime', '>=', Carbon::now())->where('status', '!=', 'cancelled')->count(),
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
        ];

        return view('admin.bookings.index', compact('bookings', 'stats'));
    }

    public function create()
    {
        return view('admin.bookings.create');
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Booking $booking)
    {
        //
    }

    public function edit(Booking $booking)
    {
        //
    }

    public function update(Request $request, Booking $booking)
    {
        //
    }

    public function destroy(Booking $booking, BookingService $bookingService)
    {
        // Eager-load service for email templates
        $booking->load('service');

        // Delete Google Calendar event (best-effort)
        $bookingService->deleteGoogleCalendarEvent($booking);

        // Send cancellation email to customer (best-effort)
        try {
            Mail::to($booking->customer_email)
                ->send(new BookingCancellation($booking, false));
        } catch (\Throwable $e) {
            \Log::error('Booking cancellation email (customer) failed', [
                'booking_id' => $booking->id,
                'to'         => $booking->customer_email,
                'error'      => $e->getMessage(),
            ]);
        }

        // Send cancellation notification to admin (best-effort)
        try {
            $adminEmail = env('MAIL_ADMIN_ADDRESS', 'ranatb2023@gmail.com');
            Mail::to($adminEmail)
                ->send(new BookingCancellation($booking, true));
        } catch (\Throwable $e) {
            \Log::error('Booking cancellation email (admin) failed', [
                'booking_id' => $booking->id,
                'error'      => $e->getMessage(),
            ]);
        }

        $booking->delete();

        return back()->with('success', 'Booking deleted and customer notified.');
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate(['status' => 'required|in:confirmed,pending,cancelled,completed']);
        $booking->update(['status' => $request->status]);
        return back()->with('success', 'Booking status updated.');
    }
}