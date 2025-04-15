<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
  /**
   * Store a new comment.
   */
  public function store(Request $request, Blog $blog): JsonResponse
  {
    $validated = $request->validate([
      'content' => ['required', 'string', 'min:3', 'max:1000'],
      'parent_id' => ['nullable', 'exists:comments,id']
    ]);

    try {
      $comment = $blog->comments()->create([
        'content' => $validated['content'],
        'user_id' => $request->user()->id,
        'parent_id' => $validated['parent_id'] ?? null,
        'is_approved' => true, // You might want to change this based on your moderation needs
      ]);

      // Load relations for the response
      $comment->load(['user', 'likes']);

      return response()->json([
        'message' => 'Comment posted successfully',
        'comment' => $comment,
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'message' => 'Failed to post comment'
      ], 500);
    }
  }

  /**
   * Update an existing comment.
   */
  public function update(Request $request, Comment $comment): JsonResponse
  {
    $validated = $request->validate([
      'content' => ['required', 'string', 'min:3', 'max:1000'],
    ]);

    try {
      $comment->update([
        'content' => $validated['content'],
      ]);

      return response()->json([
        'message' => 'Comment updated successfully',
        'comment' => $comment->fresh(['user', 'likes']),
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'message' => 'Failed to update comment'
      ], 500);
    }
  }

  /**
   * Delete a comment.
   */
  public function destroy(Comment $comment): JsonResponse
  {
    try {
      $comment->delete();

      return response()->json([
        'message' => 'Comment deleted successfully'
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'message' => 'Failed to delete comment'
      ], 500);
    }
  }
}
