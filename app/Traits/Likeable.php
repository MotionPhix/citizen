<?php

namespace App\Traits;

use App\Models\Like;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Likeable
{
  /**
   * Get all likes for this model
   */
  public function likes(): MorphMany
  {
    return $this->morphMany(Like::class, 'likeable');
  }

  /**
   * Add a like from a user
   */
  public function like(User $user): void
  {
    if (!$this->isLikedBy($user)) {
      $this->likes()->create([
        'user_id' => $user->id,
      ]);
    }
  }

  /**
   * Remove a like from a user
   */
  public function unlike(User $user): void
  {
    $this->likes()
      ->where('user_id', $user->id)
      ->delete();
  }

  /**
   * Toggle the like status for a user
   */
  public function toggleLike(User $user): void
  {
    if ($this->isLikedBy($user)) {
      $this->unlike($user);
    } else {
      $this->like($user);
    }
  }

  /**
   * Check if the model is liked by a user
   */
  public function isLikedBy(User $user): bool
  {
    return $this->likes()
      ->where('user_id', $user->id)
      ->exists();
  }

  /**
   * Get the total number of likes
   */
  public function getLikesCountAttribute(): int
  {
    return $this->likes()->count();
  }
}
