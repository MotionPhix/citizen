<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactFormRequest;
use App\Models\ContactSubmission;
use App\Models\User;
use App\Notifications\ContactFormSubmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\View\View;

class ContactController extends Controller
{
  public function index(): View
  {
    return view('pages.contact');
  }

  public function submit(ContactFormRequest $request): RedirectResponse
  {
    // Rate limiting: 2 submissions per hour per IP
    $key = 'contact-form:' . $request->ip();

    if (RateLimiter::tooManyAttempts($key, 2)) {
      return back()->with('error', 'Too many attempts. Please try again in ' .
        RateLimiter::availableIn($key) . ' seconds.');
    }

    RateLimiter::hit($key, 3600); // Key expires in 1 hour

    // Store the submission
    $submission = ContactSubmission::create([
      'name' => $request->name,
      'email' => $request->email,
      'subject' => $request->subject,
      'message' => $request->message,
      'status' => 'unread',
      'ip_address' => $request->ip(),
      'user_agent' => $request->userAgent(),
    ]);

    // Send notification to admin
    User::where('role', 'admin')->each(function ($admin) use ($submission) {
      $admin->notify(new ContactFormSubmission($submission));
    });

    return back()->with('success', 'Thank you for your message. We will get back to you soon!');
  }
}
