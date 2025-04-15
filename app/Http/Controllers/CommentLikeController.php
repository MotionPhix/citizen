<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentLikeController extends Controller
{
  public function toggle(Request $request, Comment $comment): JsonResponse
  {
    dd($comment);

    $user = $request->user();

    if ($comment->likes()->where('user_id', $user->id)->exists()) {
      $comment->likes()->detach($user->id);
      $message = 'Comment unliked successfully';
    } else {
      $comment->likes()->attach($user->id);
      $message = 'Comment liked successfully';
    }

    return response()->json([
      'message' => $message,
      'likes_count' => $comment->likes()->count(),
      'is_liked' => $comment->likes()->where('user_id', $user->id)->exists(),
    ]);
  }
}
