<?php

namespace App\Http\Controllers;

use App\Models\NewsletterIssue;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class NewsletterFeedbackController extends Controller
{
  public function show(NewsletterIssue $issue, Subscriber $subscriber, string $token)
  {
    abort_unless(
      Hash::check($subscriber->email, $token),
      404,
      'Invalid or expired feedback link'
    );

    return view('newsletters.feedback', [
      'issue' => $issue,
      'subscriber' => $subscriber,
      'token' => $token
    ]);
  }

  public function store(Request $request, NewsletterIssue $issue, Subscriber $subscriber, string $token)
  {
    abort_unless(
      Hash::check($subscriber->email, $token),
      404,
      'Invalid or expired feedback link'
    );

    $validated = $request->validate([
      'rating' => ['required', 'integer', 'min:1', 'max:5'],
      'comment' => ['nullable', 'string', 'max:500']
    ]);

    $issue->feedback()->create([
      'subscriber_id' => $subscriber->id,
      'rating' => $validated['rating'],
      'comment' => $validated['comment']
    ]);

    return back()->with('success', 'Thank you for your feedback!');
  }
}
