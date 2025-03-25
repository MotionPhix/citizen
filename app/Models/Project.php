<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Tags\HasTags;

class Project extends Model implements HasMedia
{
  use HasFactory, HasSlug, InteractsWithMedia, HasTags, SoftDeletes;

  protected $fillable = [
    'title',
    'slug',
    'description',
    'content',
    'start_date',
    'end_date',
    'funded_by',
    'status',
    'key_achievements',
    'people_reached',
    'budget',
    'meta_data',
    'is_featured',
    'order',
  ];

  protected $casts = [
    'key_achievements' => 'array',
    'meta_data' => 'array',
    'start_date' => 'date',
    'end_date' => 'date',
    'is_featured' => 'boolean',
  ];

  public function getSlugOptions(): SlugOptions
  {
    return SlugOptions::create()
      ->generateSlugsFrom('title')
      ->saveSlugsTo('slug');
  }

  public function registerMediaConversions(Media $media = null): void
  {
    if ($media === null) {
      return;
    }

    $this->addMediaConversion('thumbnail')
      ->fit(Fit::Crop, 300, 300)
      ->nonQueued();

    $this->addMediaConversion('preview')
      ->fit(Fit::Contain, 800, 600)
      ->nonQueued();

    $this->addMediaConversion('hero')
      ->fit(Fit::Max, 1920, 1080)
      ->nonQueued();
  }

  public function registerMediaCollections(): void
  {
    $this->addMediaCollection('project_image')
      ->singleFile()
      ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp'])
      ->withResponsiveImages();

    $this->addMediaCollection('project_gallery')
      ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp'])
      ->withResponsiveImages();
  }

  public function scopeFeatured($query)
  {
    return $query->where('is_featured', true);
  }

  public function scopeByStatus($query, $status)
  {
    return $query->where('status', $status);
  }

  // Helper method to get the featured image URL
  public function getFeaturedImageAttribute()
  {
    return $this->getFirstMediaUrl('project_image', 'preview') ?: null;
  }

  // Helper method to get the gallery images
  public function getGalleryImagesAttribute()
  {
    return $this->getMedia('project_gallery')
      ->map(fn($media) => [
        'id' => $media->id,
        'url' => $media->getUrl('preview'),
        'thumbnail' => $media->getUrl('thumbnail'),
        'original' => $media->getUrl(),
      ]);
  }
}
