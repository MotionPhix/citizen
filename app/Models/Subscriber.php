<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Subscriber extends Model
{
  use HasFactory, SoftDeletes, Notifiable;

  protected $fillable = [
    'email',
    'name',
    'status',
    'unsubscribed_at',
  ];

  protected $casts = [
    'unsubscribed_at' => 'datetime',
  ];

  public function newsletters()
  {
    return $this->belongsToMany(Newsletter::class, 'newsletter_sends')
      ->withTimestamps()
      ->withPivot(['status', 'error_message']);
  }

  public function scopeActive($query)
  {
    return $query->where('status', 'subscribed')
      ->whereNull('unsubscribed_at');
  }

  public function routeNotificationForMail()
  {
    return $this->email;
  }
}
