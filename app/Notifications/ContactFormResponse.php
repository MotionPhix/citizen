<?php

namespace App\Notifications;

use App\Models\ContactSubmission;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactFormResponse extends Notification
{
  public function __construct(
    public ContactSubmission $submission,
    public string $responseMessage
  ) {}

  public function via($notifiable): array
  {
    return ['mail'];
  }

  public function toMail($notifiable): MailMessage
  {
    return (new MailMessage)
      ->subject('Re: ' . $this->submission->subject)
      ->view('emails.contact-form-response', [
        'data' => [
          'name' => $this->submission->name,
          'subject' => $this->submission->subject,
          'originalMessage' => $this->submission->message,
          'responseMessage' => $this->responseMessage,
        ]
      ]);
  }
}
