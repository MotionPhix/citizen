<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { type Slide } from '@/types'
import { Button } from '@/components/ui/button'
import { ArrowRightIcon, ChevronRight, MoveLeftIcon } from 'lucide-vue-next'
import { Swiper, SwiperSlide } from 'swiper/vue'
import { Autoplay, EffectFade, Navigation } from 'swiper/modules'
import { gsap } from 'gsap'
import 'swiper/css'
import 'swiper/css/effect-fade'
import 'swiper/css/pagination'

interface Props {
  slides: Slide[]
}

defineProps<Props>()

// Track active slide for animations
const activeIndex = ref(0)

// Animation timeline reference
let tl = gsap.timeline()

// Function to animate slide content
const animateContent = (index: number) => {
  // Reset previous animations
  gsap.set(`.slide-${index} .slide-title`, {
    y: 50,
    opacity: 0
  })
  gsap.set(`.slide-${index} .slide-description`, {
    y: 30,
    opacity: 0
  })
  gsap.set(`.slide-${index} .slide-button`, {
    y: 20,
    opacity: 0
  })

  // Create new timeline
  tl = gsap.timeline()

  // Animate elements in sequence
  tl.to(`.slide-${index} .slide-title`, {
    y: 0,
    opacity: 1,
    duration: 0.8,
    ease: 'power3.out'
  })
    .to(`.slide-${index} .slide-description`, {
      y: 0,
      opacity: 1,
      duration: 0.8,
      ease: 'power3.out'
    }, '-=0.6')
    .to(`.slide-${index} .slide-button`, {
      y: 0,
      opacity: 1,
      duration: 0.8,
      ease: 'power3.out'
    }, '-=0.6')
}

// Handle slide change
const handleSlideChange = ({ activeIndex: newIndex }) => {
  activeIndex.value = newIndex
  animateContent(newIndex)
}

onMounted(() => {
  // Animate first slide on mount
  animateContent(0)
})
</script>

<template>
  <section class="relative">

    <Swiper
      :modules="[Autoplay, EffectFade, Navigation]"
      :slides-per-view="1"
      :effect="'fade'"
      :speed="1000"
      :autoplay="{
        delay: 5000,
        disableOnInteraction: false
      }"
      :navigation="{
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      }"
      @slideChange="handleSlideChange"
      class="h-[calc(100vh-5rem)]">
      <SwiperSlide
        v-for="(slide, index) in slides"
        :key="index"
        :class="`slide-${index}`"
        class="relative">
        <!-- Background Image with Parallax -->
        <div
          class="absolute inset-0 bg-cover bg-center bg-no-repeat transform transition-transform duration-[1.5s]"
          :style="{
            backgroundImage: `url(${slide.image})`,
            transform: activeIndex === index ? 'scale(1.1)' : 'scale(1)'
          }"
        />

        <!-- Overlay with custom gradient -->
        <div class="absolute inset-0 bg-gradient-to-b from-gray-900/50 via-gray-900/60 to-gray-900/80 dark:from-gray-950/60 dark:via-gray-950/70 dark:to-gray-950/90" />

        <!-- Content -->
        <div class="relative h-full flex items-end pb-16">
          <div class="container mx-auto px-4">
            <div class="max-w-5xl mx-auto">
              <!-- Title -->
              <h1
                class="slide-title text-4xl md:text-6xl font-display font-bold mb-6 text-white dark:text-gray-100"
                v-html="slide.title"
              />

              <!-- Description -->
              <p
                class="slide-description text-xl md:text-2xl mb-8 text-gray-200 dark:text-gray-300"
                v-text="slide.description"
              />

              <!-- CTA Button -->
              <div class="slide-button">
                <Button
                  variant="default"
                  size="lg"
                  class="bg-ca-primary hover:bg-ca-primary/90 dark:bg-ca-highlight dark:hover:bg-ca-highlight/90 text-white dark:text-gray-950"
                  asChild>
                  <a
                    size="lg"
                    :href="route('about')">
                    Learn More
                    <ChevronRight class="ml-2 h-5 w-5" />
                  </a>
                </Button>
              </div>
            </div>
          </div>
        </div>
      </SwiperSlide>
    </Swiper>

    <!-- Repositioned Navigation Controls -->
    <div class="absolute bottom-8 right-8 z-20 hidden md:flex items-center text-gray-400 hover:text-gray-50">
      <button
        class="swiper-button-prev !static flex h-12 w-24 items-center justify-center">
        <MoveLeftIcon
          class="h-6 w-6 transform transition-transform duration-300 group-hover:-translate-x-1"
        />
      </button>

      <button
        class="swiper-button-next !static flex h-12 w-16 items-center justify-center">
        <ArrowRightIcon
          class="h-6 w-6 transform transition-transform duration-300 group-hover:translate-x-1"
        />
      </button>
    </div>
  </section>
</template>

<style scoped>
.swiper {
  @apply w-full overflow-hidden;
}

/* Position pagination to the left */
:deep(.swiper-pagination) {
  @apply !left-8 !bottom-8 !text-left !right-auto;
}

/* Fade effect enhancement */
.swiper-slide-active {
  z-index: 10;
}
</style>
