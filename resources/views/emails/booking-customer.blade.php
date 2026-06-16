<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmed</title>
    <style>
        body { margin: 0; padding: 0; background: #f3f4f6; font-family: 'Inter', Arial, sans-serif; color: #111827; }
        .wrapper { max-width: 560px; margin: 40px auto; background: #ffffff; }
        .header { background: #111111; padding: 32px 40px; }
        .header-brand { color: #FF6900; font-size: 11px; font-weight: 700; letter-spacing: 0.2em; text-transform: uppercase; margin-bottom: 6px; }
        .header h1 { color: #ffffff; font-size: 22px; font-weight: 700; text-transform: uppercase; letter-spacing: -0.03em; margin: 0; }
        .body { padding: 36px 40px; }
        .intro { font-size: 15px; color: #374151; line-height: 1.6; margin-bottom: 28px; }
        .card { background: #f9fafb; border-left: 3px solid #FF6900; padding: 20px 24px; margin-bottom: 28px; }
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
            <h1>You're Booked In!</h1>
        </div>

        <div class="body">
            <p class="intro">
                Hi {{ $booking->customer_name }},<br>
                Your booking has been confirmed. Here's a summary of your appointment:
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

            @if($booking->manageUrl())
            <div style="text-align:center; margin-bottom: 28px;">
                <a href="{{ $booking->manageUrl() }}"
                   style="display:inline-block; background:#FF6900; color:#ffffff; font-size:13px; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; text-decoration:none; padding:13px 28px; border-radius:8px;">
                    Manage Your Booking
                </a>
                <p style="font-size:12px; color:#9ca3af; margin-top:10px;">
                    Reschedule, change service, update your details or cancel — no login needed.
                </p>
            </div>
            @endif

            <p class="note">
                If you have any questions, please reply to this email or contact us directly.
                We look forward to seeing you!
            </p>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} Simply Motoring &nbsp;·&nbsp;
            <a href="mailto:hello@simplymotoring.uk">hello@simplymotoring.uk</a>
        </div>
    </div>
</body>
</html>