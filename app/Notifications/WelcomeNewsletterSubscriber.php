<?php

namespace App\Notifications;

use App\Models\Subscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class WelcomeNewsletterSubscriber extends Notification
{
  use Queueable;

  public function __construct(
    private readonly Subscriber $subscriber
  ) {}

  public function via($notifiable): array
  {
    return ['mail'];
  }

  public function toMail($notifiable): MailMessage
  {
    $unsubscribeToken = hash('sha256', $this->subscriber->email);
    $unsubscribeUrl = route('newsletter.unsubscribe', [
      'subscriber' => $this->subscriber->id,
      'token' => $unsubscribeToken
    ]);

    return (new MailMessage)
      ->subject('Welcome to Citizen Alliance Newsletter')
      ->markdown('emails.newsletter.welcome', [
        'subscriber' => $this->subscriber,
        'unsubscribeUrl' => $unsubscribeUrl
      ]);
  }
}
