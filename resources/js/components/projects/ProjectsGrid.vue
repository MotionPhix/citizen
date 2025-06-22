<script setup lang="ts">
import { computed } from 'vue'
import BoundingContainer from '@/components/BoundingContainer.vue'
import ProjectCard from '@/components/projects/ProjectCard.vue'

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

const props = defineProps<{
  projects: {
    data: Project[]
    current_page: number
    first_page_url: string
    from?: string|null
    last_page: string
    last_page_url: string
    prev_page_url?: string|null
    next_page_url?: string|null
    per_page: number
    to?: string|null
    total?: number
    links: []
  }
  featured?: boolean
}>()

const sectionTitle = computed(() =>
  props.featured ? 'Featured Projects' : 'Our Latest Projects'
)

const sectionDescription = computed(() =>
  props.featured
    ? 'Explore our flagship initiatives that are making significant impacts in communities across Malawi.'
    : 'Browse through our complete portfolio of projects that are making a difference in communities.'
)
</script>

<template>
  <section :class="[
    'py-20',
    featured ? 'bg-gray-50 dark:bg-ca-primary/5' : 'bg-white dark:bg-ca-primary'
  ]">
    <BoundingContainer>
      <div
        class="text-center mb-16"
        v-motion
        :initial="{ opacity: 0, y: 20 }"
        :enter="{ opacity: 1, y: 0, transition: { duration: 0.5 } }">
        <h2 class="text-4xl md:text-5xl font-display text-ca-primary dark:text-white mb-4">
          {{ sectionTitle }}
        </h2>

        <p class="text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
          {{ sectionDescription }}
        </p>
      </div>

      <div
        class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 md:gap-8 lg:gap-12"
        v-motion
        :initial="{ opacity: 0, y: 40 }"
        :enter="{ opacity: 1, y: 0, transition: { stagger: 0.1 } }">
        <ProjectCard
          v-for="project in projects.data"
          :key="project?.uuid"
          :project="project"
        />
      </div>
    </BoundingContainer>
  </section>
</template>
