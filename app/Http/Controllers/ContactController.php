<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormSubmitted;
use App\Mail\ContactFormThankYou;

class ContactController extends Controller
{
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'date' => 'required|date',
            'service' => 'required|string',
            'message' => 'required|string',
        ]);

        // Send to admin (uses the mailbox configured in .env — info@ does not exist)
        Mail::to(env('MAIL_ADMIN_ADDRESS', 'hello@simplymotoring.uk'))->send(new ContactFormSubmitted($validated));

        // Send to user
        Mail::to($validated['email'])->send(new ContactFormThankYou($validated));

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Thank you! Your message has been sent successfully.']);
        }

        return back()->with('success', 'Thank you! Your message has been sent successfully.');
    }
}
