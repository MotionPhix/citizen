<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount } from 'vue'
import { type Slide } from '@/types'
import { Button } from '@/components/ui/button'
import { cn } from '@/lib/utils'
import { ChevronRight } from 'lucide-vue-next'

interface Props {
  slides: Slide[]
}

const props = defineProps<Props>()
const activeSlide = ref(0)
let slideInterval: number | undefined

const transitionClasses = (index: number) => cn(
  'transition-all duration-300 absolute inset-0',
  {
    'opacity-0 transform translate-x-full': activeSlide.value !== index,
    'opacity-100 transform translate-x-0': activeSlide.value === index
  }
)

const startSlideShow = () => {
  slideInterval = window.setInterval(() => {
    activeSlide.value = (activeSlide.value + 1) % props.slides.length
  }, 5000)
}

onMounted(() => {
  startSlideShow()
})

onBeforeUnmount(() => {
  if (slideInterval) clearInterval(slideInterval)
})
</script>

<template>
  <section class="relative">
    <div class="relative w-full h-[700px] overflow-hidden">
      <div class="relative h-full">
        <div
          v-for="(slide, index) in slides"
          :key="index"
          v-show="activeSlide === index"
          :class="transitionClasses(index)"
        >
          <img
            :src="slide.image"
            :alt="slide.title"
            class="object-cover w-full h-full transform hover:scale-105 transition-transform duration-500"
          >

          <div class="absolute inset-0 bg-gradient-to-b from-black/50 to-black/70"></div>

          <div class="absolute inset-0 flex items-center">
            <div class="container mx-auto px-4">
              <div class="max-w-3xl text-white">
                <h1 class="text-4xl font-display md:text-6xl font-bold mb-4 animate-fade-in">
                  {{ slide.title }}
                </h1>
                <p class="text-xl md:text-2xl mb-8 animate-fade-in-delay">
                  {{ slide.description }}
                </p>
                <Button
                  variant="default"
                  size="lg"
                  class="bg-ca-purple hover:bg-ca-primary text-white"
                  asChild>
                  <a href="#about">
                    Learn More
                    <ChevronRight class="ml-2 h-5 w-5" />
                  </a>
                </Button>
              </div>
            </div>
          </div>
        </div>

        <!-- Slider Controls -->
        <div class="absolute bottom-5 left-0 right-0 flex justify-center space-x-2">
          <button
            v-for="(slide, index) in slides"
            :key="index"
            @click="activeSlide = index"
            :class="cn('w-3 h-3 rounded-full transition-all duration-300', {
              'bg-white': activeSlide === index,
              'bg-white/50': activeSlide !== index
            })"
          />
        </div>
      </div>
    </div>
  </section>
</template>
