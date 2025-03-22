@props(['post'])

<article class="group relative bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden hover:shadow-lg transition-shadow duration-300">
    <a href="{{ route('blogs.show', $post->slug) }}" class="block">
        @if($post->hasMedia('blog_images'))
            <div class="aspect-w-16 aspect-h-9 overflow-hidden">
                <img
                    src="{{ $post->featured_image['preview'] }}"
                    srcset="{{ $post->featured_image['thumbnail'] }} 400w,
                            {{ $post->featured_image['preview'] }} 800w,
                            {{ $post->featured_image['hero'] }} 1200w"
                    sizes="(max-width: 640px) 100vw,
                           (max-width: 768px) 50vw,
                           (max-width: 1024px) 33vw,
                           400px"
                    alt="{{ $post->title }}"
                    class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300"
                    loading="lazy"
                >
            </div>
        @else
            <div class="aspect-w-16 aspect-h-9 bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                <x-heroicon-o-photo class="w-12 h-12 text-gray-400" />
            </div>
        @endif
    </a>

    <div class="p-6">
        @if($post->tags->isNotEmpty())
            <div class="flex flex-wrap gap-2 mb-3">
                @foreach($post->tags as $tag)
                    <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-medium bg-ca-primary/10 text-ca-primary">
                        {{ $tag->name }}
                    </span>
                @endforeach
            </div>
        @endif

        <h3 class="text-xl font-display font-bold mb-2">
            <a href="{{ route('blogs.show', $post->slug) }}" class="text-gray-900 dark:text-white hover:text-ca-primary dark:hover:text-ca-primary">
                {{ $post->title }}
            </a>
        </h3>

        <p class="text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">
            {{ $post->excerpt }}
        </p>

        <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
            <div class="flex items-center space-x-4">
                <span class="flex items-center">
                    <x-heroicon-o-calendar class="w-4 h-4 mr-1" />
                    {{ $post->published_at?->format('M d, Y') }}
                </span>
                <span class="flex items-center">
                    <x-heroicon-o-heart class="w-4 h-4 mr-1" />
                    {{ $post->likes->count() }}
                </span>
            </div>
            <span class="text-ca-primary font-medium group-hover:translate-x-1 transition-transform duration-200">
                Read more →
            </span>
        </div>
    </div>
</article>
