<?php

namespace App\Models;

use App\Support\ContentFormatter;
use App\Traits\HasMediaUrls;
use App\Traits\Likeable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Tags\HasTags;

class Blog extends Model implements HasMedia
{
    use HasFactory, HasSlug, InteractsWithMedia, HasTags, HasMediaUrls, Likeable;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'published_at',
        'is_published',
        'user_id',
        'view_count',
    ];

    protected $casts = [
        'published_at' => 'date',
        'is_published' => 'boolean',
        'view_count' => 'integer',
    ];

    protected $appends = [
        'featured_image_url',
        'featured_image_thumbnail_url',
        'featured_image_preview_url',
        'featured_image_hero_url',
        'reading_time'
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        if ($media === null) {
            return;
        }

        $this->addMediaConversion('thumbnail')
            ->width(400)
            ->height(225)
            ->sharpen(10)
            ->optimize()
            ->performOnCollections('blog_images')
            ->nonQueued();

        $this->addMediaConversion('preview')
            ->width(800)
            ->height(450)
            ->sharpen(10)
            ->optimize()
            ->performOnCollections('blog_images')
            ->nonQueued();

        $this->addMediaConversion('hero')
            ->width(1920)
            ->height(1080)
            ->sharpen(10)
            ->optimize()
            ->performOnCollections('blog_images')
            ->nonQueued();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('blog_images')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp'])
            ->withResponsiveImages();
    }

    public function getContentAttribute($value): string
    {
        return ContentFormatter::formatContent($value);
    }

    // Helper method to get the featured image URL with different sizes
    public function getFeaturedImageAttribute()
    {
        if (!$this->hasMedia('blog_images')) {
            return null;
        }

        return [
            'thumbnail' => $this->getFirstMediaUrl('blog_images', 'thumbnail'),
            'preview' => $this->getFirstMediaUrl('blog_images', 'preview'),
            'hero' => $this->getFirstMediaUrl('blog_images', 'hero'),
            'original' => $this->getFirstMediaUrl('blog_images'),
        ];
    }

    // Helper methods for individual image URLs
    public function getFeaturedImageUrlAttribute()
    {
        return $this->getFirstMediaUrl('blog_images');
    }

    public function getFeaturedImageThumbnailUrlAttribute()
    {
        return $this->getFirstMediaUrl('blog_images', 'thumbnail');
    }

    public function getFeaturedImagePreviewUrlAttribute()
    {
        return $this->getFirstMediaUrl('blog_images', 'preview');
    }

    public function getFeaturedImageHeroUrlAttribute()
    {
        return $this->getFirstMediaUrl('blog_images', 'hero');
    }

    // Relationship to User (Author)
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function approvedComments(): HasMany
    {
        return $this->hasMany(Comment::class)->where('status', Comment::STATUS_APPROVED);
    }

    public function topLevelComments(): HasMany
    {
        return $this->hasMany(Comment::class)
            ->whereNull('parent_id')
            ->where('status', Comment::STATUS_APPROVED);
    }

    /**
     * Get all likes for the blog post
     */
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    /**
     * Check if the blog post is liked by a specific user
     */
    public function isLikedBy($user)
    {
        if (!$user) {
            return false;
        }

        return $this->likes()
            ->where('user_id', $user->id)
            ->exists();
    }

    // Add scope for published posts
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
            ->where('published_at', '<=', now());
    }

    // Add method to increment view count
    public function incrementViewCount()
    {
        $this->increment('view_count');
    }

    // Add method to get reading time - more accurate calculation
    public function getReadingTimeAttribute()
    {
        // Remove HTML tags and decode entities
        $text = html_entity_decode(strip_tags($this->attributes['content'] ?? ''));

        // Count words more accurately
        $wordCount = str_word_count($text);

        // Average reading speed is 200-250 words per minute
        // We'll use 200 for a conservative estimate
        $readingTime = ceil($wordCount / 200);

        // Minimum 1 minute
        return max(1, $readingTime);
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable')
            ->where('type', 'blog_tags');
    }

    protected static function boot()
    {
        parent::boot();

        // Add likes_count to every query
        static::addGlobalScope('withLikesCount', function ($builder) {
            $builder->withCount('likes');
        });
    }
}
