<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NewsletterFeedback extends Model
{
  use HasFactory;

  protected $table = 'newsletter_feedback';

  protected $fillable = [
    'newsletter_issue_id',
    'subscriber_id',
    'rating',
    'comment'
  ];

  protected $casts = [
    'rating' => 'integer'
  ];

  public function issue(): BelongsTo
  {
    return $this->belongsTo(NewsletterIssue::class, 'newsletter_issue_id');
  }

  public function subscriber(): BelongsTo
  {
    return $this->belongsTo(Subscriber::class);
  }
}
