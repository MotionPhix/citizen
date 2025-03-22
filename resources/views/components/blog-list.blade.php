@props(['posts'])

<section class="py-16 bg-white dark:bg-ca-primary">
  <x-content-container>
    <h2 class="text-3xl font-bold text-center mb-12 text-ca-primary dark:text-ca-highlight">
      Latest News & Updates
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
      @foreach($posts as $post)
        <a href="{{ route('blog.show', $post->slug) }}"
           class="bg-white dark:bg-ca-secondary p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
          <div class="relative h-48 mb-6">
            <img src="{{ asset($post->image) }}"
                 alt="{{ $post->title }}"
                 class="w-full h-full object-cover rounded-lg">
          </div>
          <h3 class="text-xl font-semibold mb-2 text-ca-primary dark:text-white">
            {{ $post->title }}
          </h3>
          <p class="text-gray-600 dark:text-gray-300 mb-4">
            {{ $post->excerpt }}
          </p>
          <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
            <span>{{ $post->published_at->format('M d, Y') }}</span>
          </div>
        </a>
      @endforeach
    </div>
  </x-content-container>
</section>
