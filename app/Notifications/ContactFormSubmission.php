<?php

namespace App\Notifications;

use App\Models\ContactSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactFormSubmission extends Notification implements ShouldQueue
{
  use Queueable;

  public function __construct(
    public ContactSubmission $submission
  ) {}

  public function via($notifiable): array
  {
    return ['mail', 'database'];
  }

  public function toMail($notifiable): MailMessage
  {
    return (new MailMessage)
      ->subject('New Contact Form Submission')
      ->view('emails.contact-form', [
        'data' => [
          'serial' => $this->submission->id,
          'name' => $this->submission->name,
          'email' => $this->submission->email,
          'subject' => $this->submission->subject,
          'message' => $this->submission->message,
          'ip_address' => $this->submission->ip_address,
        ]
      ]);
  }

  public function toArray($notifiable): array
  {
    return [
      'submission_id' => $this->submission->id,
      'name' => $this->submission->name,
      'subject' => $this->submission->subject,
      'email' => $this->submission->email,
      'message' => $this->submission->message,
      'ip_address' => $this->submission->ip_address,
    ];
  }
}
