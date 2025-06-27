<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;

class ContactSubmission extends Model
{
  use HasFactory, Notifiable;

  protected $fillable = [
    'name',
    'email',
    'subject',
    'message',
    'status',
    'response',
    'ip_address',
    'user_agent',
    'referrer',
    'submitted_at',
    'responded_at',
    'responded_by',
    'spam_score',
    'metadata'
  ];

  protected $casts = [
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
    'responded_at' => 'datetime',
    'submitted_at' => 'datetime',
    'metadata' => 'array',
    'spam_score' => 'float',
  ];

  protected $attributes = [
    'status' => 'unread',
    'spam_score' => 0.0,
  ];

  protected $dates = [
    'deleted_at',
    'submitted_at',
    'responded_at'
  ];

  // Constants for status values
  const STATUS_UNREAD = 'unread';
  const STATUS_READ = 'read';
  const STATUS_RESPONDED = 'responded';
  const STATUS_SPAM = 'spam';
  const STATUS_ARCHIVED = 'archived';

  /**
   * Get the user who responded to this submission.
   */
  public function responder(): BelongsTo
  {
    return $this->belongsTo(User::class, 'responded_by');
  }

  #[Scope]
  protected function unread(Builder $query): void
  {
    $query->where('status', self::STATUS_UNREAD);
  }

  /**
   * Get submissions that are read but not responded to.
   */
  #[Scope]
  protected function read(Builder $query): void
  {
    $query->where('status', self::STATUS_READ);
  }

  /**
   * Get submissions marked as spam.
   */
  #[Scope]
  protected function spam(Builder $query): void
  {
    $query->where('status', self::STATUS_SPAM);
  }

  /**
   * Get submissions with spam score above threshold.
   */
  #[Scope]
  protected function likelySpam(Builder $query, float $threshold = 0.7): void
  {
    $query->where('spam_score', '>=', $threshold);
  }

  /**
   * Get submissions from the last N days.
   */
  #[Scope]
  protected function recent(Builder $query, int $days = 7): void
  {
    $query->where('created_at', '>=', now()->subDays($days));
  }

  /**
   * Get submissions that have been responded to.
   */
  protected function responded(Builder $query): void
  {
    $query->where('status', self::STATUS_RESPONDED);
  }

  /**
   * Search submissions by content.
   */
  #[Scope]
  protected function search(Builder $query, string $search): void
  {
    $query->where(function ($q) use ($search) {
      $q->where('name', 'LIKE', "%{$search}%")
        ->orWhere('email', 'LIKE', "%{$search}%")
        ->orWhere('subject', 'LIKE', "%{$search}%")
        ->orWhere('message', 'LIKE', "%{$search}%");
    });
  }

  /**
   * Mark submission as read.
   */
  public function markAsRead(): bool
  {
    return $this->update([
      'status' => self::STATUS_READ,
      'responded_by' => auth()->id(),
    ]);
  }

  /**
   * Mark submission as responded.
   */
  public function markAsResponded($response = null): bool
  {
    return $this->update([
      'status' => self::STATUS_RESPONDED,
      'responded_by' => auth()->id(),
      'responded_at' => now(),
      'response' => $response ?? $this->response,
    ]);
  }

  /**
   * Mark submission as spam.
   */
  public function markAsSpam(): bool
  {
    return $this->update([
      'status' => self::STATUS_SPAM,
      'responded_by' => auth()->id(),
    ]);
  }

  /**
   * Archive the submission.
   */
  public function archive(): bool
  {
    return $this->update([
      'status' => self::STATUS_ARCHIVED,
      'responded_by' => auth()->id(),
    ]);
  }

  /**
   * Get formatted submission date.
   */
  public function formattedDate(): Attribute
  {
    return Attribute::make(
      get: fn () => $this->created_at->format('M j, Y g:i A')
    );
  }

  /**
   * Get time since submission.
   */
  public function timeSince(): Attribute
  {
    return Attribute::make(
      get: fn () => $this->created_at->diffForHumans()
    );
  }

  /**
   * Get response time if responded.
   */
  public function responseTime(): Attribute
  {
    return Attribute::make(
      get: fn () => $this->calculateResponseTime()
    );
  }

  /**
   * Check if submission is likely spam.
   */
  public function isLikelySpam(): Attribute
  {
    return Attribute::make(
      get: fn () => $this->spam_score >= 0.7
    );
  }

  /**
   * Get short excerpt of message.
   */
  public function excerpt(): Attribute
  {
    return Attribute::make(
      get: fn () => strlen($this->message) > 100
        ? substr($this->message, 0, 100) . '...'
        : $this->message
    );
  }

  /**
   * Get status badge color.
   */
  public function statusColor(): Attribute
  {
    return Attribute::make(
      get: fn () => match($this->status) {
        self::STATUS_UNREAD => 'red',
        self::STATUS_READ => 'yellow',
        self::STATUS_RESPONDED => 'green',
        self::STATUS_SPAM => 'gray',
        self::STATUS_ARCHIVED => 'purple',
        default => 'blue'
      }
    );
  }

  /**
   * Set up notification routing.
   */
  public function routeNotificationForMail(): string
  {
    return $this->email;
  }

  /**
   * Boot method for model events.
   */
  protected static function boot(): void
  {
    parent::boot();

    static::creating(function ($submission) {
      // Set submitted_at if not provided
      if (!$submission->submitted_at) {
        $submission->submitted_at = now();
      }
    });
  }

  /**
   * Get submissions statistics.
   */
  public static function getStats(): array
  {
    return [
      'total' => self::count(),
      'unread' => self::unread()->count(),
      'today' => self::whereDate('created_at', today())->count(),
      'this_week' => self::where('created_at', '>=', now()->startOfWeek())->count(),
      'spam_rate' => self::likelySpam()->count() / max(self::count(), 1) * 100,
      'avg_response_time' => self::whereNotNull('responded_at')
        ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, responded_at)) as avg_hours')
        ->value('avg_hours'),
    ];
  }

  /**
   * Export submissions to array.
   */
  public function toExportArray(): array
  {
    return [
      'ID' => $this->id,
      'Name' => $this->name,
      'Email' => $this->email,
      'Subject' => $this->subject,
      'Message' => $this->message,
      'Status' => $this->status,
      'Spam Score' => $this->spam_score,
      'IP Address' => $this->ip_address,
      'Submitted At' => $this->submitted_at?->format('Y-m-d H:i:s'),
      'Responded At' => $this->responded_at?->format('Y-m-d H:i:s'),
      'Response' => $this->response,
    ];
  }

  private function calculateResponseTime(): ?string
  {
    if (!$this->responded_at || !$this->submitted_at) {
      return null;
    }


    $diff = $this->created_at->diffForHumans($this->responded_at, true);

    return $diff->format('%h hours %i minutes');
  }
}
