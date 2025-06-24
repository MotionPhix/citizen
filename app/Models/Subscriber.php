<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subscriber extends Model
{
  use HasFactory;

  protected $fillable = [
    'email',
    'name',
    'preferences',
    'status'
  ];

  protected $casts = [
    'preferences' => 'array',
    'last_sent_at' => 'datetime'
  ];

  public const FREQUENCIES = [
    'daily' => 'Daily',
    'weekly' => 'Weekly',
    'biweekly' => 'Every two weeks',
    'monthly' => 'Monthly',
    'quarterly' => 'Quarterly'
  ];

  public const CATEGORIES = [
    'news' => 'News & Updates',
    'events' => 'Events & Meetups',
    'announcements' => 'Important Announcements',
    'blog_posts' => 'Blog Posts',
    'projects' => 'Project Updates',
    'tutorials' => 'Tutorials & Guides'
  ];

  public const TIMEZONES = [
    'UTC' => 'UTC (Coordinated Universal Time)',
    'Africa/Blantyre' => 'Africa/Blantyre (CAT)',
    'Africa/Johannesburg' => 'Africa/Johannesburg (SAST)',
    'Africa/Lagos' => 'Africa/Lagos (WAT)',
    'Africa/Nairobi' => 'Africa/Nairobi (EAT)'
  ];

  public function feedback(): HasMany
  {
    return $this->hasMany(NewsletterFeedback::class);
  }

  public function getDefaultPreferences(): array
  {
    return [
      'frequency' => 'weekly',
      'categories' => ['news', 'announcements'],
      'format' => 'html',
      'timezone' => 'UTC',
      'time_of_day' => '08:00',
      'digest' => true,
      'notifications' => [
        'browser' => false,
        'mobile' => false
      ],
      'language' => 'en'
    ];
  }

  /**
   * Scope a query to only include active subscribers.
   */
  public function scopeActive(Builder $query): void
  {
    $query->where('status', 'active');
  }
}
