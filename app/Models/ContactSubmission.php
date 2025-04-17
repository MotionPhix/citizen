<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactSubmission extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'email',
    'subject',
    'message',
    'status',
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
}
