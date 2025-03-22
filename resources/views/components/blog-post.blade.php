@props(['post'])

<section class="py-16 bg-white dark:bg-ca-primary">
  <x-content-container>
    <div class="max-w-4xl mx-auto">
      <h1 class="text-4xl font-bold mb-6 text-ca-primary dark:text-white">
        {{ $post->title }}
      </h1>
      <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-8">
        <span>{{ $post->published_at->format('M d, Y') }}</span>
      </div>
      <div class="relative h-96 mb-8">
        <img src="{{ asset($post->image) }}"
             alt="{{ $post->title }}"
             class="w-full h-full object-cover rounded-lg">
      </div>
      <div class="prose dark:prose-invert max-w-none">
        {!! $post->content !!}
      </div>
    </div>
  </x-content-container>
</section>
