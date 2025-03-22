<x-app-layout>
  <x-slot name="title">{{ $post->title }}</x-slot>
  <x-slot name="description">{{ $post->excerpt }}</x-slot>

  <article
    class="min-h-screen bg-gray-50 dark:bg-gray-900 pt-12 pb-24">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
      <div>
        <!-- Blog Post Header Component -->
        <blog-post-header
          title="{{ $post->title }}"
          :tags="@json($post->tags)"
          author="{{ $post->user->name }}"
          published-date="{{ $post->published_at->format('M d, Y') }}"
          likes-count="{{ $post->likes()->count() }}"
          reading-time="{{ $post->reading_time }}"
          view-count="{{ $post->view_count }}"
        />

        <!-- Featured Image -->
        @if($post->hasMedia('blog_images'))
          <div class="max-w-5xl mx-auto mb-12">
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
        <div class="max-w-4xl mx-auto">
          <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-8 md:p-12">
            <div class="prose dark:prose-invert prose-lg max-w-none">
              {!! $post->content !!}
            </div>

            <!-- Like Button -->
            <div class="mt-12 pt-8 border-t border-gray-200 dark:border-gray-700">
              <like-button
                :post-id="{{ $post->id }}"
                :initial-count="{{ $post->likes()->count() }}"
              />
            </div>
          </div>

          <!-- Comments Section -->
          <comments
            post-slug="{{ $post->slug }}"
            :initial-comments='@json($post->comments->load('user'))'
          />

          <!-- Related Posts -->
          @if($relatedPosts->isNotEmpty())
            <div class="mt-16">
              <h3 class="text-2xl font-display font-bold text-gray-900 dark:text-white mb-8">
                Related Posts
              </h3>

              <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($relatedPosts as $relatedPost)
                  <x-blog-post-card :post="$relatedPost"/>
                @endforeach
              </div>
            </div>
          @endif
        </div>
      </div>
    </div>

    <!-- Toast Messages -->
    <toast-messages />
  </article>
</x-app-layout>
