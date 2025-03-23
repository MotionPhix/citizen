<div
  v-scope="heroSlider({{ json_encode($slides) }})"
  v-cloak
  @vue:mounted="initSwiper"
  class="relative h-[70vh] overflow-hidden"
>
  <!-- Swiper container -->
  <div class="swiper hero-swiper h-full">
    <div class="swiper-wrapper">
      <div
        v-for="(slide, index) in slides"
        :key="index"
        class="swiper-slide relative"
      >
        <!-- Parallax background -->
        <div
          class="absolute inset-0 bg-cover bg-center bg-no-repeat"
          :style="{
            backgroundImage: `url(${slide.image})`,
            transform: `translate3d(0, ${slideOffset[index]}px, 0)`
          }"
        ></div>

        <!-- Content overlay -->
        <div class="relative z-10 h-full flex items-center">
          <div
            class="container mx-auto px-4"
            :class="{'opacity-0 translate-y-8': !isActive(index)}"
            :style="{
              transition: 'opacity 0.8s ease, transform 0.8s ease',
              opacity: isActive(index) ? 1 : 0,
              transform: isActive(index) ? 'translateY(0)' : 'translateY(2rem)'
            }"
          >
            <!-- Title with animation -->
            <h2
              class="text-4xl md:text-6xl font-display text-white mb-4 opacity-0"
              :class="{'animate-fadeInUp': isActive(index)}"
              v-html="slide.title"
            ></h2>

            <!-- Description with animation -->
            <p
              class="text-lg md:text-xl text-white mb-8 max-w-2xl opacity-0"
              :class="{'animate-fadeInUp animation-delay-200': isActive(index)}"
              v-html="slide.description"
            ></p>

            <!-- CTA Button with animation -->
            <a
              v-if="slide.cta"
              :href="slide.cta.url"
              class="inline-flex items-center px-6 py-3 text-base font-medium rounded-md text-white bg-ca-primary hover:bg-ca-primary-dark transition-colors duration-300 opacity-0"
              :class="{'animate-fadeInUp animation-delay-400': isActive(index)}"
            >
              {{ slide.cta.text }}
              <svg
                class="ml-2 -mr-1 w-5 h-5"
                fill="currentColor"
                viewBox="0 0 20 20"
              >
                <path
                  fill-rule="evenodd"
                  d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                  clip-rule="evenodd"
                />
              </svg>
            </a>
          </div>
        </div>

        <!-- Gradient overlay -->
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-black/20"></div>
      </div>
    </div>

    <!-- Navigation -->
    <div class="absolute bottom-8 right-8 z-20 flex space-x-4">
      <button
        @click="prevSlide"
        class="p-2 rounded-full bg-white/20 text-white hover:bg-white/30 transition-colors duration-300"
      >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
      </button>
      <button
        @click="nextSlide"
        class="p-2 rounded-full bg-white/20 text-white hover:bg-white/30 transition-colors duration-300"
      >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
      </button>
    </div>

    <!-- Pagination -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 z-20">
      <div class="swiper-pagination"></div>
    </div>
  </div>
</div>
