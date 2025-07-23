<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { ref, computed } from 'vue'
import GuestLayout from '@/layouts/GuestLayout.vue'
import ProjectHero from '@/components/projects/ProjectHero.vue'
import ImpactStats from '@/components/projects/ImpactStats.vue'
import CallToAction from '@/components/projects/CallToAction.vue'
import ProjectCard from '@/components/projects/ProjectCard.vue'
import ProjectListItem from '@/components/projects/ProjectListItem.vue'
import { Grid, List, Filter, Search, SlidersHorizontal } from 'lucide-vue-next'
import { useStorage } from '@vueuse/core';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';

interface ImpactStat {
  id: number
  title: string
  value: string
  description: string
  icon: string
}

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

interface PaginationData {
  data: Project[]
  current_page: number
  last_page: number
  per_page: number
  total: number
  from: number
  to: number
  prev_page_url?: string
  next_page_url?: string
}

interface Props {
  projects: PaginationData
  featuredProjects: Project[]
  impactStats: ImpactStat[]
}

const props = defineProps<Props>()

// View mode state
const viewMode = useStorage<'grid' | 'list'>('projects_view_mode', 'grid')
const searchQuery = ref('')
const selectedStatus = ref<string>('all')
const showFilters = ref(false)

// Filter options
const statusOptions = [
  { value: 'all', label: 'All Projects' },
  { value: 'current', label: 'Current' },
  { value: 'completed', label: 'Completed' },
  { value: 'upcoming', label: 'Upcoming' }
]

// Filtered projects
const filteredProjects = computed(() => {
  let filtered = props.projects.data

  // Filter by search query
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(project =>
      project.title.toLowerCase().includes(query) ||
      project.description.toLowerCase().includes(query) ||
      project.location?.toLowerCase().includes(query)
    )
  }

  // Filter by status
  if (selectedStatus.value !== 'all') {
    filtered = filtered.filter(project => project.status === selectedStatus.value)
  }

  return filtered
})

// Toggle view mode
const toggleViewMode = () => {
  viewMode.value = viewMode.value === 'grid' ? 'list' : 'grid'
}

// Toggle filters
const toggleFilters = () => {
  showFilters.value = !showFilters.value
}

// Clear filters
const clearFilters = () => {
  searchQuery.value = ''
  selectedStatus.value = 'all'
  showFilters.value = false
}
</script>

