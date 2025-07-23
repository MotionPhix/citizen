<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Carbon\Carbon;

class NewsletterIssue extends Model implements HasMedia
{
  use HasFactory, InteractsWithMedia;

  protected $fillable = [
    'title',
    'description',
    'featured_image',
    'published_at',
    'scheduled_at',
    'sent_at',
    'status',
    'issue_number',
    'template',
    'metadata',
    'created_by',
    'published_by',
    'subscriber_count',
    'open_rate',
    'click_rate',
  ];

  protected $casts = [
    'published_at' => 'datetime',
    'scheduled_at' => 'datetime',
    'sent_at' => 'datetime',
    'metadata' => 'array',
    'subscriber_count' => 'integer',
    'open_rate' => 'decimal:2',
    'click_rate' => 'decimal:2',
  ];

  protected $attributes = [
    'status' => 'draft',
    'template' => 'default',
    'metadata' => '{}',
  ];

  // Status constants
  const STATUS_DRAFT = 'draft';
  const STATUS_SCHEDULED = 'scheduled';
  const STATUS_SENDING = 'sending';
  const STATUS_SENT = 'sent';
  const STATUS_CANCELLED = 'cancelled';

  // Template constants
  const TEMPLATE_DEFAULT = 'default';
  const TEMPLATE_MINIMAL = 'minimal';
  const TEMPLATE_FEATURED = 'featured';

  /**
   * Relationships
   */
  public function stories(): HasMany
  {
    return $this->hasMany(Story::class)->orderBy('order');
  }

  public function updates(): HasMany
  {
    return $this->hasMany(Update::class)->orderBy('order');
  }

  public function events(): HasMany
  {
    return $this->hasMany(Event::class)->orderBy('order');
  }

  /**
   * Unified content relationship
   */
  public function contents(): HasMany
  {
    return $this->hasMany(NewsletterContent::class)->orderBy('order');
  }

  public function contentStories(): HasMany
  {
    return $this->hasMany(NewsletterContent::class)->where('type', 'story')->orderBy('order');
  }

  public function contentEvents(): HasMany
  {
    return $this->hasMany(NewsletterContent::class)->where('type', 'event')->orderBy('order');
  }

  public function contentUpdates(): HasMany
  {
    return $this->hasMany(NewsletterContent::class)->where('type', 'update')->orderBy('order');
  }

  public function contentAnnouncements(): HasMany
  {
    return $this->hasMany(NewsletterContent::class)->where('type', 'announcement')->orderBy('order');
  }

  public function featuredContents(): HasMany
  {
    return $this->hasMany(NewsletterContent::class)->where('is_featured', true)->orderBy('order');
  }

  public function feedback(): HasMany
  {
    return $this->hasMany(NewsletterFeedback::class);
  }

  public function creator(): BelongsTo
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  public function publisher(): BelongsTo
  {
    return $this->belongsTo(User::class, 'published_by');
  }

  /**
   * Scopes
   */
  public function scopeDraft(Builder $query): void
  {
    $query->where('status', self::STATUS_DRAFT);
  }

  public function scopeScheduled(Builder $query): void
  {
    $query->where('status', self::STATUS_SCHEDULED);
  }

  public function scopeReadyToSend(Builder $query): void
  {
    $query->where('status', self::STATUS_SCHEDULED)
          ->where('scheduled_at', '<=', now());
  }

  public function scopeSent(Builder $query): void
  {
    $query->where('status', self::STATUS_SENT);
  }

  public function scopePublished(Builder $query): void
  {
    $query->whereIn('status', [self::STATUS_SENT])
          ->whereNotNull('published_at');
  }

  /**
   * Accessors & Mutators
   */
  public function statusLabel(): Attribute
  {
    return Attribute::make(
      get: fn () => match($this->status) {
        self::STATUS_DRAFT => 'Draft',
        self::STATUS_SCHEDULED => 'Scheduled',
        self::STATUS_SENDING => 'Sending',
        self::STATUS_SENT => 'Sent',
        self::STATUS_CANCELLED => 'Cancelled',
        default => 'Unknown'
      }
    );
  }

