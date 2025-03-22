<x-app-layout>
  <x-slot name="title">Blog</x-slot>
  <x-slot name="description">Read the latest blog posts from Citizen Alliance.</x-slot>

  <div class="py-12 bg-gray-50 dark:bg-gray-900">
    <x-content-container>
      <!-- Hero Section -->
      <div class="text-center mb-16">
        <h1 class="text-4xl md:text-5xl font-display font-bold text-gray-900 dark:text-white mb-4">
          Our Latest Stories
        </h1>
        <p class="text-xl text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
          Stay informed with our latest news, insights, and updates about our initiatives and community impact.
        </p>
      </div>

      <!-- Search and Filters -->
      <div class="mb-12">
        <form action="{{ route('blogs.index') }}" method="GET" class="max-w-3xl mx-auto">
          <div class="relative flex items-center">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
              <x-heroicon-o-magnifying-glass class="h-5 w-5 text-gray-400" />
            </div>
            <input
              type="text" name="search"
              placeholder="Search stories..."
              value="{{ request('search') }}"
              class="w-full pl-12 pr-4 py-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-ca-primary focus:border-transparent transition duration-200 ease-in-out"
            >
          </div>
        </form>
      </div>

      <!-- Blog Posts Grid -->
      @if($posts->isEmpty())
        <div class="text-center py-16">
          <div class="mb-6">
            <x-heroicon-o-document-text class="h-16 w-16 text-gray-400 mx-auto" />
          </div>
          <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
            No posts found
          </h3>
          <p class="text-gray-600 dark:text-gray-400">
            We couldn't find any posts matching your search criteria.
          </p>
        </div>
      @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          @foreach($posts as $post)
            <article class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden group">
              <a href="{{ route('blogs.show', $post->slug) }}" class="block h-full">
                <div class="aspect-w-16 aspect-h-9 bg-gray-100 dark:bg-gray-700">
                  @if($post->hasMedia('blog_images'))
                    <img
                      src="{{ $post->getFirstMediaUrl('blog_images') }}"
                      alt="{{ $post->title }}"
                      class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                    >
                  @else
                    <div class="flex items-center justify-center h-full">
                      <x-heroicon-o-photo class="h-12 w-12 text-gray-400" />
                    </div>
                  @endif
                </div>

                <div class="p-6">
                  <!-- Tags -->
                  @if($post->tags->isNotEmpty())
                    <div class="flex flex-wrap gap-2 mb-4">
                      @foreach($post->tags as $tag)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-ca-primary/10 text-ca-primary">
                          {{ $tag->name }}
                        </span>
                      @endforeach
                    </div>
                  @endif

                  <h3 class="text-xl font-display font-bold text-gray-900 dark:text-white mb-3 group-hover:text-ca-primary transition-colors duration-200">
                    {{ $post->title }}
                  </h3>

                  <p class="text-gray-600 dark:text-gray-400 line-clamp-3 mb-4">
                    {{ $post->excerpt }}
                  </p>

                  <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center space-x-4 text-gray-500 dark:text-gray-400">
                      <span class="flex items-center">
                        <x-heroicon-o-calendar class="h-4 w-4 mr-1" />
                        {{ $post->published_at->format('M d, Y') }}
                      </span>
                      <span class="flex items-center">
                        <x-heroicon-o-heart class="h-4 w-4 mr-1" />
                        {{ $post->likes->count() }}
                      </span>
                    </div>

                    <span class="text-ca-primary font-medium group-hover:translate-x-1 transition-transform duration-200">
                      Read more →
                    </span>
                  </div>
                </div>
              </a>
            </article>
          @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-12">
          {{ $posts->links() }}
        </div>
      @endif
    </x-content-container>
  </div>
</x-app-layout>
