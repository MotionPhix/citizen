<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use App\Notifications\WelcomeNewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SubscriberController extends Controller
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

    // Send welcome email
    $subscriber->notify(new WelcomeNewsletterSubscriber($subscriber));

    return response()->json([
      'message' => 'Thank you for subscribing to our newsletter!',
      'subscriber' => $subscriber
    ], 201);
  }

  public function unsubscribe(Subscriber $subscriber, $token)
  {
    if (hash('sha256', $subscriber->email) !== $token) {
      abort(404);
    }

    try {
      $subscriber->update([
        'status' => 'unsubscribed',
        'unsubscribed_at' => now(),
      ]);

      return response()->json([
        'message' => 'You have been successfully unsubscribed from our newsletter.',
      ]);

    } catch (\Exception $e) {
      report($e);

      return response()->json([
        'message' => 'Failed to process your unsubscription. Please try again later.',
      ], 500);
    }
  }
}
