<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { computed } from 'vue'
import GuestLayout from '@/layouts/GuestLayout.vue'
import ContentSection from '@/components/ContentSection.vue'
import ProjectCard from '@/components/projects/ProjectCard.vue'
import SocialShare from '@/components/SocialShare.vue'
import { Badge } from '@/components/ui/badge'
import {
  Calendar,
  MapPin,
  Users,
  DollarSign,
  Building,
  Clock,
  Check,
  HandHeart,
  ArrowLeft,
  Target,
  TrendingUp,
  Award
} from 'lucide-vue-next'

interface Tag {
  id: number
  name: string
}

interface GalleryImage {
  id: number
  url: string
  thumbnail: string
  original: string
}

interface Project {
  id: number
  title: string
  slug: string
  description: string
  content: string
  status: string
  start_date: string
  end_date?: string
  funded_by?: string
  people_reached?: number
  budget?: number
  key_achievements?: string[]
  meta_data?: Record<string, any>
  tags: Tag[]
  featured_image_url?: string
  gallery_images: GalleryImage[]
  location?: string
}

interface Props {
  project: Project
  relatedProjects: Project[]
}

const props = defineProps<Props>()

// Computed properties
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

const statusColor = computed(() => {
  switch (props.project.status) {
    case 'current':
      return 'text-green-600 bg-green-50 border-green-200'
    case 'completed':
      return 'text-blue-600 bg-blue-50 border-blue-200'
    case 'upcoming':
      return 'text-orange-600 bg-orange-50 border-orange-200'
    default:
      return 'text-gray-600 bg-gray-50 border-gray-200'
  }
})

const projectDuration = computed(() => {
  if (!props.project.start_date) return null

  const start = new Date(props.project.start_date)
  const end = props.project.end_date ? new Date(props.project.end_date) : new Date()
  const diffTime = Math.abs(end.getTime() - start.getTime())
  const diffMonths = Math.ceil(diffTime / (1000 * 60 * 60 * 24 * 30))

  return diffMonths
})

const progressPercentage = computed(() => {
  if (!props.project.start_date || props.project.status === 'upcoming') return 0
  if (props.project.status === 'completed') return 100

  const start = new Date(props.project.start_date)
  const now = new Date()
  const end = props.project.end_date ? new Date(props.project.end_date) : new Date(start.getTime() + (365 * 24 * 60 * 60 * 1000)) // Default 1 year

  const totalDuration = end.getTime() - start.getTime()
  const elapsed = now.getTime() - start.getTime()

  return Math.min(Math.max((elapsed / totalDuration) * 100, 0), 100)
})

// Helper functions
const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

const formatNumber = (num: number) => {
  return new Intl.NumberFormat().format(num)
}

const formatBudget = (budget: number) => {
  if (budget >= 1000000) {
    return `$${(budget / 1000000).toFixed(1)}M`
  } else if (budget >= 1000) {
    return `$${(budget / 1000).toFixed(0)}K`
  }
  return `$${formatNumber(budget)}`
}
</script>

