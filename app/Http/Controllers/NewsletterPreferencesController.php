<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class NewsletterPreferencesController extends Controller
{
  public function show(Subscriber $subscriber, string $token)
  {
    abort_unless(
      Hash::check($subscriber->email, $token),
      404,
      'Invalid or expired preferences link'
    );

    return view('newsletters.preferences', [
      'subscriber' => $subscriber,
      'token' => $token
    ]);
  }

  public function update(Request $request, Subscriber $subscriber, string $token)
  {
    abort_unless(
      Hash::check($subscriber->email, $token),
      404,
      'Invalid or expired preferences link'
    );

    $validated = $request->validate([
      'frequency' => ['required', 'string', 'in:' . implode(',', array_keys(Subscriber::FREQUENCIES))],
      'categories' => ['array'],
      'categories.*' => ['string', 'in:' . implode(',', array_keys(Subscriber::CATEGORIES))],
      'timezone' => ['required', 'string', 'in:' . implode(',', array_keys(Subscriber::TIMEZONES))],
      'time_of_day' => ['required', 'date_format:H:i'],
      'format' => ['required', 'in:html,text'],
      'digest' => ['boolean'],
      'notifications' => ['array'],
      'notifications.browser' => ['boolean'],
      'notifications.mobile' => ['boolean']
    ]);

    $subscriber->update([
      'preferences' => [
        'frequency' => $validated['frequency'],
        'categories' => $validated['categories'] ?? [],
        'timezone' => $validated['timezone'],
        'time_of_day' => $validated['time_of_day'],
        'format' => $validated['format'],
        'digest' => (bool) ($validated['digest'] ?? true),
        'notifications' => [
          'browser' => (bool) ($validated['notifications']['browser'] ?? false),
          'mobile' => (bool) ($validated['notifications']['mobile'] ?? false)
        ]
      ]
    ]);

    return back()->with('success', 'Your preferences have been updated.');
  }
}
