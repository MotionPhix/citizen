<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
  public function subscribe(Request $request)
  {
    $validated = $request->validate([
      'email' => ['required', 'email', 'max:255'],
      'name' => ['nullable', 'string', 'max:255'],
    ]);

    $subscriber = Subscriber::firstOrCreate(
      ['email' => $validated['email']],
      [
        'name' => $validated['name'],
        'status' => 'subscribed',
      ]
    );

    if ($subscriber->status === 'unsubscribed') {
      $subscriber->update([
        'status' => 'subscribed',
        'unsubscribed_at' => null,
      ]);
    }

    return back()->with('success', 'Thank you for subscribing to our newsletter!');
  }

  public function unsubscribe(Subscriber $subscriber, $token)
  {
    if (hash('sha256', $subscriber->email) !== $token) {
      abort(404);
    }

    $subscriber->update([
      'status' => 'unsubscribed',
      'unsubscribed_at' => now(),
    ]);

    return view('newsletter.unsubscribed');
  }
}
