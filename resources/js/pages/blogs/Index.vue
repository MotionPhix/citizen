<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3'
import { ref, computed } from 'vue'
import GuestLayout from '@/layouts/GuestLayout.vue'
import ContentSection from '@/components/ContentSection.vue'
import BlogCard from '@/components/blogs/BlogCard.vue'
import BlogListItem from '@/components/blogs/BlogListItem.vue'
import FeaturedBlogCard from '@/components/blogs/FeaturedBlogCard.vue'
import BlogStats from '@/components/blogs/BlogStats.vue'
import {
  Search,
  FileText,
  Calendar,
  Heart,
  Image,
  Grid,
  List,
  Filter,
  SlidersHorizontal,
  TrendingUp,
  Clock,
  Eye,
  MessageCircle,
  X
} from 'lucide-vue-next'
import { useStorage } from '@vueuse/core';

interface User {
  id: number
  name: string
  avatar_url?: string
}

interface Tag {
  id: number
  name: string
  posts_count?: number
}

interface FeaturedImage {
  thumbnail: string
  preview: string
  hero: string
  original: string
}

interface Post {
  id: number
  title: string
  slug: string
  excerpt: string
  published_at: string
  likes_count: number
  view_count: number
  comments_count: number
  reading_time: number
  tags: Tag[]
  featured_image?: FeaturedImage
  author: User
}

interface PaginationLink {
  url: string | null
  label: string
  active: boolean
}

interface Pagination {
  current_page: number
  data: Post[]
  first_page_url: string
  from: number
  last_page: number
  last_page_url: string
  links: PaginationLink[]
  next_page_url: string | null
  path: string
  per_page: number
  prev_page_url: string | null
  to: number
  total: number
}

interface BlogStats {
  total_posts: number
  total_views: number
  total_likes: number
  total_comments: number
}

interface Filters {
  search?: string
  tag?: string
  category?: string
  sort: string
}

interface Props {
  posts: Pagination
  featuredPosts: Post[]
  popularTags: Tag[]
  blogStats: BlogStats
  filters: Filters
}

const props = defineProps<Props>()

// View mode state
const viewMode = useStorage<'grid' | 'list'>('blogs_view_mode', 'grid')
const searchQuery = ref(props.filters.search || '')
const selectedTag = ref(props.filters.tag || '')
const selectedSort = ref(props.filters.sort || 'latest')
const showFilters = ref(false)

// Sort options
const sortOptions = [
  { value: 'latest', label: 'Latest Posts' },
  { value: 'popular', label: 'Most Popular' },
  { value: 'views', label: 'Most Viewed' },
  { value: 'oldest', label: 'Oldest First' }
]

// Filtered posts (client-side filtering for immediate feedback)
const filteredPosts = computed(() => {
  let filtered = props.posts.data

  // Note: Server-side filtering is primary, this is for immediate UI feedback
  if (searchQuery.value && searchQuery.value !== props.filters.search) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(post =>
      post.title.toLowerCase().includes(query) ||
      post.excerpt.toLowerCase().includes(query)
    )
  }

  return filtered
})

// Handle search
const handleSearch = () => {
  const params: Record<string, any> = {}

  if (searchQuery.value) params.search = searchQuery.value
  if (selectedTag.value) params.tag = selectedTag.value
  if (selectedSort.value !== 'latest') params.sort = selectedSort.value

  router.get(route('blogs.index'), params, {
    preserveState: true,
    replace: true
  })
}

// Handle filter changes
const handleFilterChange = () => {
  handleSearch()
}

// Clear all filters
const clearFilters = () => {
  searchQuery.value = ''
  selectedTag.value = ''
  selectedSort.value = 'latest'
  showFilters.value = false

  router.get(route('blogs.index'), {}, {
    preserveState: true,
    replace: true
  })
}

// Format date
const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

// Format number
const formatNumber = (num: number) => {
  if (num >= 1000000) {
    return (num / 1000000).toFixed(1) + 'M'
  } else if (num >= 1000) {
    return (num / 1000).toFixed(1) + 'K'
  }
  return num.toString()
}
</script>

