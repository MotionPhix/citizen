<script setup lang="ts">
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import { Calendar, MapPin, Users, DollarSign, ArrowRight } from 'lucide-vue-next'
import { Badge } from '@/components/ui/badge'

interface Project {
  id: number
  title: string
  slug: string
  description: string
  featured_image_url?: string
  status: string
  location: string
  start_date: string
  end_date?: string
  people_reached?: number
  budget?: number
  funded_by?: string
  tags?: Array<{ id: number; name: string }>
}

interface Props {
  project: Project
}

const props = defineProps<Props>()

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

// Format budget
const formatBudget = (budget: number) => {
  if (budget >= 1000000) {
    return `$${(budget / 1000000).toFixed(1)}M`
  } else if (budget >= 1000) {
    return `$${(budget / 1000).toFixed(0)}K`
  }
  return `$${budget}`
}
</script>

<template>
  <Link
    :href="route('projects.show', project.uuid)"
    class="group block">
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg hover:border-ca-primary/50 dark:hover:border-ca-highlight/50 transition-all duration-300">
      <div class="flex flex-col lg:flex-row gap-6">
        <!-- Project Image -->
        <div class="lg:w-48 lg:flex-shrink-0">
          <div class="aspect-w-16 aspect-h-10 lg:aspect-w-1 lg:aspect-h-1 rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-700">
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
              <span class="text-ca-primary dark:text-ca-highlight font-medium">
                {{ project.title.charAt(0) }}
              </span>
            </div>
          </div>
        </div>

        <!-- Project Content -->
        <div class="flex-1 min-w-0">
          <div class="flex flex-col h-full">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 mb-4">
              <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-2">
                  <Badge :variant="statusVariant">
                    {{ project.status.charAt(0).toUpperCase() + project.status.slice(1) }}
                  </Badge>
                  <span v-if="project.location" class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                    <MapPin class="h-3 w-3 mr-1" />
                    {{ project.location }}
                  </span>
                </div>
                <h3 class="text-xl font-display font-semibold text-gray-900 dark:text-white group-hover:text-ca-primary dark:group-hover:text-ca-highlight transition-colors duration-300 line-clamp-2">
                  {{ project.title }}
                </h3>
              </div>
            </div>

            <!-- Description -->
            <p class="truncate text-gray-600 dark:text-gray-400 line-clamp-3 mb-4 flex-1">
              {{ project.description }}
            </p>

            <!-- Project Stats -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
              <div v-if="project.start_date" class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                <Calendar class="h-4 w-4 mr-2 text-gray-400" />
                <span>{{ formatDate(project.start_date) }}</span>
              </div>

              <div v-if="project.people_reached" class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                <Users class="h-4 w-4 mr-2 text-gray-400" />
                <span>{{ formatNumber(project.people_reached) }} people</span>
              </div>

              <div v-if="project.budget" class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                <DollarSign class="h-4 w-4 mr-2 text-gray-400" />
                <span>{{ formatBudget(project.budget) }}</span>
              </div>

              <div v-if="project.funded_by" class="flex items-center text-sm text-gray-500 dark:text-gray-400 lg:col-span-1 col-span-2">
                <span class="truncate">{{ project.funded_by }}</span>
              </div>
            </div>

            <!-- Tags -->
            <div v-if="project.tags && project.tags.length > 0" class="flex flex-wrap gap-2 mb-4">
              <span
                v-for="tag in project.tags.slice(0, 3)"
                :key="tag.id"
                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300"
              >
                {{ tag.name.en }}
              </span>
              <span
                v-if="project.tags.length > 3"
                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400"
              >
                +{{ project.tags.length - 3 }} more
              </span>
            </div>

            <!-- Action -->
            <div class="flex items-center justify-between">
              <div class="flex items-center text-ca-primary dark:text-ca-highlight group-hover:text-ca-highlight dark:group-hover:text-white transition-colors duration-300">
                <span class="text-sm font-medium">View Project</span>
                <ArrowRight class="h-4 w-4 ml-1 group-hover:translate-x-1 transition-transform duration-300" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </Link>
</template>
