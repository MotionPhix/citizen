@props(['tags'])

<div class="flex flex-wrap gap-2">
  @foreach($tags as $tag)
    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-ca-primary/10 text-ca-primary">
            {{ $tag->name }}
      @if(isset($tag->blog_posts_count))
        <span class="ml-1 text-gray-500">({{ $tag->blog_posts_count }})</span>
      @endif
        </span>
  @endforeach
</div>
