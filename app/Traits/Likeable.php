<?php

namespace App\Traits;

use App\Models\Like;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Likeable
{
  public function likes(): MorphMany
  {
    return $this->morphMany(Like::class, 'likeable');
  }

  public function isLikedBy($user): bool
  {
    if (!$user) {
      return false;
    }

    return $this->likes()
      ->where('user_id', $user->id)
      ->exists();
  }

  public function like($user): void
  {
    if (!$this->isLikedBy($user)) {
      $this->likes()->create(['user_id' => $user->id]);
    }
  }

  public function unlike($user): void
  {
    $this->likes()
      ->where('user_id', $user->id)
      ->delete();
  }

  public function toggleLike($user): void
  {
    if ($this->isLikedBy($user)) {
      $this->unlike($user);
    } else {
      $this->like($user);
    }
  }
}
