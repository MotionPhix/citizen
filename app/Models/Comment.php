<?php

namespace App\Models;

use App\Traits\Likeable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
  use HasFactory, Likeable;

  protected $fillable = [
    'content',
    'user_id',
    'blog_id',
    'parent_id', // For nested comments
    'is_approved',
  ];

  protected $casts = [
    'is_approved' => 'boolean',
  ];

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  public function blog(): BelongsTo
  {
    return $this->belongsTo(Blog::class);
  }

  public function parent(): BelongsTo
  {
    return $this->belongsTo(Comment::class, 'parent_id');
  }

  public function replies()
  {
    return $this->hasMany(Comment::class, 'parent_id');
  }
}
