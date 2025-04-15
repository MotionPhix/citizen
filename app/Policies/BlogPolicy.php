<?php

namespace App\Policies;

use App\Models\Blog;
use App\Models\User;

class BlogPolicy
{
  /**
   * Determine if the user can manage blogs (admin operations).
   */
  public function manage(User $user): bool
  {
    return $user->isAdmin();
  }

  /**
   * Determine if the user can update the blog.
   */
  public function update(User $user, Blog $blog): bool
  {
    return $user->isAdmin();
  }

  /**
   * Determine if the user can delete the blog.
   */
  public function delete(User $user, Blog $blog): bool
  {
    return $user->isAdmin();
  }
}
