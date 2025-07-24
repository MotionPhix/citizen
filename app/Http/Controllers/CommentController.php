<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Blog;
use App\Models\Comment;
use App\Notifications\CommentReplyNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class CommentController extends Controller
{
    /**
     * Store a new comment.
     */
    public function store(CommentRequest $request, Blog $blog): JsonResponse
    {
        $ip = $request->ip();
        $rateLimitKey = 'comment:' . $ip;

        // Rate limiting: 5 comments per hour per IP
        if (RateLimiter::tooManyAttempts($rateLimitKey, 5)) {
            $availableIn = RateLimiter::availableIn($rateLimitKey);
            return response()->json([
                'message' => 'Too many comments. Please try again in ' . ceil($availableIn / 60) . ' minutes.',
                'error_code' => 'RATE_LIMIT_EXCEEDED'
            ], 429);
        }

        try {
            $commentData = [
                'content' => $request->input('content'),
                'blog_id' => $blog->id,
                'parent_id' => $request->input('parent_id'),
                'ip_address' => $ip,
                'user_agent' => $request->userAgent(),
                'notify_on_reply' => $request->boolean('notify_on_reply', true),
            ];

            // If user is authenticated, use their info
            if (auth()->check()) {
                $commentData['user_id'] = auth()->id();
            } else {
                // Anonymous comment
                $commentData['author_name'] = $request->input('author_name');
                $commentData['author_email'] = $request->input('author_email');
                $commentData['author_website'] = $request->input('author_website');
            }

            // Calculate spam score
            $comment = new Comment($commentData);
            $spamScore = $comment->calculateSpamScore();
            $commentData['spam_score'] = $spamScore;

            // Auto-approve if spam score is low and user is trusted
            if ($spamScore < 0.3) {
                if (auth()->check() && auth()->user()->hasPermissionTo('create_comment')) {
                    $commentData['status'] = Comment::STATUS_APPROVED;
                    $commentData['approved_at'] = now();
                    $commentData['approved_by'] = auth()->id();
                } elseif (!auth()->check() && config('comments.auto_approve_anonymous', false)) {
                    $commentData['status'] = Comment::STATUS_APPROVED;
                    $commentData['approved_at'] = now();
                }
            }

            $comment = Comment::create($commentData);

            // Increment rate limit
            RateLimiter::hit($rateLimitKey, 3600); // 1 hour

            // Send notification to parent comment author if this is a reply
            if ($comment->parent_id && $comment->status === Comment::STATUS_APPROVED) {
                $this->notifyParentCommentAuthor($comment);
            }

            // Load relations for response
            $comment->load(['user', 'parent', 'blog']);

            $message = $comment->status === Comment::STATUS_APPROVED
                ? 'Comment posted successfully!'
                : 'Comment submitted for moderation. It will appear after approval.';

            return response()->json([
                'message' => $message,
                'comment' => [
                    'id' => $comment->id,
                    'content' => $comment->content,
                    'author_name' => $comment->display_name,
                    'author_email' => $comment->display_email,
                    'gravatar_url' => $comment->gravatar_url,
                    'created_at' => $comment->created_at,
                    'status' => $comment->status,
                    'is_approved' => $comment->is_approved,
                    'can_edit' => $comment->canEdit(),
                    'can_delete' => $comment->canDelete(),
                ],
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to create comment', [
                'error' => $e->getMessage(),
                'blog_id' => $blog->id,
                'ip' => $ip,
            ]);

            return response()->json([
                'message' => 'Failed to post comment. Please try again.',
                'error_code' => 'CREATION_FAILED'
            ], 500);
        }
    }

    /**
     * Update an existing comment.
     */
    public function update(CommentRequest $request, Comment $comment): JsonResponse
    {
        // Check if user can edit this comment
        if (!$comment->canEdit()) {
            return response()->json([
                'message' => 'You are not authorized to edit this comment.',
                'error_code' => 'UNAUTHORIZED'
            ], 403);
        }

        try {
            $updateData = [
                'content' => $request->input('content'),
            ];

            // If comment was auto-approved before, keep it approved
            // Otherwise, send back to moderation
            if ($comment->status === Comment::STATUS_APPROVED && auth()->check()) {
                // Keep approved status for authenticated users
            } else {
                $updateData['status'] = Comment::STATUS_PENDING;
                $updateData['approved_at'] = null;
                $updateData['approved_by'] = null;
            }

            $comment->update($updateData);

            return response()->json([
                'message' => 'Comment updated successfully.',
                'comment' => [
                    'id' => $comment->id,
                    'content' => $comment->content,
                    'status' => $comment->status,
                    'is_approved' => $comment->is_approved,
                ],
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to update comment', [
                'error' => $e->getMessage(),
                'comment_id' => $comment->id,
            ]);

            return response()->json([
                'message' => 'Failed to update comment.',
                'error_code' => 'UPDATE_FAILED'
            ], 500);
        }
    }

    /**
     * Delete a comment.
     */
    public function destroy(Comment $comment): JsonResponse
    {
        // Check if user can delete this comment
        if (!$comment->canDelete()) {
            return response()->json([
                'message' => 'You are not authorized to delete this comment.',
                'error_code' => 'UNAUTHORIZED'
            ], 403);
        }

        try {
            $comment->delete();

            return response()->json([
                'message' => 'Comment deleted successfully.'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to delete comment', [
                'error' => $e->getMessage(),
                'comment_id' => $comment->id,
            ]);

            return response()->json([
                'message' => 'Failed to delete comment.',
                'error_code' => 'DELETE_FAILED'
            ], 500);
        }
    }

    /**
     * Get comments for a blog post.
     */
    public function index(Request $request, Blog $blog): JsonResponse
    {
        try {
            $comments = $blog->comments()
                ->with(['user', 'replies.user'])
                ->approved()
                ->topLevel()
                ->orderBy('created_at', 'desc')
                ->paginate(20);

            $commentsData = $comments->map(function ($comment) {
                return $this->formatCommentForResponse($comment);
            });

            return response()->json([
                'comments' => $commentsData,
                'pagination' => [
                    'current_page' => $comments->currentPage(),
                    'last_page' => $comments->lastPage(),
                    'per_page' => $comments->perPage(),
                    'total' => $comments->total(),
                ],
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to fetch comments', [
                'error' => $e->getMessage(),
                'blog_id' => $blog->id,
            ]);

            return response()->json([
                'message' => 'Failed to load comments.',
                'error_code' => 'FETCH_FAILED'
            ], 500);
        }
    }

    /**
     * Reply to a comment via email link.
     */
    public function replyViaEmail(Request $request, Comment $comment): JsonResponse
    {
        // Verify token
        if ($request->input('token') !== $comment->reply_token) {
            return response()->json([
                'message' => 'Invalid reply token.',
                'error_code' => 'INVALID_TOKEN'
            ], 403);
        }

        $validated = $request->validate([
            'content' => 'required|string|min:10|max:2000',
            'author_name' => 'required|string|min:2|max:100',
            'author_email' => 'required|email|max:255',
        ]);

        try {
            $reply = Comment::create([
                'content' => $validated['content'],
                'blog_id' => $comment->blog_id,
                'parent_id' => $comment->id,
                'author_name' => $validated['author_name'],
                'author_email' => $validated['author_email'],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status' => Comment::STATUS_PENDING, // Always moderate email replies
            ]);

            return response()->json([
                'message' => 'Reply submitted for moderation.',
                'comment' => $this->formatCommentForResponse($reply),
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to create email reply', [
                'error' => $e->getMessage(),
                'parent_comment_id' => $comment->id,
            ]);

            return response()->json([
                'message' => 'Failed to submit reply.',
                'error_code' => 'REPLY_FAILED'
            ], 500);
        }
    }

    /**
     * Unsubscribe from reply notifications.
     */
    public function unsubscribe(Request $request, Comment $comment): JsonResponse
    {
        // Verify token
        if ($request->input('token') !== $comment->reply_token) {
            return response()->json([
                'message' => 'Invalid unsubscribe token.',
                'error_code' => 'INVALID_TOKEN'
            ], 403);
        }

        try {
            $comment->update(['notify_on_reply' => false]);

            return response()->json([
                'message' => 'Successfully unsubscribed from reply notifications.',
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to unsubscribe from notifications', [
                'error' => $e->getMessage(),
                'comment_id' => $comment->id,
            ]);

            return response()->json([
                'message' => 'Failed to unsubscribe.',
                'error_code' => 'UNSUBSCRIBE_FAILED'
            ], 500);
        }
    }

    /**
     * Send notification to parent comment author.
     */
    protected function notifyParentCommentAuthor(Comment $reply): void
    {
        try {
            $parentComment = $reply->parent;

            if (!$parentComment || !$parentComment->notify_on_reply) {
                return;
            }

            $email = $parentComment->display_email;
            if (!$email) {
                return;
            }

            // Send notification email
            Mail::to($email)->send(new CommentReplyNotification($reply, $parentComment));

            Log::info('Comment reply notification sent', [
                'parent_comment_id' => $parentComment->id,
                'reply_id' => $reply->id,
                'email' => $email,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send comment reply notification', [
                'error' => $e->getMessage(),
                'reply_id' => $reply->id,
            ]);
        }
    }

    /**
     * Format comment data for API response.
     */
    protected function formatCommentForResponse(Comment $comment): array
    {
        return [
            'id' => $comment->id,
            'content' => $comment->content,
            'author_name' => $comment->display_name,
            'author_website' => $comment->author_website,
            'gravatar_url' => $comment->gravatar_url,
            'created_at' => $comment->created_at,
            'status' => $comment->status,
            'is_approved' => $comment->is_approved,
            'can_edit' => $comment->canEdit(),
            'can_delete' => $comment->canDelete(),
            'can_reply' => $comment->canReply(),
            'replies' => $comment->replies->map(function ($reply) {
                return $this->formatCommentForResponse($reply);
            }),
        ];
    }
}
