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
    public function index(Request $request, BookingService $bookingService)
    {
        // Reflect Google Calendar deletions in the dashboard (throttled to avoid
        // hammering Google on rapid page navigation/search/filter).
        \Cache::remember('gcal_delete_sync_admin', 15, function () use ($bookingService) {
            $bookingService->syncDeletedGoogleEvents();
            return now();
        });

        $bookings = $this->filteredBookingsQuery($request)->paginate(20)->withQueryString();

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

    /**
     * Build the bookings query with the current search / status / date filters
     * applied. Shared by the list view and the CSV export.
     */
    private function filteredBookingsQuery(Request $request)
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

        // A specific calendar date takes precedence over the named date filter.
        if ($date = $request->get('date')) {
            $query->whereDate('start_datetime', $date);
        } else {
            $dateFilter = $request->get('date_filter', 'all');
            if ($dateFilter === 'today') {
                $query->whereDate('start_datetime', Carbon::today());
            } elseif ($dateFilter === 'upcoming') {
                $query->where('start_datetime', '>=', Carbon::now());
            } elseif ($dateFilter === 'past') {
                $query->where('start_datetime', '<', Carbon::today());
            }
        }

        return $query;
    }

    /**
     * Download the (filtered) bookings as a CSV. With no date selected it
     * exports every booking; with a date it exports just that day's.
     */
    public function export(Request $request)
    {
        $bookings = $this->filteredBookingsQuery($request)->get();

        $date = $request->get('date');
        $filename = 'bookings-' . ($date ?: 'all') . '-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($bookings) {
            $out = fopen('php://output', 'w');
            // UTF-8 BOM so Excel shows accents/£ correctly
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['ID', 'Customer', 'Email', 'Phone', 'Vehicle Reg', 'Service', 'Sub Service', 'Date', 'Time', 'Status']);
            foreach ($bookings as $b) {
                fputcsv($out, [
                    $b->id,
                    $b->customer_name,
                    $b->customer_email,
                    $b->customer_phone,
                    strtoupper((string) $b->vehicle_reg),
                    $b->service->name ?? '',
                    $b->sub_service,
                    optional($b->start_datetime)->format('Y-m-d'),
                    optional($b->start_datetime)->format('H:i'),
                    ucfirst((string) $b->status),
                ]);
            }
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
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