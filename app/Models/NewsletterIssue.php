<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class NewsletterIssue extends Model implements HasMedia
{
  use HasFactory, InteractsWithMedia;

  protected $fillable = [
    'title',
    'description',
    'featured_image',
    'published_at',
    'status',
  ];

  protected $casts = [
    'published_at' => 'datetime',
  ];

  public function stories(): HasMany
  {
    return $this->hasMany(Story::class);
  }

  public function updates(): HasMany
  {
    return $this->hasMany(Update::class);
  }

  public function events(): HasMany
  {
    return $this->hasMany(Event::class);
  }

  public function registerMediaCollections(): void
  {
    $this->addMediaCollection('featured_images')
      ->singleFile()
      ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp'])
      ->registerMediaConversions(function () {
        $this->addMediaConversion('thumbnail')
          ->width(400)
          ->height(300);

        $this->addMediaConversion('full')
          ->width(1200)
          ->height(900);
      });
  }
}
