<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements HasMedia, FilamentUser
{
  /** @use HasFactory<\Database\Factories\UserFactory> */
  use HasFactory, HasUuid, HasRoles, Notifiable, HasApiTokens, InteractsWithMedia;

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
    'metadata',
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
      'metadata' => 'array',
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

  #[Scope]
  protected function active(Builder $query): void
  {
    $query->where('is_active', true);
  }

  /**
   * Check if the user is an admin.
   */
  public function isAdmin(): bool
  {
    return $this->hasRole(['super-admin', 'admin']);
  }

  /**
   * Check if the user can manage posts.
   */
  public function canManagePosts(): bool
  {
    return $this->hasAnyPermission(['create blogs', 'edit blogs', 'delete blogs']);
  }

  /**
   * Check if the user can manage comments.
   */
  public function canManageComments(): bool
  {
    return $this->hasPermissionTo('moderate comments');
  }

  /**
   * Check if the user can delete a specific comment.
   */
  public function canDeleteComment(Comment $comment): bool
  {
    return $this->hasPermissionTo('delete comments') ||
      ($comment->user_id === $this->id && $this->hasPermissionTo('edit comments'));
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

  public static function boot()
  {
    parent::boot();

    static::creating(function ($user) {
      if (self::count() <= 0 && !$user->role) {
        $user->assignRole('super-admin');
      }
    });
  }

  public function canAccessPanel(Panel $panel): bool
  {
    return true; // You might want to adjust this based on your requirements
  }
}