<template>
  <Head>
    <title>{{ project.title }}</title>
    <meta
      name="description"
      :content="project.description"
    />
    <meta property="og:title" :content="project.title" />
    <meta property="og:description" :content="project.description" />
    <meta property="og:image" :content="project.featured_image_url" />
  </Head>

  <GuestLayout>
    <!-- Hero Section -->
    <section class="relative min-h-[70vh] flex items-end bg-gradient-to-br from-ca-primary to-ca-highlight overflow-hidden">
      <!-- Background Image -->
      <div class="absolute inset-0">
        <img
          v-if="project.featured_image_url"
          :src="project.featured_image_url"
          :alt="project.title"
          class="w-full h-full object-cover"
        />
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
      </div>

      <!-- Content -->
      <div class="relative w-full">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
          <!-- Back Button -->
          <div class="mb-8">
            <a
              :href="route('projects.index')"
              class="inline-flex items-center text-white/80 hover:text-white transition-colors duration-300"
            >
              <ArrowLeft class="h-4 w-4 mr-2" />
              Back to Projects
            </a>
          </div>

          <!-- Project Meta -->
          <div class="flex flex-wrap items-center gap-3 mb-6">
            <Badge :variant="statusVariant" class="text-sm">
              {{ project.status.charAt(0).toUpperCase() + project.status.slice(1) }}
            </Badge>

            <div v-if="project.location" class="flex items-center text-white/80">
              <MapPin class="h-4 w-4 mr-1" />
              <span class="text-sm">{{ project.location }}</span>
            </div>

            <div v-if="project.start_date" class="flex items-center text-white/80">
              <Calendar class="h-4 w-4 mr-1" />
              <span class="text-sm">{{ formatDate(project.start_date) }}</span>
            </div>
          </div>

          <!-- Title -->
          <h1 class="text-4xl md:text-5xl lg:text-6xl font-display font-bold text-white mb-6 leading-tight">
            {{ project.title }}
          </h1>

          <!-- Description -->
          <p class="text-xl text-gray-200 max-w-3xl leading-relaxed">
            {{ project.description }}
          </p>

          <!-- Tags -->
          <div v-if="project.tags.length > 0" class="flex flex-wrap gap-2 mt-8">
            <span
              v-for="tag in project.tags"
              :key="tag.id"
              class="px-3 py-1 text-sm bg-white/10 text-white rounded-full backdrop-blur-sm border border-white/20"
            >
              {{ tag.name.en }}
            </span>
          </div>
        </div>
      </div>
    </section>

    <!-- Project Stats Section -->
    <ContentSection background="white" padding="lg">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        <!-- People Reached -->
        <div v-if="project.people_reached" class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl p-6 border border-blue-200 dark:border-blue-800">
          <div class="flex items-center justify-between mb-4">
            <Users class="h-8 w-8 text-blue-600 dark:text-blue-400" />
            <TrendingUp class="h-5 w-5 text-blue-500" />
          </div>
          <div class="text-2xl font-bold text-gray-900 dark:text-white mb-1">
            {{ formatNumber(project.people_reached) }}
          </div>
          <div class="text-sm text-gray-600 dark:text-gray-400">
            People Reached
          </div>
        </div>

        <!-- Budget -->
        <div v-if="project.budget" class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-xl p-6 border border-green-200 dark:border-green-800">
          <div class="flex items-center justify-between mb-4">
            <DollarSign class="h-8 w-8 text-green-600 dark:text-green-400" />
            <Target class="h-5 w-5 text-green-500" />
          </div>
          <div class="text-2xl font-bold text-gray-900 dark:text-white mb-1">
            {{ formatBudget(project.budget) }}
          </div>
          <div class="text-sm text-gray-600 dark:text-gray-400">
            Total Budget
          </div>
        </div>

        <!-- Duration -->
        <div v-if="projectDuration" class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-xl p-6 border border-purple-200 dark:border-purple-800">
          <div class="flex items-center justify-between mb-4">
            <Clock class="h-8 w-8 text-purple-600 dark:text-purple-400" />
            <Calendar class="h-5 w-5 text-purple-500" />
          </div>
          <div class="text-2xl font-bold text-gray-900 dark:text-white mb-1">
            {{ projectDuration }}
          </div>
          <div class="text-sm text-gray-600 dark:text-gray-400">
            Months Duration
          </div>
        </div>

        <!-- Status -->
        <div class="bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 rounded-xl p-6 border border-orange-200 dark:border-orange-800">
          <div class="flex items-center justify-between mb-4">
            <Award class="h-8 w-8 text-orange-600 dark:text-orange-400" />
            <div class="text-sm text-orange-600 dark:text-orange-400">
              {{ Math.round(progressPercentage) }}%
            </div>
          </div>
          <div class="text-2xl font-bold text-gray-900 dark:text-white mb-1">
            {{ project.status.charAt(0).toUpperCase() + project.status.slice(1) }}
          </div>
          <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mt-2">
            <div
              class="bg-orange-500 h-2 rounded-full transition-all duration-500"
              :style="{ width: `${progressPercentage}%` }"
            ></div>
          </div>
        </div>
      </div>

      <!-- Funding Info -->
      <div v-if="project.funded_by" class="bg-gray-50 dark:bg-gray-800 rounded-xl p-6 mb-12 border border-gray-200 dark:border-gray-700">
        <div class="flex items-center mb-4">
          <Building class="h-6 w-6 text-gray-600 dark:text-gray-400 mr-3" />
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
            Funded By
          </h3>
        </div>
        <p class="text-gray-700 dark:text-gray-300">
          {{ project.funded_by }}
        </p>
      </div>
    </ContentSection>

    <!-- Main Content Section -->
    <ContentSection background="gray" padding="lg">
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <!-- Main Content -->
        <div class="lg:col-span-2">
          <!-- Project Content -->
          <div class="bg-white dark:bg-gray-800 mb-8">
            <div class="prose prose-lg dark:prose-invert max-w-none" v-html="project.content"></div>
          </div>

          <!-- Key Achievements -->
          <div v-if="project.key_achievements && project.key_achievements.length > 0" class="bg-white dark:bg-gray-800 rounded-xl p-8 shadow-sm border border-gray-200 dark:border-gray-700 mb-8">
            <h2 class="text-2xl font-display font-bold text-gray-900 dark:text-white mb-6 flex items-center">
              <Award class="h-6 w-6 mr-3 text-ca-primary" />
              Key Achievements
            </h2>
            <div class="space-y-4">
              <div
                v-for="(achievement, index) in project.key_achievements"
                :key="index"
                class="flex items-start group"
              >
                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center group-hover:bg-green-200 dark:group-hover:bg-green-800/50 transition-colors duration-300 mt-1">
                  <Check class="w-4 h-4 text-green-600 dark:text-green-400" />
                </div>
                <p class="ml-4 text-gray-700 dark:text-gray-300 leading-relaxed">
                  {{ achievement }}
                </p>
              </div>
            </div>
          </div>

          <!-- Project Timeline -->
          <div class="bg-white dark:bg-gray-800 rounded-xl p-8 shadow-sm border border-gray-200 dark:border-gray-700">
            <h2 class="text-2xl font-display font-bold text-gray-900 dark:text-white mb-6 flex items-center">
              <Clock class="h-6 w-6 mr-3 text-ca-primary" />
              Project Timeline
            </h2>
            <div class="relative">
              <div class="absolute left-4 top-8 bottom-8 w-0.5 bg-gradient-to-b from-ca-primary to-ca-highlight"></div>

              <!-- Start Date -->
              <div class="relative flex items-center mb-8">
                <div class="absolute left-0 w-8 h-8 bg-ca-primary rounded-full flex items-center justify-center">
                  <div class="w-3 h-3 bg-white rounded-full"></div>
                </div>
                <div class="ml-12">
                  <div class="text-sm text-gray-500 dark:text-gray-400">
                    {{ formatDate(project.start_date) }}
                  </div>
                  <div class="font-semibold text-gray-900 dark:text-white">
                    Project Launch
                  </div>
                  <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Project officially started
                  </div>
                </div>
              </div>

              <!-- Current Status -->
              <div v-if="project.status === 'current'" class="relative flex items-center mb-8">
                <div class="absolute left-0 w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center animate-pulse">
                  <div class="w-3 h-3 bg-white rounded-full"></div>
                </div>
                <div class="ml-12">
                  <div class="text-sm text-gray-500 dark:text-gray-400">
                    Now
                  </div>
                  <div class="font-semibold text-gray-900 dark:text-white">
                    In Progress
                  </div>
                  <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Project is actively running
                  </div>
                </div>
              </div>

              <!-- End Date -->
              <div v-if="project.end_date" class="relative flex items-center">
                <div class="absolute left-0 w-8 h-8 bg-ca-highlight rounded-full flex items-center justify-center">
                  <div class="w-3 h-3 bg-white rounded-full"></div>
                </div>
                <div class="ml-12">
                  <div class="text-sm text-gray-500 dark:text-gray-400">
                    {{ formatDate(project.end_date) }}
                  </div>
                  <div class="font-semibold text-gray-900 dark:text-white">
                    {{ project.status === 'completed' ? 'Project Completed' : 'Expected Completion' }}
                  </div>
                  <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    {{ project.status === 'completed' ? 'Project successfully completed' : 'Planned completion date' }}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-8">
          <!-- Support CTA -->
          <div class="bg-gradient-to-br from-ca-primary to-ca-highlight rounded-xl p-6 text-white">
            <h3 class="text-xl font-bold mb-4">Support This Project</h3>
            <p class="text-white/90 mb-6 text-sm">
              Help us make a greater impact in the community by supporting this project.
            </p>
            <button class="w-full bg-white text-ca-primary font-semibold py-3 px-4 rounded-lg hover:bg-gray-100 transition-colors duration-300 flex items-center justify-center">
              <HandHeart class="h-5 w-5 mr-2" />
              Get Involved
            </button>
          </div>

          <!-- Share Project -->
          <SocialShare
            :url="route('projects.show', project.slug)"
            :title="project.title"
            :description="project.description"
          />

          <!-- Additional Meta Data -->
          <div v-if="project.meta_data && Object.keys(project.meta_data).length > 0" class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
              Project Details
            </h3>
            <div class="space-y-3">
              <div
                v-for="(value, key) in project.meta_data"
                :key="key"
                class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700 last:border-b-0"
              >
                <span class="text-sm text-gray-600 dark:text-gray-400 capitalize">
                  {{ key.replace('_', ' ') }}
                </span>
                <span class="text-sm font-medium text-gray-900 dark:text-white">
                  {{ value }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </ContentSection>

    <!-- Gallery Section -->
    <ContentSection
      v-if="project.gallery_images.length > 0"
      background="white"
      padding="lg"
    >
      <h2 class="text-3xl font-display font-bold text-center text-gray-900 dark:text-white mb-12">
        Project Gallery
      </h2>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="image in project.gallery_images"
          :key="image.id"
          class="group relative aspect-w-16 aspect-h-12 rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-800 cursor-pointer"
        >
          <img
            :src="image.url"
            :alt="`${project.title} gallery image`"
            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
            loading="lazy"
          />
          <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300"></div>
        </div>
      </div>
    </ContentSection>

    <!-- Related Projects Section -->
    <ContentSection
      v-if="relatedProjects.length > 0"
      background="gradient"
      padding="lg"
    >
      <div class="text-center mb-12">
        <h2 class="text-3xl font-display font-bold text-gray-900 dark:text-white mb-4">
          Related Projects
        </h2>
        <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
          Explore other projects that are making a difference in similar areas.
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <ProjectCard
          v-for="relatedProject in relatedProjects"
          :key="relatedProject.id"
          :project="relatedProject"
        />
      </div>
    </ContentSection>
  </GuestLayout>
</template>
