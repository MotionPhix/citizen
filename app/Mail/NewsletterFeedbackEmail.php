<?php

namespace App\Mail;

use App\Models\NewsletterIssue;
use App\Models\Subscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Hash;

class NewsletterFeedbackEmail extends Mailable
{
  use Queueable, SerializesModels;

  public function __construct(
    public NewsletterIssue $issue,
    public Subscriber $subscriber
  ) {}

  public function envelope(): Envelope
  {
    return new Envelope(
      subject: 'Share Your Feedback on Our Latest Newsletter',
    );
  }

  public function content(): Content
  {
    return new Content(
      markdown: 'emails.newsletter-feedback',
      with: [
        'token' => Hash::make($this->subscriber->email)
      ]
    );
  }
}
