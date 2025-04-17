<?php

namespace App\Notifications;

use App\Models\ContactSubmission;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactFormSubmission extends Notification
{
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
          'name' => $this->submission->name,
          'email' => $this->submission->email,
          'subject' => $this->submission->subject,
          'message' => $this->submission->message,
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
    ];
  }
}
