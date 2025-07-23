<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class NewsletterContent extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'newsletter_issue_id',
        'type',
        'title',
        'excerpt',
        'content',
        'image',
        'url',
        'category',
        'metadata',
        'published_at',
        'order',
        'is_featured',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'metadata' => 'array',
        'is_featured' => 'boolean',
        'order' => 'integer',
    ];

    protected $attributes = [
        'order' => 0,
        'is_featured' => false,
        'metadata' => '{}',
    ];

    // Content type constants
    const TYPE_STORY = 'story';
    const TYPE_EVENT = 'event';
    const TYPE_UPDATE = 'update';
    const TYPE_ANNOUNCEMENT = 'announcement';

    // Category constants for updates
    const CATEGORY_NEWS = 'news';
    const CATEGORY_ANNOUNCEMENTS = 'announcements';
    const CATEGORY_UPDATES = 'updates';
    const CATEGORY_PROJECTS = 'projects';

    /**
     * Relationships
     */
    public function newsletterIssue(): BelongsTo
    {
        return $this->belongsTo(NewsletterIssue::class);
    }

    /**
     * Scopes for different content types
     */
    public function scopeStories(Builder $query): void
    {
        $query->where('type', self::TYPE_STORY);
    }

    public function scopeEvents(Builder $query): void
    {
        $query->where('type', self::TYPE_EVENT);
    }

    public function scopeUpdates(Builder $query): void
    {
        $query->where('type', self::TYPE_UPDATE);
    }

    public function scopeAnnouncements(Builder $query): void
    {
        $query->where('type', self::TYPE_ANNOUNCEMENT);
    }

    public function scopeFeatured(Builder $query): void
    {
        $query->where('is_featured', true);
    }

    public function scopeByCategory(Builder $query, string $category): void
    {
        $query->where('category', $category);
    }

    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('order')->orderBy('created_at');
    }

    /**
     * Accessors for metadata fields
     */
    public function eventData(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->type === self::TYPE_EVENT ? $this->metadata : null
        );
    }

    public function location(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->metadata['location'] ?? null
        );
    }

    public function startDate(): Attribute
    {
        return Attribute::make(
            get: fn () => isset($this->metadata['start_date'])
                ? \Carbon\Carbon::parse($this->metadata['start_date'])
                : null
        );
    }

    public function endDate(): Attribute
    {
        return Attribute::make(
            get: fn () => isset($this->metadata['end_date'])
                ? \Carbon\Carbon::parse($this->metadata['end_date'])
                : null
        );
    }

    public function registrationUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->metadata['registration_url'] ?? null
        );
    }

    public function capacity(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->metadata['capacity'] ?? null
        );
    }

    public function author(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->metadata['author'] ?? null
        );
    }

    public function readTime(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->metadata['read_time'] ?? $this->calculateReadTime()
        );
    }

    public function priority(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->metadata['priority'] ?? 'normal'
        );
    }

    /**
     * Type-specific helper methods
     */
    public function isStory(): bool
    {
        return $this->type === self::TYPE_STORY;
    }

    public function isEvent(): bool
    {
        return $this->type === self::TYPE_EVENT;
    }

    public function isUpdate(): bool
    {
        return $this->type === self::TYPE_UPDATE;
    }

    public function isAnnouncement(): bool
    {
        return $this->type === self::TYPE_ANNOUNCEMENT;
    }

    /**
     * Get type label for display
     */
    public function typeLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => match($this->type) {
                self::TYPE_STORY => 'Story',
                self::TYPE_EVENT => 'Event',
                self::TYPE_UPDATE => 'Update',
                self::TYPE_ANNOUNCEMENT => 'Announcement',
                default => 'Unknown'
            }
        );
    }

    /**
     * Get type color for badges
     */
    public function typeColor(): Attribute
    {
        return Attribute::make(
            get: fn () => match($this->type) {
                self::TYPE_STORY => 'primary',
                self::TYPE_EVENT => 'success',
                self::TYPE_UPDATE => 'warning',
                self::TYPE_ANNOUNCEMENT => 'danger',
                default => 'gray'
            }
        );
    }

    /**
     * Calculate reading time based on content
     */
    protected function calculateReadTime(): int
    {
        if (!$this->content) {
            return 1;
        }

        $wordCount = str_word_count(strip_tags($this->content));
        return max(1, ceil($wordCount / 200)); // 200 words per minute
    }

    /**
     * Get available content types
     */
    public static function getTypes(): array
    {
        return [
            self::TYPE_STORY => 'Story',
            self::TYPE_EVENT => 'Event',
            self::TYPE_UPDATE => 'Update',
            self::TYPE_ANNOUNCEMENT => 'Announcement',
        ];
    }

    /**
     * Get available categories
     */
    public static function getCategories(): array
    {
        return [
            self::CATEGORY_NEWS => 'News',
            self::CATEGORY_ANNOUNCEMENTS => 'Announcements',
            self::CATEGORY_UPDATES => 'Updates',
            self::CATEGORY_PROJECTS => 'Projects',
        ];
    }

    /**
     * Media collections
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp'])
            ->registerMediaConversions(function (?Media $media = null) {
                $this->addMediaConversion('thumbnail')
                    ->width(400)
                    ->height(300)
                    ->optimize()
                    ->nonQueued();

                $this->addMediaConversion('preview')
                    ->width(800)
                    ->height(600)
                    ->optimize()
                    ->nonQueued();
            });
    }

    /**
     * Get featured image URL
     */
    public function featuredImageUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getFirstMediaUrl('images') ?: $this->image
        );
    }

    /**
     * Get featured image thumbnail URL
     */
    public function featuredImageThumbnailUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getFirstMediaUrl('images', 'thumbnail') ?: $this->image
        );
    }

    /**
     * Boot method for model events
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($content) {
            if (!$content->published_at) {
                $content->published_at = now();
            }
        });
    }

    /**
     * Get content for newsletter template
     */
    public function getTemplateData(): array
    {
        $data = [
            'id' => $this->id,
            'type' => $this->type,
            'title' => $this->title,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'image' => $this->featured_image_url,
            'thumbnail' => $this->featured_image_thumbnail_url,
            'url' => $this->url,
            'category' => $this->category,
            'order' => $this->order,
            'is_featured' => $this->is_featured,
            'published_at' => $this->published_at,
        ];

        // Add type-specific data
        if ($this->isEvent()) {
            $data['event'] = [
                'location' => $this->location,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'registration_url' => $this->registration_url,
                'capacity' => $this->capacity,
            ];
        }

        if ($this->isStory()) {
            $data['story'] = [
                'author' => $this->author,
                'read_time' => $this->read_time,
            ];
        }

        if ($this->isUpdate()) {
            $data['update'] = [
                'priority' => $this->priority,
            ];
        }

        return $data;
    }
}
