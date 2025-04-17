<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Newsletter extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'title',
    'content',
    'status',
    'scheduled_for',
    'sent_at',
    'sent_by',
  ];

  protected $casts = [
    'scheduled_for' => 'datetime',
    'sent_at' => 'datetime',
  ];

  public function sentBy()
  {
    return $this->belongsTo(User::class, 'sent_by');
  }

  public function subscribers()
  {
    return $this->belongsToMany(Subscriber::class, 'newsletter_sends')
      ->withTimestamps()
      ->withPivot(['status', 'error_message']);
  }

  public function scopeScheduled($query)
  {
    return $query->where('status', 'scheduled')
      ->where('scheduled_for', '>', now());
  }

  public function scopeDraft($query)
  {
    return $query->where('status', 'draft');
  }

  public function scopeSent($query)
  {
    return $query->where('status', 'sent');
  }
}
