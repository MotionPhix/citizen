@props(['media'])

<section class="py-16 bg-gray-50 dark:bg-ca-secondary">
  <x-content-container>
    <h2 class="text-3xl font-display text-ca-primary dark:text-white mb-8">
      Project Gallery
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      @foreach($media as $image)
        <div class="relative group aspect-w-16 aspect-h-9 rounded-2xl overflow-hidden bg-gray-100 dark:bg-gray-800">
          <img
            src="{{ $image->getUrl('preview') }}"
            alt="{{ $image->name }}"
            class="object-cover w-full h-full transition-transform duration-500 group-hover:scale-110"
          >
          <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            <div class="absolute bottom-0 left-0 right-0 p-4">
              <h3 class="text-white text-sm font-medium truncate">
                {{ $image->name }}
              </h3>
            </div>
          </div>
          <a
            href="{{ $image->getUrl() }}"
            class="absolute inset-0"
            data-fslightbox="gallery"
            aria-label="View full image"
          ></a>
        </div>
      @endforeach
    </div>
  </x-content-container>
</section>

@push('scripts')
  <script src="{{ asset('js/fslightbox.js') }}"></script>
@endpush
