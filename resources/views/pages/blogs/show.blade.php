<x-app-layout>
  <x-slot name="title">{{ $post->title }}</x-slot>
  <x-slot name="description">{{ $post->excerpt }}</x-slot>

  <!-- Blog Article -->
  <div class="max-w-[85rem] px-4 sm:px-6 lg:px-8 mx-auto">
    <div class="grid lg:grid-cols-3 gap-y-8 lg:gap-y-0 lg:gap-x-6">
      <!-- Content -->
      <div class="lg:col-span-2">
        <div class="py-8 lg:pe-8">
          <div class="space-y-5 lg:space-y-8">
            <a class="inline-flex items-center gap-x-1.5 text-sm text-gray-600 decoration-2 hover:underline focus:outline-hidden focus:underline dark:text-blue-500" href="{{ route('blogs.index') }}">
              <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
              Back to Blog
            </a>

            <h2 class="text-3xl font-bold lg:text-5xl dark:text-white">
              {{ $post->title }}
            </h2>

            <div class="flex items-center gap-x-5">
              @if($post->tags->isNotEmpty())
                <div class="flex flex-wrap gap-2">
                  @foreach($post->tags as $tag)
                    <a class="inline-flex items-center gap-1.5 py-1 px-3 rounded-full text-xs sm:text-sm bg-gray-100 text-gray-800 hover:bg-gray-200 focus:outline-hidden focus:bg-gray-200 dark:bg-neutral-800 dark:text-neutral-200 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800" href="{{ route('blogs.index', ['tag' => $tag->slug]) }}">
                      {{ $tag->name }}
                    </a>
                  @endforeach
                </div>
              @endif
              <p class="text-xs sm:text-sm text-gray-800 dark:text-neutral-200">
                {{ $post->published_at?->format('M d, Y') }}
              </p>
            </div>

            @if($post->excerpt)
              <blockquote>
                <p class="text-lg text-gray-800 dark:text-neutral-200">
                  {{ $post->excerpt }}
                </p>
              </blockquote>
            @endif

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
                    loading="lazy"
                    width="1200"
                    height="675">
                </div>
              </div>
            @endif

            <!-- Article Content -->
            <div class="prose prose-lg max-w-none dark:prose-invert">
              {!! str($post->content)->markdown() !!}
            </div>

            <!-- Tags and Interactions -->
            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-y-5 lg:gap-y-0 mt-8 pt-8 border-t border-gray-200 dark:border-neutral-700">
              <!-- Tags -->
              @if($post->tags->isNotEmpty())
                <div class="flex flex-wrap gap-2">
                  @foreach($post->tags as $tag)
                    <a class="inline-flex items-center gap-1.5 py-2 px-3 rounded-full text-sm bg-gray-100 text-gray-800 hover:bg-gray-200 focus:outline-hidden focus:bg-gray-200 dark:bg-neutral-800 dark:text-neutral-200 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700" href="{{ route('blogs.index', ['tag' => $tag->slug]) }}">
                      {{ $tag->name }}
                    </a>
                  @endforeach
                </div>
              @endif

              <!-- Interaction Buttons -->
              <div class="flex justify-end items-center gap-x-1.5">
                <!-- Like Button -->
                <like-button
                  post-slug="{{ $post->slug }}"
                  :initial-count="{{ $post->likes()->count() }}"
                  :is-authenticated="{{ auth()->check() ? 'true' : 'false' }}"
                  :initial-is-liked="{{ auth()->check() ? $post->isLikedBy(auth()->user()) ? 'true' : 'false' : 'false' }}">
                </like-button>

                <div class="block h-3 border-e border-gray-300 mx-3 dark:border-neutral-600"></div>

                <!-- Comments Count -->
                <div class="hs-tooltip inline-block">
                  <button type="button" class="hs-tooltip-toggle flex items-center gap-x-2 text-sm text-gray-500 hover:text-gray-800 focus:outline-hidden focus:text-gray-800 dark:text-neutral-400 dark:hover:text-neutral-200 dark:focus:text-neutral-200">
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 21 1.9-5.7a8.5 8.5 0 1 1 3.8 3.8z"/></svg>
                    {{ $post->comments->count() }}
                    <span class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 transition-opacity inline-block absolute invisible z-10 py-1 px-2 bg-gray-900 text-xs font-medium text-white rounded-md shadow-2xs dark:bg-black" role="tooltip">
                      Comments
                    </span>
                  </button>
                </div>

                <div class="block h-3 border-e border-gray-300 mx-3 dark:border-neutral-600"></div>

                <!-- Share Button -->
                <share-button
                  url="{{ url()->current() }}"
                  title="{{ $post->title }}">
                </share-button>
              </div>
            </div>
          </div>

          <!-- Comments Section -->
          <div class="mt-8 pt-8 border-t border-gray-200 dark:border-neutral-700">
            <comments
              post-slug="{{ $post->slug }}"
              :is-authenticated="{{ auth()->check() ? 'true' : 'false' }}"
              :current-user='@json(auth()->user())'
              :initial-comments='@json($post->comments->load('user'))'>
            </comments>
          </div>
        </div>
      </div>
      <!-- End Content -->

      <!-- Sidebar -->
      <div class="lg:col-span-1 lg:w-full lg:h-full lg:bg-gradient-to-r lg:from-gray-50 lg:via-transparent lg:to-transparent dark:from-neutral-800">
        <div class="sticky top-10 start-0 py-8 lg:ps-8">
          <!-- Author Info -->
          <div class="group flex items-center gap-x-3 border-b border-gray-200 pb-8 mb-8 dark:border-neutral-700">
            <a class="block shrink-0 focus:outline-hidden" href="#">
              @if($post->user->getFirstMediaUrl('avatar'))
                <img class="size-10 rounded-full" src="{{ $post->user->getFirstMediaUrl('avatar', 'thumb') }}" alt="{{ $post->user->name }}">
              @else
                <div class="size-10 rounded-full bg-gray-200 dark:bg-neutral-700 flex items-center justify-center">
                  <span class="text-lg font-medium text-gray-600 dark:text-neutral-300">
                    {{ substr($post->user->name, 0, 1) }}
                  </span>
                </div>
              @endif
            </a>

            <div class="grow">
              <h5 class="text-sm font-semibold text-gray-800 dark:text-neutral-200">
                {{ $post->user->name }}
              </h5>
              <p class="text-sm text-gray-500 dark:text-neutral-500">
                {{ '@' . str($post->user->name)->lower()->slug() }}
              </p>
            </div>

            @if(auth()->check() && auth()->id() !== $post->user_id)
              <div class="grow">
                <div class="flex justify-end">
                  <button type="button" class="py-1.5 px-2.5 inline-flex items-center gap-x-2 text-xs font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-hidden focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
                    Follow
                  </button>
                </div>
              </div>
            @endif
          </div>

          <!-- Related Posts -->
          @if($relatedPosts->isNotEmpty())
            <div class="space-y-6">
              @foreach($relatedPosts as $relatedPost)
                <a class="group flex items-center gap-x-6 focus:outline-hidden" href="{{ route('blogs.show', $relatedPost->slug) }}">
                  <div class="grow">
                    <span class="text-sm font-bold text-gray-800 group-hover:text-blue-600 group-focus:text-blue-600 dark:text-neutral-200 dark:group-hover:text-blue-500 dark:group-focus:text-blue-500">
                      {{ $relatedPost->title }}
                    </span>
                    <p class="mt-1 text-sm text-gray-500 dark:text-neutral-500">
                      {{ $relatedPost->published_at?->format('M d, Y') }}
                    </p>
                  </div>

                  @if($relatedPost->hasMedia('blog_images'))
                    <div class="shrink-0 relative rounded-lg overflow-hidden size-20">
                      <img
                        class="size-full absolute top-0 start-0 object-cover rounded-lg"
                        src="{{ $relatedPost->getFirstMediaUrl('blog_images', 'thumbnail') }}"
                        alt="{{ $relatedPost->title }}"
                        loading="lazy"
                        width="80"
                        height="80">
                    </div>
                  @endif
                </a>
              @endforeach
            </div>
          @endif
        </div>
      </div>
      <!-- End Sidebar -->
    </div>
  </div>
  <!-- End Blog Article -->

  @push('scripts')
    <script>
      function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
          // Show a toast notification
          window.dispatchEvent(new CustomEvent('toast', {
            detail: {
              type: 'success',
              message: 'Link copied to clipboard!'
            }
          }));
        }).catch(() => {
          window.dispatchEvent(new CustomEvent('toast', {
            detail: {
              type: 'error',
              message: 'Failed to copy link'
            }
          }));
        });
      }
    </script>
  @endpush
</x-app-layout>
