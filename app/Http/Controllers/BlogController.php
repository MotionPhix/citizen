<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Tags\Tag;
use Inertia\Inertia;
use Inertia\Response;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Blog::published()
            ->with(['user', 'tags', 'media'])
            ->withCount(['comments' => function ($query) {
                $query->where('status', Comment::STATUS_APPROVED);
            }])
            ->orderBy('published_at', 'desc');

        // Search functionality
        if ($request->has('search') && $request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Tag filtering
        if ($request->has('tag') && $request->filled('tag')) {
            $query->withAnyTags([$request->input('tag')]);
        }

        // Category filtering (if you have categories)
        if ($request->has('category') && $request->filled('category')) {
            $query->whereHas('tags', function($q) use ($request) {
                $q->where('name', $request->input('category'));
            });
        }

        // Date filtering
        if ($request->has('month') && $request->filled('month')) {
            $month = $request->input('month');
            $query->whereMonth('published_at', $month);
        }

        if ($request->has('year') && $request->filled('year')) {
            $year = $request->input('year');
            $query->whereYear('published_at', $year);
        }

        // Sorting options
        $sortBy = $request->input('sort', 'latest');
        switch ($sortBy) {
            case 'popular':
                $query->orderBy('likes_count', 'desc');
                break;
            case 'views':
                $query->orderBy('view_count', 'desc');
                break;
            case 'oldest':
                $query->orderBy('published_at', 'asc');
                break;
            default:
                $query->orderBy('published_at', 'desc');
        }

        $posts = $query->paginate(12)->withQueryString();

        // Transform posts to include computed attributes
        $posts->getCollection()->transform(function ($post) {
            return $this->transformPostData($post);
        });

        // Get popular tags for filtering
        $popularTags = Tag::query()
            ->select(['tags.*', DB::raw('COUNT(DISTINCT blogs.id) as posts_count')])
            ->join('taggables', 'tags.id', '=', 'taggables.tag_id')
            ->join('blogs', function ($join) {
                $join->on('taggables.taggable_id', '=', 'blogs.id')
                    ->where('taggables.taggable_type', '=', Blog::class);
            })
            ->whereNotNull('blogs.published_at')
            ->where('blogs.published_at', '<=', now())
            ->groupBy('tags.id', 'tags.name', 'tags.type', 'tags.created_at', 'tags.updated_at', 'tags.order_column')
            ->having('posts_count', '>', 0)
            ->orderByDesc('posts_count')
            ->limit(10)
            ->get();

        // Get featured posts (just get the latest 3 posts for now)
        $featuredPosts = Blog::published()
            ->with(['user', 'tags', 'media'])
            ->withCount(['comments' => function ($query) {
                $query->where('status', Comment::STATUS_APPROVED);
            }])
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get()
            ->map(function ($post) {
                return $this->transformPostData($post);
            });

        // Get blog statistics
        $blogStats = [
            'total_posts' => Blog::published()->count(),
            'total_views' => Blog::published()->sum('view_count') ?: 0,
            'total_likes' => DB::table('likes')
                ->join('blogs', 'likes.likeable_id', '=', 'blogs.id')
                ->where('likes.likeable_type', Blog::class)
                ->where('blogs.is_published', true)
                ->count(),
            'total_comments' => Comment::whereHas('blog', function ($query) {
                $query->where('is_published', true);
            })->where('status', Comment::STATUS_APPROVED)->count(),
        ];

        return Inertia::render('blogs/Index', [
            'posts' => $posts,
            'featuredPosts' => $featuredPosts,
            'popularTags' => $popularTags,
            'blogStats' => $blogStats,
            'filters' => [
                'search' => $request->input('search'),
                'tag' => $request->input('tag'),
                'category' => $request->input('category'),
                'sort' => $sortBy,
            ]
        ]);
    }

    /**
     * Display the specified blog post.
     */
    public function show(string $slug)
    {
        $post = Blog::where('slug', $slug)
            ->published()
            ->with([
                'user',
                'tags',
                'media',
                'comments' => function ($query) {
                    $query->whereNull('parent_id')
                        ->where('status', Comment::STATUS_APPROVED)
                        ->with(['user'])
                        ->orderBy('created_at', 'desc');
                },
                'comments.replies' => function ($query) {
                    $query->where('status', Comment::STATUS_APPROVED)
                        ->with(['user'])
                        ->orderBy('created_at', 'asc');
                }
            ])
            ->withCount([
                'comments' => function ($query) {
                    $query->where('status', Comment::STATUS_APPROVED);
                },
                'likes'
            ])
            ->firstOrFail();

        // Increment the view count (with session-based tracking to prevent spam)
        $this->incrementViewCount($post);

        // Transform post data to include computed attributes
        $postData = $this->transformPostData($post, true);
        $postData['is_liked_by_user'] = auth()->check() ? $post->isLikedBy(auth()->user()) : false;

        // Transform comments to include proper display data
        $postData['comments'] = $post->comments->map(function ($comment) {
            return $this->transformCommentData($comment);
        });

        // Get related posts based on tags
        $relatedPosts = Blog::published()
            ->where('id', '!=', $post->id)
            ->withAnyTags($post->tags->pluck('name')->toArray())
            ->with(['user', 'media'])
            ->withCount([
                'comments' => function ($query) {
                    $query->where('status', Comment::STATUS_APPROVED);
                },
                'likes'
            ])
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get()
            ->map(function ($relatedPost) {
                return $this->transformPostData($relatedPost);
            });

        return Inertia::render('blogs/Show', [
            'post' => $postData,
            'relatedPosts' => $relatedPosts,
            'isAuthenticated' => auth()->check(),
            'currentUser' => auth()->user()
        ]);
    }

    /**
     * Transform post data for consistent API response
     */
    private function transformPostData($post, $includeContent = false)
    {
        $data = $post->toArray();

        // Add computed attributes
        $data['featured_image_url'] = $post->getFirstMediaUrl('blog_images');
        $data['featured_image_thumbnail_url'] = $post->getFirstMediaUrl('blog_images', 'thumbnail');
        $data['featured_image_preview_url'] = $post->getFirstMediaUrl('blog_images', 'preview');
        $data['featured_image_hero_url'] = $post->getFirstMediaUrl('blog_images', 'hero');

        // Calculate accurate reading time
        $data['reading_time'] = $this->calculateReadingTime($post->content);

        // Ensure view_count is set
        $data['view_count'] = $post->view_count ?? 0;

        // Add author data
        $data['author'] = $post->user;

        // Remove content for list views to reduce payload size
        if (!$includeContent) {
            unset($data['content']);
        }

        return $data;
    }

    /**
     * Transform comment data for frontend consumption
     */
    private function transformCommentData($comment)
    {
        $data = [
            'id' => $comment->id,
            'content' => $comment->content,
            'created_at' => $comment->created_at,
            'status' => $comment->status,
            'is_approved' => $comment->is_approved,
            'display_name' => $comment->display_name,
            'gravatar_url' => $comment->gravatar_url,
            'author_website' => $comment->author_website,
            'is_anonymous' => $comment->is_anonymous,
            'can_edit' => $comment->canEdit(),
            'can_delete' => $comment->canDelete(),
            'can_reply' => $comment->canReply(),
        ];

        // Add user data if authenticated comment
        if ($comment->user) {
            $data['user'] = [
                'id' => $comment->user->id,
                'name' => $comment->user->name,
                'avatar_url' => $comment->user->getFirstMediaUrl('avatar', 'thumb'),
            ];
        }

        // Add replies if they exist
        if ($comment->replies) {
            $data['replies'] = $comment->replies->map(function ($reply) {
                return $this->transformCommentData($reply);
            });
        }

        return $data;
    }

    /**
     * Calculate accurate reading time based on content
     */
    private function calculateReadingTime($content)
    {
        // Remove HTML tags and decode entities
        $text = html_entity_decode(strip_tags($content));

        // Count words (more accurate than str_word_count for international text)
        $wordCount = str_word_count($text);

        // Average reading speed is 200-250 words per minute
        // We'll use 200 for a conservative estimate
        $readingTime = ceil($wordCount / 200);

        // Minimum 1 minute
        return max(1, $readingTime);
    }

    /**
     * Increment view count with session-based tracking
     */
    private function incrementViewCount($post)
    {
        $sessionKey = 'blog_viewed_' . $post->id;

        // Only increment if not viewed in this session
        if (!session()->has($sessionKey)) {
            $post->increment('view_count');
            session()->put($sessionKey, true);
        }
    }
}
