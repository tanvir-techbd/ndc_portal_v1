<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Mail\ContactInquiryAckMail;
use App\Models\ContactInquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

/**
 * Handles both the Contact page form and the Account Access page's message
 * box — same model, same endpoint. See LARAVEL-DYNAMIZATION-PLAN.md Part 6.7
 * (Public Form Abuse Protection).
 */
class ContactInquiryController extends Controller
{
    private const SUBMITTED_COOKIE = 'ndc_has_submitted';

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:150'],
            'organization' => ['nullable', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30'],
            'inquiry_type' => ['nullable', 'string', 'max:50'],
            'message' => ['required', 'string', 'max:5000'],
            'source' => ['required', 'in:contact_page,account_access_page'],
        ]);

        // From the 2nd submission onward (this cookie present), a CAPTCHA
        // checkbox is required. Real deployments swap this boolean check for
        // verifying a reCAPTCHA/hCaptcha token — see the plan doc §6.7. The
        // frontend mock (resources/js/nav.js) already shows/hides the box
        // client-side to match this same cookie-based rule.
        if ($request->cookie(self::SUBMITTED_COOKIE)) {
            $request->validate([
                'captcha_verified' => ['required', 'accepted'],
            ], [
                'captcha_verified.required' => 'Please confirm you\'re not a robot.',
                'captcha_verified.accepted' => 'Please confirm you\'re not a robot.',
            ]);
        }

        $inquiry = ContactInquiry::create($data + [
            'submitted_from_ip' => $request->ip(),
        ]);

        Mail::to($inquiry->email)->send(new ContactInquiryAckMail($inquiry));

        return back()
            ->with('status', 'Thanks — we\'ve received your message and will respond within one business day.')
            ->withCookie(cookie()->forever(self::SUBMITTED_COOKIE, '1'));
    }
}
