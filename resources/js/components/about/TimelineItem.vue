<script setup lang="ts">
import { computed } from 'vue'
import { useTimelineContext } from './TimelineContext'
import { cn } from '@/lib/utils'

interface Props {
  year: string
  title: string
  description: string
  index: number
}

const props = defineProps<Props>()

const { activeIndex } = useTimelineContext()

const isActive = computed(() => activeIndex.value === props.index)
const shortYear = computed(() => props.year.slice(-2))

const getPositionClasses = computed(() => {
  const baseClasses = 'relative pl-8 sm:pl-32 py-6 group'
  const isFirst = props.index === 0
  const isLast = false // You'll need to pass total length to determine this

  return cn(baseClasses, {
    'opacity-50': !isActive.value,
    'scale-105': isActive.value
  })
})
</script>

<template>
  <div
    :class="getPositionClasses"
    @mouseenter="activeIndex = index"
  >
    <!-- Line -->
    <div
      class="hidden sm:block absolute left-0 top-0 h-full w-[1px] bg-gray-200 dark:bg-gray-700 group-last:h-1/2"
      :class="{ 'bg-ca-primary': isActive }"
    />

    <!-- Year -->
    <div
      class="absolute left-0 sm:left-16 top-6 flex items-center justify-center w-8 h-8 rounded-full font-bold text-sm transform -translate-x-1/2 transition-all duration-300"
      :class="[
        isActive
          ? 'bg-ca-primary text-white scale-125'
          : 'bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-400'
      ]"
    >
      {{ shortYear }}
    </div>

    <!-- Content -->
    <div
      class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 transform transition-all duration-300"
      :class="{ 'scale-105': isActive, '-translate-y-1': isActive }"
    >
      <time
        class="text-sm font-medium mb-1 block transition-colors duration-300"
        :class="[
          isActive
            ? 'text-ca-primary dark:text-ca-primary/90'
            : 'text-gray-500 dark:text-gray-400'
        ]"
      >
        {{ year }}
      </time>
      <h3 class="text-xl font-display font-semibold text-gray-900 dark:text-white mb-2">
        {{ title }}
      </h3>
      <p class="text-gray-600 dark:text-gray-300">
        {{ description }}
      </p>
    </div>
  </div>
</template>
