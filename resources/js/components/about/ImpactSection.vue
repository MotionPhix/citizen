<script setup lang="ts">
import { ref } from 'vue'
import { useIntersectionObserver } from '@vueuse/core'
import ImpactStats from './ImpactStats.vue'
import ImpactAchievement from './ImpactAchievement.vue'
import BoundingContainer from '@/components/BoundingContainer.vue'

const achievements = [
  {
    icon: 'academic-cap',
    title: 'Education',
    description: '32 schools supported with resources and training programs'
  },
  {
    icon: 'heart',
    title: 'Healthcare',
    description: '85 medical camps organized in rural communities'
  },
  {
    icon: 'users',
    title: 'Community',
    description: '450+ training sessions conducted for local leaders'
  }
]

const sectionRef = ref<HTMLElement | null>(null)
const isVisible = ref(false)

useIntersectionObserver(sectionRef, ([{ isIntersecting }]) => {
  if (isIntersecting) {
    isVisible.value = true
  }
}, {
  threshold: 0.2
})
</script>

<template>
  <section
    ref="sectionRef"
    class="relative py-24 overflow-hidden"
  >
    <BoundingContainer class="relative">
      <div class="lg:mx-auto lg:grid lg:grid-cols-2 lg:items-center lg:gap-24">
        <!-- Image -->
        <div
          class="relative h-[600px] overflow-hidden rounded-xl lg:order-last"
          :class="{ 'opacity-0 scale-110': !isVisible, 'opacity-100 scale-100': isVisible }"
          style="transition: all 1s ease-out"
        >
          <img
            src="/images/about-us.jpg"
            alt="Impact visualization"
            class="absolute inset-0 h-full w-full object-cover"
          >
          <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent" />

          <!-- Stats -->
          <ImpactStats />
        </div>

        <!-- Content -->
        <div class="relative pt-12 sm:pt-0 px-6 lg:px-0">
          <div class="mb-12">
            <div
              class="flex items-center space-x-2 text-ca-primary mb-6"
              :class="{ 'opacity-0 translate-y-5': !isVisible, 'opacity-100 translate-y-0': isVisible }"
              style="transition: all 0.5s ease-out"
            >
              <div class="w-12 h-1 bg-ca-primary rounded-full" />
              <p class="font-medium uppercase tracking-wide">Our Impact</p>
            </div>

            <h2
              class="text-3xl md:text-4xl font-display font-bold text-gray-900 dark:text-white mb-6"
              :class="{ 'opacity-0 translate-y-5': !isVisible, 'opacity-100 translate-y-0': isVisible }"
              style="transition: all 0.5s ease-out 0.3s"
            >
              Creating Lasting Change in Communities
            </h2>

            <p
              class="text-lg text-gray-600 dark:text-gray-300"
              :class="{ 'opacity-0 translate-y-5': !isVisible, 'opacity-100 translate-y-0': isVisible }"
              style="transition: all 0.5s ease-out 0.5s"
            >
              Through our programs and initiatives, we've touched thousands of lives, empowering individuals to take
              active roles in their communities' development and governance processes.
            </p>
          </div>

          <!-- Key Achievements -->
          <div class="space-y-8">
            <template v-for="(achievement, index) in achievements" :key="index">
              <div
                :class="{ 'opacity-0 translate-y-5': !isVisible, 'opacity-100 translate-y-0': isVisible }"
                :style="`transition: all 0.5s ease-out ${0.7 + (index * 0.2)}s`"
              >
                <ImpactAchievement
                  :achievement="achievement"
                  :delay="index"
                />
              </div>
            </template>
          </div>
        </div>
      </div>
    </BoundingContainer>
  </section>
</template>

<style scoped>
.opacity-0 {
  opacity: 0;
}
.opacity-100 {
  opacity: 1;
}
.translate-y-5 {
  transform: translateY(1.25rem);
}
.translate-y-0 {
  transform: translateY(0);
}
.scale-110 {
  transform: scale(1.1);
}
.scale-100 {
  transform: scale(1);
}
</style>
