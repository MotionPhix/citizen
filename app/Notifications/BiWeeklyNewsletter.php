<?php

namespace App\Notifications;

use App\Models\NewsletterIssue;
use App\Models\Subscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BiWeeklyNewsletter extends Notification implements ShouldQueue
{
  use Queueable;

  public function __construct(
    private readonly NewsletterIssue $issue,
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
      ->subject($this->issue->title)
      ->markdown('emails.newsletter.bi-weekly', [
        'issue' => $this->issue,
        'subscriber' => $this->subscriber,
        'unsubscribeUrl' => $unsubscribeUrl,
      ]);
  }
}