  public function statusColor(): Attribute
  {
    return Attribute::make(
      get: fn () => match($this->status) {
        self::STATUS_DRAFT => 'gray',
        self::STATUS_SCHEDULED => 'warning',
        self::STATUS_SENDING => 'info',
        self::STATUS_SENT => 'success',
        self::STATUS_CANCELLED => 'danger',
        default => 'gray'
      }
    );
  }

  public function isScheduled(): Attribute
  {
    return Attribute::make(
      get: fn () => $this->status === self::STATUS_SCHEDULED && $this->scheduled_at
    );
  }

  public function canBeSent(): Attribute
  {
    return Attribute::make(
      get: fn () => in_array($this->status, [self::STATUS_DRAFT, self::STATUS_SCHEDULED])
                   && $this->hasContent()
    );
  }

  public function totalContentItems(): Attribute
  {
    return Attribute::make(
      get: fn () => $this->stories()->count() + $this->updates()->count() + $this->events()->count()
    );
  }

  /**
   * Business Logic Methods
   */
  public function hasContent(): bool
  {
    return $this->stories()->exists() ||
           $this->updates()->exists() ||
           $this->events()->exists();
  }

  public function schedule(Carbon $scheduledAt): bool
  {
    if (!$this->canBeSent) {
      return false;
    }

    return $this->update([
      'status' => self::STATUS_SCHEDULED,
      'scheduled_at' => $scheduledAt,
    ]);
  }

  public function markAsSending(): bool
  {
    return $this->update([
      'status' => self::STATUS_SENDING,
      'sent_at' => now(),
    ]);
  }

  public function markAsSent(int $subscriberCount = null): bool
  {
    return $this->update([
      'status' => self::STATUS_SENT,
      'sent_at' => $this->sent_at ?? now(),
      'published_at' => $this->published_at ?? now(),
      'subscriber_count' => $subscriberCount ?? $this->subscriber_count,
      'published_by' => auth()->id(),
    ]);
  }

  public function cancel(): bool
  {
    if (!in_array($this->status, [self::STATUS_SCHEDULED])) {
      return false;
    }

    return $this->update([
      'status' => self::STATUS_CANCELLED,
      'scheduled_at' => null,
    ]);
  }

  public function generateIssueNumber(): int
  {
    $lastIssue = static::where('issue_number', '>', 0)
                      ->orderBy('issue_number', 'desc')
                      ->first();

    return $lastIssue ? $lastIssue->issue_number + 1 : 1;
  }

  public function updateMetrics(array $metrics): bool
  {
    $currentMetadata = $this->metadata ?? [];

    return $this->update([
      'open_rate' => $metrics['open_rate'] ?? $this->open_rate,
      'click_rate' => $metrics['click_rate'] ?? $this->click_rate,
      'metadata' => array_merge($currentMetadata, $metrics),
    ]);
  }

  /**
   * Media Collections
   */
  public function registerMediaCollections(): void
  {
    $this->addMediaCollection('featured_images')
      ->singleFile()
      ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp'])
      ->registerMediaConversions(function () {
        $this->addMediaConversion('thumbnail')
          ->width(400)
          ->height(300)
          ->optimize()
          ->nonQueued();

        $this->addMediaConversion('full')
          ->width(1200)
          ->height(900)
          ->optimize()
          ->nonQueued();

        $this->addMediaConversion('email')
          ->width(600)
          ->height(400)
          ->optimize()
          ->nonQueued();
      });
  }

  /**
   * Boot method for model events
   */
  protected static function boot(): void
  {
    parent::boot();

    static::creating(function ($issue) {
      if (!$issue->issue_number) {
        $issue->issue_number = $issue->generateIssueNumber();
      }

      if (!$issue->created_by) {
        $issue->created_by = auth()->id();
      }
    });
  }
}
