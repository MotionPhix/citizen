<script setup lang="ts">
import { ref } from 'vue'
import { useIntersectionObserver } from '@vueuse/core'
import GridPattern from '@/components/GridPattern.vue'
import BoundingContainer from '@/components/BoundingContainer.vue'
import CounterAnimation from '@/components/CounterAnimation.vue'

interface Stat {
  id: number
  number: number
  suffix?: string
  label: string
}

defineProps<{
  stats: Stat[]
}>()

const statsRef = ref<HTMLElement | null>(null)
const isVisible = ref(false)

useIntersectionObserver(statsRef, ([{ isIntersecting }]) => {
  if (isIntersecting) {
    isVisible.value = true
  }
})
</script>

<template>
  <section
    ref="statsRef"
    class="bg-white dark:bg-ca-secondary py-16 relative overflow-hidden">

    <GridPattern
      class="absolute inset-0 text-ca-primary/[0.02]"
      size="60"
    />

    <BoundingContainer>
      <div
        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8"
        v-motion
        :initial="{ opacity: 0, y: 20 }"
        :enter="{ opacity: 1, y: 0, transition: { stagger: 0.1 } }">

        <div
          v-for="stat in stats" :key="stat.id"
          class="bg-gray-100 dark:bg-ca-primary/20 rounded-2xl p-8 shadow-card hover:shadow-card-hover transition-all duration-300 transform hover:-translate-y-1"
        >
          <div class="flex items-center mb-4">
            <span class="text-4xl font-bold text-ca-primary dark:text-white">
              <CounterAnimation
                v-if="isVisible"
                :target-value="stat.number"
                :duration="2000"
              />

              <span v-if="stat.suffix">
                {{ stat.suffix }}
              </span>
            </span>
          </div>

          <p class="text-gray-600 dark:text-gray-300">
            {{ stat.label }}
          </p>
        </div>
      </div>
    </BoundingContainer>
  </section>
</template>
