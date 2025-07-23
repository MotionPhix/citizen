<?php

namespace App\Http\Controllers;

use App\Models\Blog;
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
      $data = $post->toArray();
      $data['featured_image'] = $post->featured_image;
      $data['reading_time'] = $post->reading_time;
      $data['author'] = $post->user;
      return $data;
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
      ->orderBy('published_at', 'desc')
      ->take(3)
      ->get()
      ->map(function ($post) {
        $data = $post->toArray();
        $data['featured_image'] = $post->featured_image;
        $data['reading_time'] = $post->reading_time;
        $data['author'] = $post->user;
        return $data;
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
      'total_comments' => DB::table('comments')
        ->join('blogs', 'comments.blog_id', '=', 'blogs.id')
        ->where('blogs.is_published', true)
        ->count(),
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
   *
   * @param string $slug
   * @return \Illuminate\View\View
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
            ->with(['user', 'likes'])
            ->withCount('likes')
            ->orderBy('created_at', 'desc');
        },
        'comments.replies' => function ($query) {
          $query->with(['user', 'likes'])
            ->withCount('likes')
            ->orderBy('created_at', 'asc');
        }
      ])
      ->withCount(['comments', 'likes'])
      ->firstOrFail();

    // Increment the view count
    $post->incrementViewCount();

    // Transform post data to include computed attributes
    $postData = $post->toArray();
    $postData['featured_image'] = $post->featured_image;
    $postData['featured_image_url'] = $post->getFirstMediaUrl('blog_images');
    $postData['featured_image_thumbnail_url'] = $post->getFirstMediaUrl('blog_images', 'thumbnail');
    $postData['reading_time'] = $post->reading_time;
    $postData['view_count'] = $post->view_count;
    $postData['is_liked_by_user'] = auth()->check() ? $post->isLikedBy(auth()->user()) : false;

    // Get related posts based on tags
    $relatedPosts = Blog::published()
      ->where('id', '!=', $post->id)
      ->withAnyTags($post->tags->pluck('name')->toArray())
      ->with(['user', 'media'])
      ->withCount(['comments', 'likes'])
      ->orderBy('published_at', 'desc')
      ->take(3)
      ->get()
      ->map(function ($relatedPost) {
        $data = $relatedPost->toArray();
        $data['featured_image'] = $relatedPost->featured_image;
        $data['featured_image_url'] = $relatedPost->getFirstMediaUrl('blog_images');
        $data['featured_image_thumbnail_url'] = $relatedPost->getFirstMediaUrl('blog_images', 'thumbnail');
        $data['reading_time'] = $relatedPost->reading_time;
        return $data;
      });

    return Inertia::render('blogs/Show', [
      'post' => $postData,
      'relatedPosts' => $relatedPosts,
      'isAuthenticated' => auth()->check(),
      'currentUser' => auth()->user()
    ]);
  }

}