<template>
  <Head>
    <title>Our Impact Projects</title>
    <meta
      name="description"
      content="Discover how Citizen Alliance is driving sustainable change across Malawi through innovative community development projects."
    />
  </Head>

  <GuestLayout>
    <!-- Full-width Hero Section -->
    <ProjectHero />

    <!-- Full-width Impact Stats -->
    <ImpactStats
      v-if="impactStats.length > 0"
      :stats="impactStats"
    />

    <!-- Featured Projects Section - Full Width Background -->
    <section
      v-if="featuredProjects.length > 0"
      class="py-16 bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800"
    >
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
          <h2 class="text-3xl md:text-4xl font-display font-bold text-gray-900 dark:text-white mb-4">
            Featured Projects
          </h2>
          <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
            Explore our flagship initiatives that are making significant impacts in communities across Malawi.
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
          <ProjectCard
            v-for="project in featuredProjects"
            :key="project.id"
            :project="project"
            :featured="true"
          />
        </div>
      </div>
    </section>

    <!-- Main Projects Section - Full Width Background -->
    <section class="py-16 bg-white dark:bg-gray-900">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-12">
          <h2 class="text-3xl md:text-4xl font-display font-bold text-gray-900 dark:text-white mb-4">
            All Projects
          </h2>
          <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
            Browse through our complete portfolio of projects that are making a difference in communities.
          </p>
        </div>

        <!-- Controls Bar -->
        <div class="flex flex-col sm:flex-row gap-4 mb-8">
          <!-- Search Bar -->
          <div class="flex-1 relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <Search class="h-5 w-5 text-gray-400" />
            </div>
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Search projects..."
              class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg leading-5 bg-white dark:bg-gray-800 dark:border-gray-600 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-ca-primary focus:border-ca-primary text-sm"
            />
          </div>

          <!-- View Mode Toggle -->
          <div class="flex items-center bg-gray-100 dark:bg-gray-800 rounded-lg p-1">
            <button
              @click="viewMode = 'grid'"
              :class="[
                'flex items-center px-3 py-2 rounded-md text-sm font-medium transition-colors',
                viewMode === 'grid'
                  ? 'bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm'
                  : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'
              ]"
            >
              <Grid class="h-4 w-4 mr-2" />
              Grid
            </button>
            <button
              @click="viewMode = 'list'"
              :class="[
                'flex items-center px-3 py-2 rounded-md text-sm font-medium transition-colors',
                viewMode === 'list'
                  ? 'bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm'
                  : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'
              ]"
            >
              <List class="h-4 w-4 mr-2" />
              List
            </button>
          </div>

          <!-- Filters Toggle -->
          <button
            @click="toggleFilters"
            :class="[
              'flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-colors border',
              showFilters
                ? 'bg-ca-primary text-white border-ca-primary'
                : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700'
            ]"
          >
            <SlidersHorizontal class="h-4 w-4 mr-2" />
            Filters
          </button>
        </div>

        <!-- Filters Panel -->
        <div
          v-if="showFilters"
          class="bg-gray-50 dark:bg-gray-800 rounded-lg p-6 mb-8 border border-gray-200 dark:border-gray-700"
        >
          <div class="flex flex-col sm:flex-row gap-4">
            <!-- Status Filter -->
            <div class="flex-1">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Project Status
              </label>

              <Select
                v-model="selectedStatus">
                <SelectTrigger class="w-full">
                  <SelectValue placeholder="Filter by status" />
                </SelectTrigger>

                <SelectContent>
                  <SelectItem
                    v-for="option in statusOptions"
                    :key="option.value"
                    :value="option.value">
                    {{ option.label }}
                  </SelectItem>
                </SelectContent>
              </Select>
            </div>

            <!-- Clear Filters -->
            <div class="flex items-end">
              <button
                @click="clearFilters"
                class="px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors"
              >
                Clear Filters
              </button>
            </div>
          </div>
        </div>

        <!-- Results Info -->
        <div class="flex justify-between items-center mb-6">
          <p class="text-sm text-gray-600 dark:text-gray-400">
            Showing {{ filteredProjects.length }} of {{ projects.total }} projects
          </p>
          <div class="text-sm text-gray-500 dark:text-gray-400">
            {{ viewMode === 'grid' ? 'Grid View' : 'List View' }}
          </div>
        </div>

        <!-- Projects Display -->
        <div v-if="filteredProjects.length > 0">
          <!-- Grid View -->
          <div
            v-if="viewMode === 'grid'"
            class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8"
          >
            <ProjectCard
              v-for="project in filteredProjects"
              :key="project.id"
              :project="project"
            />
          </div>

          <!-- List View -->
          <div
            v-else
            class="space-y-4"
          >
            <ProjectListItem
              v-for="project in filteredProjects"
              :key="project.id"
              :project="project"
            />
          </div>
        </div>

        <!-- No Results -->
        <div
          v-else
          class="text-center py-12"
        >
          <div class="mx-auto h-12 w-12 text-gray-400 mb-4">
            <Filter class="h-12 w-12" />
          </div>
          <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
            No projects found
          </h3>
          <p class="text-gray-500 dark:text-gray-400 mb-4">
            Try adjusting your search or filter criteria.
          </p>
          <button
            @click="clearFilters"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-ca-primary bg-ca-primary/10 hover:bg-ca-primary/20 transition-colors"
          >
            Clear all filters
          </button>
        </div>

        <!-- Pagination -->
        <div
          v-if="projects.last_page > 1 && filteredProjects.length > 0"
          class="mt-12 flex justify-center"
        >
          <nav class="flex items-center space-x-2">
            <a
              v-if="projects.prev_page_url"
              :href="projects.prev_page_url"
              class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700"
            >
              Previous
            </a>
            <span class="px-3 py-2 text-sm text-gray-700 dark:text-gray-300">
              Page {{ projects.current_page }} of {{ projects.last_page }}
            </span>
            <a
              v-if="projects.next_page_url"
              :href="projects.next_page_url"
              class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700"
            >
              Next
            </a>
          </nav>
        </div>
      </div>
    </section>

    <!-- Full-width Call to Action -->
    <CallToAction />
  </GuestLayout>
</template>
