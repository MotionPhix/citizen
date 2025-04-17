<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements HasMedia
{
  /** @use HasFactory<\Database\Factories\UserFactory> */
  use HasFactory, Notifiable, HasApiTokens, InteractsWithMedia;

  /**
   * The available user roles.
   */
  const ROLE_ADMIN = 'admin';
  const ROLE_USER = 'user';

  /**
   * The attributes that are mass assignable.
   *
   * @var list<string>
   */
  protected $fillable = [
    'name',
    'email',
    'password',
    'is_active',
    'role',
  ];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var list<string>
   */
  protected $hidden = [
    'password',
    'remember_token',
  ];

  /**
   * Get the attributes that should be cast.
   *
   * @return array<string, string>
   */
  protected function casts(): array
  {
    return [
      'email_verified_at' => 'datetime',
      'password' => 'hashed',
      'is_active' => 'boolean',
    ];
  }

  /**
   * Register the media collections for the user.
   */
  public function registerMediaCollections(): void
  {
    $this->addMediaCollection('avatar')
      ->singleFile()
      ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp'])
      ->registerMediaConversions(function () {
        $this->addMediaConversion('thumb')
          ->width(100)
          ->height(100)
          ->sharpen(10);

        $this->addMediaConversion('medium')
          ->width(300)
          ->height(300)
          ->sharpen(10);
      });
  }

  /**
   * Check if the user is an admin.
   */
  public function isAdmin(): bool
  {
    return $this->role === self::ROLE_ADMIN;
  }

  /**
   * Check if the user can manage posts.
   */
  public function canManagePosts(): bool
  {
    return $this->isAdmin();
  }

  /**
   * Check if the user can manage comments.
   */
  public function canManageComments(): bool
  {
    return $this->isAdmin();
  }

  /**
   * Check if the user can delete a specific comment.
   */
  public function canDeleteComment(Comment $comment): bool
  {
    return $this->isAdmin() || $comment->user_id === $this->id;
  }

  /**
   * Get all available user roles.
   *
   * @return array<string>
   */
  public static function getRoles(): array
  {
    return [
      self::ROLE_ADMIN,
      self::ROLE_USER,
    ];
  }

  /**
   * Get total likes across all user's posts.
   */
  public function totalPostLikes(): int
  {
    return $this->posts()
      ->withCount('likes')
      ->get()
      ->sum('likes_count');
  }

  /**
   * Get all posts by the user.
   */
  public function posts()
  {
    return $this->hasMany(Blog::class);
  }
}
