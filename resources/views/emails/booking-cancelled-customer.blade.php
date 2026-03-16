<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Cancelled</title>
    <style>
        body { margin: 0; padding: 0; background: #f3f4f6; font-family: 'Inter', Arial, sans-serif; color: #111827; }
        .wrapper { max-width: 560px; margin: 40px auto; background: #ffffff; }
        .header { background: #111111; padding: 32px 40px; }
        .header-brand { color: #FF6900; font-size: 11px; font-weight: 700; letter-spacing: 0.2em; text-transform: uppercase; margin-bottom: 6px; }
        .header h1 { color: #ffffff; font-size: 22px; font-weight: 700; text-transform: uppercase; letter-spacing: -0.03em; margin: 0; }
        .body { padding: 36px 40px; }
        .intro { font-size: 15px; color: #374151; line-height: 1.6; margin-bottom: 28px; }
        .card { background: #fef2f2; border-left: 3px solid #ef4444; padding: 20px 24px; margin-bottom: 28px; }
        .row { display: flex; margin-bottom: 10px; }
        .row:last-child { margin-bottom: 0; }
        .label { width: 130px; flex-shrink: 0; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: #9ca3af; padding-top: 2px; }
        .value { font-size: 14px; font-weight: 600; color: #111827; }
        .note { font-size: 13px; color: #6b7280; line-height: 1.6; margin-bottom: 28px; }
        .footer { background: #f9fafb; border-top: 1px solid #e5e7eb; padding: 20px 40px; font-size: 12px; color: #9ca3af; }
        .footer a { color: #FF6900; text-decoration: none; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <div class="header-brand">Simply Motoring</div>
            <h1>Booking Cancelled</h1>
        </div>

        <div class="body">
            <p class="intro">
                Hi {{ $booking->customer_name }},<br>
                Your booking has been cancelled. Here's a summary of what was cancelled:
            </p>

            <div class="card">
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
                    <span class="value">{{ $booking->start_datetime->format('H:i') }}</span>
                </div>
                @if($booking->vehicle_reg)
                <div class="row">
                    <span class="label">Vehicle Reg</span>
                    <span class="value">{{ strtoupper($booking->vehicle_reg) }}</span>
                </div>
                @endif
            </div>

            <p class="note">
                If you believe this is a mistake or would like to rebook, please get in touch with us directly.
                We apologise for any inconvenience.
            </p>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} Simply Motoring &nbsp;·&nbsp;
            <a href="mailto:hello@simplymotoring.uk">hello@simplymotoring.uk</a>
        </div>
    </div>
</body>
</html>