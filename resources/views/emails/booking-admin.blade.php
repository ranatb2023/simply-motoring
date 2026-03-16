<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Booking</title>
    <style>
        body { margin: 0; padding: 0; background: #f3f4f6; font-family: 'Inter', Arial, sans-serif; color: #111827; }
        .wrapper { max-width: 560px; margin: 40px auto; background: #ffffff; }
        .header { background: #FF6900; padding: 28px 40px; }
        .header-brand { color: rgba(255,255,255,0.7); font-size: 11px; font-weight: 700; letter-spacing: 0.2em; text-transform: uppercase; margin-bottom: 6px; }
        .header h1 { color: #ffffff; font-size: 20px; font-weight: 700; text-transform: uppercase; letter-spacing: -0.02em; margin: 0; }
        .body { padding: 32px 40px; }
        .intro { font-size: 14px; color: #6b7280; margin-bottom: 24px; }
        .card { background: #f9fafb; border: 1px solid #e5e7eb; padding: 20px 24px; margin-bottom: 24px; }
        .card-title { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.15em; color: #FF6900; margin-bottom: 14px; }
        .row { display: flex; margin-bottom: 10px; }
        .row:last-child { margin-bottom: 0; }
        .label { width: 130px; flex-shrink: 0; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: #9ca3af; padding-top: 2px; }
        .value { font-size: 14px; font-weight: 600; color: #111827; }
        .footer { background: #f9fafb; border-top: 1px solid #e5e7eb; padding: 20px 40px; font-size: 12px; color: #9ca3af; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <div class="header-brand">Simply Motoring — Admin</div>
            <h1>New Booking Received</h1>
        </div>

        <div class="body">
            <p class="intro">A new booking has been made. Details below.</p>

            <div class="card">
                <div class="card-title">Appointment</div>
                <div class="row">
                    <span class="label">Service</span>
                    <span class="value">
                        {{ $booking->service->name ?? 'N/A' }}
                        @if($booking->sub_service) · {{ $booking->sub_service }}@endif
                    </span>
                </div>
                <div class="row">
                    <span class="label">Date</span>
                    <span class="value">{{ $booking->start_datetime->format('l, j F Y') }}</span>
                </div>
                <div class="row">
                    <span class="label">Time</span>
                    <span class="value">{{ $booking->start_datetime->format('H:i') }} – {{ $booking->end_datetime->format('H:i') }}</span>
                </div>
                @if($booking->vehicle_reg)
                <div class="row">
                    <span class="label">Vehicle Reg</span>
                    <span class="value">{{ strtoupper($booking->vehicle_reg) }}</span>
                </div>
                @endif
            </div>

            <div class="card">
                <div class="card-title">Customer</div>
                <div class="row">
                    <span class="label">Name</span>
                    <span class="value">{{ $booking->customer_name }}</span>
                </div>
                <div class="row">
                    <span class="label">Email</span>
                    <span class="value">{{ $booking->customer_email }}</span>
                </div>
                @if($booking->customer_phone)
                <div class="row">
                    <span class="label">Phone</span>
                    <span class="value">{{ $booking->customer_phone }}</span>
                </div>
                @endif
            </div>
        </div>

        <div class="footer">
            Booking ID #{{ $booking->id }} &nbsp;·&nbsp; Simply Motoring Admin
        </div>
    </div>
</body>
</html>