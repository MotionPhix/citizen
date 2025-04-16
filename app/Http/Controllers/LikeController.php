<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LikeController extends Controller
{
  /**
   * Toggle like status for a blog post.
   */
  public function toggle(Request $request, Blog $blog): JsonResponse
  {
    try {
      $user = $request->user();

      // Check if user is the blog owner
      if ($blog->user_id === $user->id) {
        return response()->json([
          'message' => 'You cannot like your own blog post',
        ], 403);
      }

      if ($blog->isLikedBy($user)) {
        $blog->unlike($user);
        $message = 'Blog post unliked successfully';
      } else {
        $blog->like($user);
        $message = 'Blog post liked successfully';
      }

      return response()->json([
        'message' => $message,
        'likes_count' => $blog->likes()->count(),
        'is_liked' => $blog->isLikedBy($user),
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'message' => 'Failed to update like status'
      ], 500);
    }
  }
}
