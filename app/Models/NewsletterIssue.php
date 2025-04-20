<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NewsletterIssue extends Model
{
  use HasFactory;

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
}
