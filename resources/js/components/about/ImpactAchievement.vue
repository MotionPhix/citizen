<script setup lang="ts">
import {
  GraduationCap,
  Heart,
  Users
} from 'lucide-vue-next'

interface Achievement {
  icon: 'academic-cap' | 'heart' | 'users'
  title: string
  description: string
}

const props = defineProps<{
  achievement: Achievement
  delay: number
}>()

const icons = {
  'academic-cap': GraduationCap,
  'heart': Heart,
  'users': Users
}
</script>

<template>
  <div
    class="flex items-start space-x-4 transform transition-all duration-300 hover:-translate-y-1"
  >
    <div class="flex-shrink-0">
      <div class="w-10 h-10 bg-ca-primary/10 dark:bg-ca-primary/20 rounded-lg flex items-center justify-center">
        <component
          :is="icons[achievement.icon]"
          class="w-5 h-5 text-ca-primary"
        />
      </div>
    </div>
    <div>
      <h3 class="font-display font-semibold text-gray-900 dark:text-white mb-1">
        {{ achievement.title }}
      </h3>
      <p class="text-gray-600 dark:text-gray-300">
        {{ achievement.description }}
      </p>
    </div>
  </div>
</template>
