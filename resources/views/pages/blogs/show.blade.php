<x-app-layout>
  <x-slot name="title">{{ $post->title }}</x-slot>
  <x-slot name="description">{{ $post->excerpt }}</x-slot>

  <article class="min-h-screen bg-gray-50 dark:bg-gray-900 pt-12 pb-24">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Blog Post Header Component -->
      <section class="max-w-7xl mx-auto mb-12">
        <blog-post-header
          title="{{ $post->title }}"
          :tags="@json($post->tags)"
          author="{{ $post->user->name }}"
          published-date="{{ $post->published_at->format('M d, Y') }}"
          likes-count="{{ $post->likes()->count() }}"
          reading-time="{{ $post->reading_time }}"
          view-count="{{ $post->view_count }}">
        </blog-post-header>
      </section>

      <!-- Main Content Area with Sidebar -->
      <div class="max-w-7xl mx-auto">
        <div class="lg:grid lg:grid-cols-12 lg:gap-8">
          <!-- Main Content -->
          <div class="lg:col-span-8">
            <!-- Featured Image -->
            @if($post->hasMedia('blog_images'))
              <div class="mb-12">
                <div class="aspect-w-16 aspect-h-9 rounded-2xl overflow-hidden shadow-xl">
                  <img
                    src="{{ $post->getFirstMediaUrl('blog_images', 'preview') }}"
                    srcset="{{ $post->getFirstMediaUrl('blog_images', 'thumbnail') }} 400w,
                            {{ $post->getFirstMediaUrl('blog_images') }} 1200w"
                    sizes="(max-width: 768px) 100vw,
                           (max-width: 1200px) 85vw,
                           1200px"
                    alt="{{ $post->title }}"
                    class="w-full h-full object-cover"
                    loading="lazy">
                </div>
              </div>
            @endif

            <!-- Article Content -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-8 md:p-12">
              <div class="prose dark:prose-invert prose-lg max-w-none">
                {!! str($post->content)->markdown()->sanitizeHtml() !!}
              </div>

              <!-- Tags -->
              <div class="mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
                <div class="flex flex-wrap gap-2">
                  @foreach($post->tags as $tag)
                    <a
                      href="{{ route('blogs.index', ['tag' => $tag->slug]) }}"
                      class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600 transition"
                    >
                      #{{ $tag->name }}
                    </a>
                  @endforeach
                </div>
              </div>

              <!-- Like Button -->
              <div class="mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
                <like-button
                  post-slug="{{ $post->slug }}"
                  :initial-count="{{ $post->likes_count }}"
                  :is-authenticated="{{ auth()->check() ? 'true' : 'false' }}"
                  :initial-is-liked="{{ auth()->check() ? $post->isLikedBy(auth()->user()) ? 'true' : 'false' : 'false' }}"
                  :is-owner="{{ auth()->check() && $post->user_id === auth()->id() ? 'true' : 'false' }}">
                </like-button>
              </div>
            </div>

            <!-- Comments Section -->
            <comments
              post-slug="{{ $post->slug }}"
              :is-authenticated="{{ auth()->check() ? 'true' : 'false' }}"
              :current-user='@json(auth()->user())'
              :initial-comments='@json($post->comments->load('user'))'>
            </comments>
          </div>

          <!-- Sidebar -->
          <div class="lg:col-span-4 space-y-8 mt-8 lg:mt-0">
            <!-- Author Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6">
              <div class="flex items-center space-x-4 mb-4">
                @if($post->user->getFirstMediaUrl('avatar'))
                  <img
                    src="{{ $post->user->getFirstMediaUrl('avatar', 'thumb') }}"
                    alt="{{ $post->user->name }}"
                    class="h-16 w-16 rounded-full object-cover"
                  >
                @else
                  <div class="h-16 w-16 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                    <span class="text-2xl font-medium text-gray-600 dark:text-gray-300">
                      {{ substr($post->user->name, 0, 1) }}
                    </span>
                  </div>
                @endif

                <div>
                  <h3 class="font-display font-bold text-gray-900 dark:text-white">
                    {{ $post->user->name }}
                  </h3>
                  <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ '@' . str($post->user->name)->lower()->slug() }}
                  </p>
                </div>
              </div>

              @if($post->user->bio)
                <p class="text-gray-600 dark:text-gray-300 text-sm mb-4">
                  {{ $post->user->bio }}
                </p>
              @endif

              <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
                <div>
                  <span class="font-medium text-gray-900 dark:text-white">
                    {{ $post->user->posts()->published()->count() }}
                  </span>
                  posts
                </div>
                <div>
                  <span class="font-medium text-gray-900 dark:text-white">
                    {{ $post->user->totalPostLikes() }}
                  </span>
                  total likes
                </div>
              </div>
            </div>

            <!-- Table of Contents -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6">
              <h3 class="font-display font-bold text-gray-900 dark:text-white mb-4">
                Table of Contents
              </h3>
              <nav class="space-y-1 text-sm">
                <table-of-contents
                  content="{{ $post->content }}"
                  class="prose-toc">
                </table-of-contents>
              </nav>
            </div>

            <!-- Related Posts -->
            @if($relatedPosts->isNotEmpty())
              <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6">
                <h3 class="font-display font-bold text-gray-900 dark:text-white mb-4">
                  Related Posts
                </h3>
                <div class="space-y-6">
                  @foreach($relatedPosts as $relatedPost)
                    <div class="flex space-x-4">
                      @if($relatedPost->hasMedia('blog_images'))
                        <div class="flex-shrink-0">
                          <img
                            src="{{ $relatedPost->getFirstMediaUrl('blog_images', 'thumbnail') }}"
                            alt="{{ $relatedPost->title }}"
                            class="h-16 w-16 object-cover rounded-lg"
                          >
                        </div>
                      @endif
                      <div>
                        <h4 class="font-medium text-gray-900 dark:text-white line-clamp-2">
                          <a href="{{ route('blogs.show', $relatedPost->slug) }}" class="hover:text-primary-600 dark:hover:text-primary-400">
                            {{ $relatedPost->title }}
                          </a>
                        </h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                          {{ $relatedPost->published_at->format('M d, Y') }}
                          · {{ $relatedPost->reading_time }} min read
                        </p>
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>
            @endif

            <!-- Popular Tags -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6">
              <h3 class="font-display font-bold text-gray-900 dark:text-white mb-4">
                Popular Tags
              </h3>
              <div class="flex flex-wrap gap-2">
                @foreach($popularTags as $tag)
                  <a
                    href="{{ route('blogs.index', ['tag' => $tag->slug]) }}"
                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600 transition"
                  >
                    #{{ $tag->name }}
                    <span class="ml-1 text-gray-500 dark:text-gray-400">{{ $tag->posts_count }}</span>
                  </a>
                @endforeach
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </article>
</x-app-layout>
