<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ContactSubmission extends Model
{
  use HasFactory, Notifiable;

  protected $fillable = [
    'name',
    'email',
    'subject',
    'message',
    'status',
    'response',
    'ip_address',
    'user_agent',
  ];

  protected $casts = [
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
  ];

  public function scopeUnread($query)
  {
    return $query->where('status', 'unread');
  }

  public function markAsRead()
  {
    $this->update(['status' => 'read']);
  }

  public function routeNotificationForMail()
  {
    return $this->email;
  }
}
