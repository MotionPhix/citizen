<?php

namespace App\Mail;

use App\Models\Subscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Hash;

class NewsletterPreferencesEmail extends Mailable
{
  use Queueable, SerializesModels;

  public function __construct(
    public Subscriber $subscriber
  ) {}

  public function envelope(): Envelope
  {
    return new Envelope(
      subject: 'Manage Your Newsletter Preferences',
    );
  }

  public function content(): Content
  {
    return new Content(
      markdown: 'emails.newsletter-preferences',
      with: [
        'token' => Hash::make($this->subscriber->email)
      ]
    );
  }
}
