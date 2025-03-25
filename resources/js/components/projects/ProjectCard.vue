<script setup lang="ts">
import { ArrowRight } from 'lucide-vue-next'
import { Badge } from '@/components/ui/badge'

interface Project {
  id: number
  title: string
  slug: string
  description: string
  status?: string
  image: string
  tags: string[]
  start_date: string
}

defineProps<{
  project: Project
}>()
</script>

<template>
  <a
    :href="`/projects/${project.slug}`"
    class="group block"
  >
    <div class="aspect-w-16 aspect-h-12 overflow-hidden bg-gray-100 rounded-2xl dark:bg-neutral-800">
      <img
        :src="project.image"
        :alt="project.title"
        class="group-hover:scale-105 transition-transform duration-500 ease-in-out object-cover rounded-2xl"
      >

      <div v-if="project.status" class="absolute top-4 right-4">
        <Badge variant="secondary" class="shadow-lg">
          {{ project.status }}
        </Badge>
      </div>
    </div>

    <div class="pt-4">
      <h3 class="relative font-display inline-block font-medium prose-xl before:absolute before:bottom-0.5 before:start-0 before:-z-[1] before:w-full before:h-1 before:bg-lime-400 before:transition before:origin-left before:scale-x-0 group-hover:before:scale-x-100 dark:text-white">
        {{ project.title }}
      </h3>

      <div class="mt-1 text-gray-600 dark:text-neutral-400 prose">
        {{ project.description }}
      </div>

      <div class="mt-3 flex flex-wrap gap-2">
        <Badge
          v-for="tag in project.tags"
          :key="tag"
          variant="outline"
          class="dark:border-neutral-700 dark:text-neutral-400"
        >
          {{ tag }}
        </Badge>
      </div>

      <div class="mt-4 flex items-center text-ca-primary dark:text-ca-highlight group-hover:text-ca-highlight dark:group-hover:text-white transition-colors duration-300">
        <span>Learn More</span>
        <ArrowRight class="w-4 h-4 ml-1" />
      </div>
    </div>
  </a>
</template>
