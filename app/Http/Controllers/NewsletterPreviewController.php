<?php

namespace App\Http\Controllers;

use App\Models\NewsletterIssue;
use Illuminate\Http\Request;

class NewsletterPreviewController extends Controller
{
  public function show(NewsletterIssue $newsletterIssue)
  {
    // Create a fake subscriber for preview purposes
    $previewSubscriber = new \App\Models\Subscriber([
      'email' => auth()->user()->email,
      'name' => auth()->user()->name,
      'status' => 'subscribed'
    ]);

    return view('newsletters.preview', [
      'issue' => $newsletterIssue->load(['stories', 'updates', 'events']),
      'subscriber' => $previewSubscriber,
      'isPreview' => true,
      'previewMode' => true,
      'unsubscribeUrl' => '#preview'
    ]);
  }
}
