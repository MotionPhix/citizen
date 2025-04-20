<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Story extends Model
{
  use HasFactory;

  protected $fillable = [
    'newsletter_issue_id',
    'title',
    'excerpt',
    'content',
    'image',
    'url',
    'published_at',
    'order',
  ];

  protected $casts = [
    'published_at' => 'datetime',
  ];

  public function newsletterIssue(): BelongsTo
  {
    return $this->belongsTo(NewsletterIssue::class);
  }
}
