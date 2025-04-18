<?php

namespace App\Notifications;

use App\Models\Newsletter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewsletterMail extends Notification implements ShouldQueue
{
  use Queueable;

  public function __construct(
    public Newsletter $newsletter
  ) {}

  public function via($notifiable): array
  {
    return ['mail'];
  }

  public function toMail($notifiable): MailMessage
  {
    return (new MailMessage)
      ->subject($this->newsletter->title)
      ->view('emails.newsletter', [
        'content' => $this->newsletter->content,
        'unsubscribeUrl' => route('newsletter.unsubscribe', [
          'subscriber' => $notifiable->id,
          'token' => hash('sha256', $notifiable->email),
        ]),
      ]);
  }
}
