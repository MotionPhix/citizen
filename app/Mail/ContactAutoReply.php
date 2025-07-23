<?php

namespace App\Mail;

use App\Models\ContactSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactAutoReply extends Mailable implements ShouldQueue
{
  use Queueable, SerializesModels;

  /**
   * Create a new message instance.
   */
  public function __construct(
    public ContactSubmission $submission
  )
  {
    // Set queue priority for auto-replies (lower priority than admin notifications)
    $this->onQueue('emails');
  }

  /**
   * Get the message envelope.
   */
  public function envelope(): Envelope
  {
    return new Envelope(
      subject: 'Thank you for contacting ' . config('app.name'),
      replyTo: [
        config('mail.from.address') => config('mail.from.name'),
      ],
      tags: ['contact-auto-reply'],
      metadata: [
        'submission_id' => $this->submission->id,
        'user_email' => $this->submission->email,
      ],
    );
  }

  /**
   * Get the message content definition.
   */
  public function content(): Content
  {
    return new Content(
      view: 'emails.contact-auto-reply',
      with: [
        'data' => [
          'name' => $this->submission->name,
          'email' => $this->submission->email,
          'subject' => $this->submission->subject,
          'originalMessage' => $this->submission->message,
          'submissionId' => $this->submission->id,
          'submittedAt' => $this->submission->submitted_at,
          'expectedResponseTime' => $this->getExpectedResponseTime(),
          'supportEmail' => config('mail.from.address'),
          'supportPhone' => config('app.phone'),
          'companyName' => config('app.name'),
          'websiteUrl' => config('app.url'),
        ]
      ],
    );
  }

  /**
   * Get the attachments for the message.
   *
   * @return array<int, \Illuminate\Mail\Mailables\Attachment>
   */
  public function attachments(): array
  {
    return [];
  }

  /**
   * Get expected response time based on submission type and current workload.
   */
  protected function getExpectedResponseTime(): string
  {
    // Check if it's a weekend or holiday
    $now = now();
    $isWeekend = $now->isWeekend();
    $isAfterHours = $now->hour < 9 || $now->hour > 17;

    // Check current pending submissions to estimate workload
    $pendingCount = ContactSubmission::whereIn('status', ['unread', 'read'])->count();

    // Determine response time based on various factors
    if ($isWeekend) {
      return 'within 48 hours (next business day)';
    } elseif ($isAfterHours) {
      return 'within 24 hours (next business day)';
    } elseif ($pendingCount > 20) {
      return 'within 48 hours due to high volume';
    } elseif ($pendingCount > 10) {
      return 'within 24 hours';
    } else {
      return 'within 12 hours';
    }
  }

  /**
   * Determine if the submission requires priority handling.
   */
  protected function isPrioritySubmission(): bool
  {
    $subject = strtolower($this->submission->subject);
    $message = strtolower($this->submission->message);

    $priorityKeywords = [
      'urgent', 'emergency', 'asap', 'immediate', 'critical',
      'bug', 'error', 'broken', 'not working', 'issue',
      'security', 'hack', 'breach', 'vulnerability'
    ];

    foreach ($priorityKeywords as $keyword) {
      if (str_contains($subject, $keyword) || str_contains($message, $keyword)) {
        return true;
      }
    }

    return false;
  }

  /**
   * Get helpful resources based on the submission content.
   */
  protected function getHelpfulResources(): array
  {
    $subject = strtolower($this->submission->subject);
    $message = strtolower($this->submission->message);
    $resources = [];

    // FAQ suggestions based on content
    if (str_contains($subject, 'pricing') || str_contains($message, 'cost')) {
      $resources[] = [
        'title' => 'Pricing Information',
        'url' => config('app.url') . '/pricing',
        'description' => 'View our current pricing plans and packages'
      ];
    }

    if (str_contains($subject, 'support') || str_contains($message, 'help')) {
      $resources[] = [
        'title' => 'Support Center',
        'url' => config('app.url') . '/support',
        'description' => 'Browse our knowledge base and tutorials'
      ];
    }

    if (str_contains($subject, 'technical') || str_contains($message, 'api')) {
      $resources[] = [
        'title' => 'Technical Documentation',
        'url' => config('app.url') . '/docs',
        'description' => 'Access our technical documentation and API guides'
      ];
    }

    return $resources;
  }
}
