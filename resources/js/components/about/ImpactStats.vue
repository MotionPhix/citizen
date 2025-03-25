<script setup lang="ts">
import { ref, onMounted } from 'vue'
import CounterAnimation from '@/components/CounterAnimation.vue'

interface Stat {
  value: number
  suffix: string
  label: string
}

const stats: Stat[] = [
  { value: 50000, suffix: '+', label: 'People Reached' },
  { value: 85, suffix: '+', label: 'Communities Served' }
]

const isVisible = ref(false)
const statsRef = ref<HTMLElement | null>(null)

onMounted(() => {
  const observer = new IntersectionObserver(
    ([entry]) => {
      if (entry.isIntersecting) {
        isVisible.value = true
      }
    },
    { threshold: 0.2 }
  )

  if (statsRef.value) {
    observer.observe(statsRef.value)
  }
})
</script>

<template>
  <div
    ref="statsRef"
    class="absolute bottom-0 left-0 right-0 p-8"
  >
    <div class="grid grid-cols-2 gap-8">
      <div
        v-for="(stat, index) in stats"
        :key="index"
        class="text-white"
      >
        <p class="text-3xl font-bold flex items-end">
          <CounterAnimation
            v-if="isVisible"
            :target-value="stat.value"
            :duration="2000"
          />
          <span class="ml-1">{{ stat.suffix }}</span>
        </p>
        <p class="text-white/80">{{ stat.label }}</p>
      </div>
    </div>
  </div>
</template>