<template>
  <Head>
    <title>Blog - Citizen Alliance</title>
    <meta name="description" content="Read the latest blog posts, insights, and updates from Citizen Alliance about governance, community development, and social impact in Malawi." />
    <meta property="og:title" content="Blog - Citizen Alliance" />
    <meta property="og:description" content="Stay informed with our latest stories and insights" />
  </Head>

  <GuestLayout>
    <!-- Hero Section -->
    <section class="relative min-h-[60vh] flex items-center bg-gradient-to-br from-ca-primary via-ca-primary to-ca-highlight overflow-hidden">
      <!-- Background Pattern -->
      <div class="absolute inset-0">
        <div class="absolute inset-0 bg-[url('/images/pattern.svg')] opacity-10"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
      </div>

      <!-- Content -->
      <div class="relative w-full">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <h1 class="text-4xl md:text-5xl lg:text-6xl font-display font-bold text-white mb-6 leading-tight">
            Stories of
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-orange-300">
              Impact & Change
            </span>
          </h1>

          <p class="text-xl md:text-2xl text-white/90 max-w-3xl mx-auto mb-8 leading-relaxed">
            Stay informed with our latest insights, updates, and stories about governance, community development, and social impact across Malawi.
          </p>

          <!-- Search Bar -->
          <div class="max-w-2xl mx-auto">
            <form @submit.prevent="handleSearch" class="relative">
              <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <Search class="h-5 w-5 text-gray-400" />
              </div>
              <input
                v-model="searchQuery"
                type="text"
                placeholder="Search stories, insights, and updates..."
                class="w-full pl-12 pr-4 py-4 rounded-xl border-0 bg-white/90 backdrop-blur-sm text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-white/30 focus:bg-white transition duration-200 ease-in-out shadow-lg"
              >
            </form>
          </div>
        </div>
      </div>
    </section>

    <!-- Blog Statistics -->
    <ContentSection background="white" padding="lg">
      <BlogStats :stats="blogStats" />
    </ContentSection>

    <!-- Featured Posts Section -->
    <ContentSection
      v-if="featuredPosts.length > 0"
      background="gradient"
      padding="lg"
    >
      <div class="text-center mb-12">
        <h2 class="text-3xl md:text-4xl font-display font-bold text-gray-900 dark:text-white mb-4">
          Featured Stories
        </h2>
        <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
          Discover our most impactful and engaging stories that are making a difference.
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <FeaturedBlogCard
          v-for="post in featuredPosts"
          :key="post.id"
          :post="post"
        />
      </div>
    </ContentSection>

    <!-- Main Blog Section -->
    <ContentSection background="white" padding="lg">
      <!-- Section Header -->
      <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-8">
        <div>
          <h2 class="text-3xl font-display font-bold text-gray-900 dark:text-white mb-2">
            All Stories
          </h2>
          <p class="text-gray-600 dark:text-gray-400">
            Browse through our complete collection of insights and updates.
          </p>
        </div>

        <!-- Popular Tags -->
        <div v-if="popularTags.length > 0" class="mt-6 lg:mt-0">
          <div class="flex flex-wrap gap-2">
            <button
              v-for="tag in popularTags.slice(0, 5)"
              :key="tag.id"
              @click="selectedTag = tag.name; handleFilterChange()"
              :class="[
                'px-3 py-1 text-sm rounded-full transition-colors duration-300',
                selectedTag === tag.name
                  ? 'bg-ca-primary text-white'
                  : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700'
              ]"
            >
              {{ tag.name }}
              <span v-if="tag.posts_count" class="ml-1 opacity-75">
                ({{ tag.posts_count }})
              </span>
            </button>
          </div>
        </div>
      </div>

      <!-- Controls Bar -->
      <div class="flex flex-col sm:flex-row gap-4 mb-8">
        <!-- Search and Filters -->
        <div class="flex-1 flex gap-4">
          <!-- Search Input -->
          <div class="flex-1 relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <Search class="h-5 w-5 text-gray-400" />
            </div>
            <input
              v-model="searchQuery"
              @input="handleSearch"
              type="text"
              placeholder="Search posts..."
              class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-ca-primary focus:border-transparent text-sm"
            />
          </div>

          <!-- Filters Toggle -->
          <button
            @click="showFilters = !showFilters"
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

        <!-- View Mode and Sort -->
        <div class="flex items-center gap-4">
          <!-- Sort Dropdown -->
          <select
            v-model="selectedSort"
            @change="handleFilterChange"
            class="px-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-ca-primary focus:border-transparent"
          >
            <option
              v-for="option in sortOptions"
              :key="option.value"
              :value="option.value"
            >
              {{ option.label }}
            </option>
          </select>

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
        </div>
      </div>

      <!-- Advanced Filters Panel -->
      <div
        v-if="showFilters"
        class="bg-gray-50 dark:bg-gray-800 rounded-lg p-6 mb-8 border border-gray-200 dark:border-gray-700"
      >
        <div class="flex flex-col sm:flex-row gap-4">
          <!-- Tag Filter -->
          <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Filter by Tag
            </label>
            <select
              v-model="selectedTag"
              @change="handleFilterChange"
              class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-ca-primary focus:border-ca-primary"
            >
              <option value="">All Tags</option>
              <option
                v-for="tag in popularTags"
                :key="tag.id"
                :value="tag.name"
              >
                {{ tag.name }} ({{ tag.posts_count }})
              </option>
            </select>
          </div>

          <!-- Clear Filters -->
          <div class="flex items-end">
            <button
              @click="clearFilters"
              class="flex items-center px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors"
            >
              <X class="h-4 w-4 mr-1" />
              Clear Filters
            </button>
          </div>
        </div>
      </div>

      <!-- Active Filters Display -->
      <div v-if="selectedTag || searchQuery" class="flex flex-wrap gap-2 mb-6">
        <span class="text-sm text-gray-600 dark:text-gray-400">Active filters:</span>

        <span
          v-if="searchQuery"
          class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-ca-primary/10 text-ca-primary"
        >
          Search: "{{ searchQuery }}"
          <button @click="searchQuery = ''; handleSearch()" class="ml-2 hover:text-ca-primary/70">
            <X class="h-3 w-3" />
          </button>
        </span>

        <span
          v-if="selectedTag"
          class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-ca-primary/10 text-ca-primary"
        >
          Tag: {{ selectedTag }}
          <button @click="selectedTag = ''; handleFilterChange()" class="ml-2 hover:text-ca-primary/70">
            <X class="h-3 w-3" />
          </button>
        </span>
      </div>

      <!-- Results Info -->
      <div class="flex justify-between items-center mb-6">
        <p class="text-sm text-gray-600 dark:text-gray-400">
          Showing {{ filteredPosts.length }} of {{ posts.total }} posts
        </p>
        <div class="text-sm text-gray-500 dark:text-gray-400">
          {{ viewMode === 'grid' ? 'Grid View' : 'List View' }}
        </div>
      </div>

      <!-- Posts Display -->
      <div v-if="filteredPosts.length > 0">
        <!-- Grid View -->
        <div
          v-if="viewMode === 'grid'"
          class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"
        >
          <BlogCard
            v-for="post in filteredPosts"
            :key="post.id"
            :post="post"
          />
        </div>

        <!-- List View -->
        <div
          v-else
          class="space-y-6"
        >
          <BlogListItem
            v-for="post in filteredPosts"
            :key="post.id"
            :post="post"
          />
        </div>
      </div>

      <!-- No Results -->
      <div
        v-else
        class="text-center py-16"
      >
        <div class="mx-auto h-12 w-12 text-gray-400 mb-4">
          <FileText class="h-12 w-12" />
        </div>
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
          No posts found
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
        v-if="posts.last_page > 1 && filteredPosts.length > 0"
        class="mt-12"
      >
        <nav class="flex items-center justify-between">
          <div class="flex-1 flex justify-between sm:hidden">
            <a
              v-if="posts.prev_page_url"
              :href="posts.prev_page_url"
              class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700"
            >
              Previous
            </a>
            <a
              v-if="posts.next_page_url"
              :href="posts.next_page_url"
              class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700"
            >
              Next
            </a>
          </div>
          <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
              <p class="text-sm text-gray-700 dark:text-gray-300">
                Showing {{ posts.from }} to {{ posts.to }} of {{ posts.total }} results
              </p>
            </div>
            <div>
              <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                <a
                  v-for="link in posts.links"
                  :key="link.label"
                  :href="link.url || '#'"
                  :class="[
                    'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                    link.active
                      ? 'z-10 bg-ca-primary border-ca-primary text-white'
                      : 'bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-500 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700',
                    !link.url ? 'cursor-not-allowed opacity-50' : ''
                  ]"
                  v-html="link.label"
                />
              </nav>
            </div>
          </div>
        </nav>
      </div>
    </ContentSection>
  </GuestLayout>
</template>
