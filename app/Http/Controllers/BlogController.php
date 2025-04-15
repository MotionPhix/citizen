<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
  public function index(Request $request)
  {
    $query = Blog::published()
      ->with(['user', 'tags', 'media'])
      ->orderBy('published_at', 'desc');

    if ($request->has('search')) {
      $search = $request->input('search');
      $query->where(function($q) use ($search) {
        $q->where('title', 'like', "%{$search}%")
          ->orWhere('excerpt', 'like', "%{$search}%")
          ->orWhere('content', 'like', "%{$search}%");
      });
    }

    if ($request->has('tag')) {
      $query->withAnyTags([$request->input('tag')]);
    }

    $posts = $query->paginate(8)->withQueryString();

    return view('pages.blogs.index', compact('posts'));
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

    // Get related posts based on tags
    $relatedPosts = Blog::published()
      ->where('id', '!=', $post->id)
      ->withAnyTags($post->tags->pluck('name')->toArray())
      ->with(['user', 'media'])
      ->withCount(['comments', 'likes'])
      ->orderBy('published_at', 'desc')
      ->take(3)
      ->get();

    // Check if the current user has liked the post
    $isLiked = auth()->check() ? $post->isLikedBy(auth()->user()) : false;

    return view('pages.blogs.show', compact('post', 'relatedPosts', 'isLiked'));
  }

}
