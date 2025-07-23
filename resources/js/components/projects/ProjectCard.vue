<script setup lang="ts">
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import { ArrowRight, Calendar, MapPin, Users } from 'lucide-vue-next'
import { Badge } from '@/components/ui/badge'

interface Project {
  id: number
  title: string
  slug: string
  description: string
  featured_image_url?: string
  status: string
  location?: string
  start_date: string
  end_date?: string
  people_reached?: number
  budget?: number
  funded_by?: string
  tags?: Array<{ id: number; name: string }>
}

interface Props {
  project: Project
  featured?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  featured: false
})

// Get status badge variant
const statusVariant = computed(() => {
  switch (props.project.status) {
    case 'current':
      return 'default'
    case 'completed':
      return 'secondary'
    case 'upcoming':
      return 'outline'
    default:
      return 'secondary'
  }
})

// Format date
const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short'
  })
}

// Format number with commas
const formatNumber = (num: number) => {
  return new Intl.NumberFormat().format(num)
}
</script>

<template>
  <Link
    :href="route('projects.show', project.slug)"
    class="group block"
  >
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden border border-gray-200 dark:border-gray-700 hover:border-ca-primary/50 dark:hover:border-ca-highlight/50">
      <!-- Project Image -->
      <div class="relative aspect-w-16 aspect-h-10 overflow-hidden">
        <img
          v-if="project.featured_image_url"
          :src="project.featured_image_url"
          :alt="project.title"
          class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
        />
        <div
          v-else
          class="w-full h-full bg-gradient-to-br from-ca-primary/20 to-ca-highlight/20 flex items-center justify-center"
        >
          <span class="text-2xl font-bold text-ca-primary dark:text-ca-highlight">
            {{ project.title.charAt(0) }}
          </span>
        </div>

        <!-- Status Badge -->
        <div class="absolute -top-1 -left-1">
          <Badge :variant="statusVariant" class="rounded-none rounded-br-lg">
            {{ project.status.charAt(0).toUpperCase() + project.status.slice(1) }}
          </Badge>
        </div>

        <!-- Overlay for featured projects -->
        <div
          v-if="featured"
          class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"
        />
      </div>

      <!-- Project Content -->
      <div class="p-6">
        <!-- Header -->
        <div class="mb-3">
          <div class="flex items-center gap-2 mb-2 text-sm text-gray-500 dark:text-gray-400">
            <Calendar class="h-3 w-3" />
            <span>{{ formatDate(project.start_date) }}</span>
            <span v-if="project.location" class="flex items-center">
              <MapPin class="h-3 w-3 ml-2 mr-1" />
              {{ project.location }}
            </span>
          </div>

          <h3 class="text-lg font-display font-semibold text-gray-900 dark:text-white group-hover:text-ca-primary dark:group-hover:text-ca-highlight transition-colors duration-300 line-clamp-2">
            {{ project.title }}
          </h3>
        </div>

        <!-- Description -->
        <p class="truncate text-gray-600 dark:text-gray-400 text-sm line-clamp-3 mb-4">
          {{ project.description }}
        </p>

        <!-- Stats -->
        <div v-if="project.people_reached || project.funded_by" class="flex items-center gap-4 mb-4 text-xs text-gray-500 dark:text-gray-400">
          <div v-if="project.people_reached" class="flex items-center">
            <Users class="h-3 w-3 mr-1" />
            <span>{{ formatNumber(project.people_reached) }} people</span>
          </div>

          <div v-if="project.funded_by" class="truncate">
            <span>{{ project.funded_by }}</span>
          </div>
        </div>

        <!-- Tags -->
        <div v-if="project.tags && project.tags.length > 0" class="flex flex-wrap gap-1">
          <span
            v-for="tag in project.tags.slice(0, 2)"
            :key="tag.id"
            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
            {{ tag.name.en }}
          </span>

          <span
            v-if="project.tags.length > 2"
            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400"
          >
            +{{ project.tags.length - 2 }}
          </span>
        </div>
      </div>
    </div>
  </Link>
</template>
