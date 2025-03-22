<div x-data="carousel()" x-init="init()" class="relative h-[700px] overflow-hidden">
  <div class="absolute inset-0 z-0 transition-transform duration-1000"
       x-ref="carousel"
       :style="`transform: translateX(-${activeIndex * 100}%)`">
    @foreach($slides as $slide)
      <div class="absolute inset-0 w-full h-full">
        <img src="{{ asset($slide['image']) }}"
             alt="{{ $slide['alt'] }}"
             class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-black/40 flex items-center">
          <div class="container mx-auto px-4 text-center">
            <h2 class="text-4xl md:text-6xl font-bold text-white mb-6 animate-fade-in-up">
              {{ $slide['title'] }}
            </h2>
            <p class="text-xl text-white mb-8 max-w-2xl mx-auto">
              {{ $slide['description'] }}
            </p>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <!-- Navigation Controls -->
  <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 z-10 flex space-x-4">
    @foreach($slides as $index => $slide)
      <button
        @click="goTo({{ $index }})"
        class="w-3 h-3 rounded-full transition-colors duration-300"
        :class="activeIndex === {{ $index }} ? 'bg-ca-highlight w-6' : 'bg-white'">
      </button>
    @endforeach
  </div>
</div>

@push('scripts')
  <script>
    function carousel() {
      return {
        activeIndex: 0,
        slidesCount: {{ count($slides) }},
        init() {
          this.autoPlay();
        },
        next() {
          this.activeIndex = (this.activeIndex + 1) % this.slidesCount;
        },
        prev() {
          this.activeIndex = (this.activeIndex - 1 + this.slidesCount) % this.slidesCount;
        },
        goTo(index) {
          this.activeIndex = index;
        },
        autoPlay() {
          setInterval(() => this.next(), 7000);
        }
      }
    }
  </script>
@endpush
