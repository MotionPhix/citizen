<script setup lang="ts">
import { CheckCircle, Clock } from 'lucide-vue-next'

interface TimelineItem {
  year: string
  title: string
  description: string
}

interface Props {
  item: TimelineItem
  index: number
  isLast: boolean
}

const props = defineProps<Props>()

// Determine if this is a future item (for styling)
const isFuture = parseInt(props.item.year) > new Date().getFullYear()
</script>

<template>
  <div class="relative flex items-center">
    <!-- Timeline Node -->
    <div class="absolute left-1/2 transform -translate-x-1/2 z-10">
      <div :class="[
        'w-4 h-4 rounded-full border-4 border-white dark:border-gray-900',
        isFuture
          ? 'bg-gray-400 dark:bg-gray-600'
          : 'bg-ca-primary dark:bg-ca-highlight'
      ]"></div>
    </div>

    <!-- Content Card -->
    <div :class="[
      'w-full grid grid-cols-1 lg:grid-cols-2 gap-8 items-center',
      index % 2 === 0 ? 'lg:text-right' : ''
    ]">
      <!-- Year Badge (Left side for even, right side for odd) -->
      <div :class="[
        'flex justify-center lg:justify-start',
        index % 2 === 0 ? 'lg:order-1' : 'lg:order-2 lg:justify-end'
      ]">
        <div :class="[
          'inline-flex items-center px-4 py-2 rounded-full text-sm font-bold',
          isFuture
            ? 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400'
            : 'bg-ca-primary/10 dark:bg-ca-highlight/20 text-ca-primary dark:text-ca-highlight'
        ]">
          <component
            :is="isFuture ? Clock : CheckCircle"
            class="w-4 h-4 mr-2"
          />
          {{ item.year }}
        </div>
      </div>

      <!-- Content Card -->
      <div :class="[
        'bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-all duration-300',
        index % 2 === 0 ? 'lg:order-2' : 'lg:order-1'
      ]">
        <h3 class="text-xl font-display font-semibold text-gray-900 dark:text-white mb-3">
          {{ item.title }}
        </h3>
        <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
          {{ item.description }}
        </p>
      </div>
    </div>
  </div>
</template>
