<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
  use HasFactory;

  protected $fillable = [
    'newsletter_issue_id',
    'title',
    'description',
    'location',
    'start_date',
    'end_date',
    'registration_url',
    'image',
    'capacity',
    'order',
  ];

  protected $casts = [
    'start_date' => 'datetime',
    'end_date' => 'datetime',
    'capacity' => 'integer',
  ];

  public function newsletterIssue(): BelongsTo
  {
    return $this->belongsTo(NewsletterIssue::class);
  }
}
