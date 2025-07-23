<script setup lang="ts">
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import { Calendar, Heart, Eye, MessageCircle, Clock, TrendingUp } from 'lucide-vue-next'

interface User {
  id: number
  name: string
  avatar_url?: string
}

interface Tag {
  id: number
  name: string
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

interface Props {
  post: Post
}

const props = defineProps<Props>()

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
  if (num >= 1000) {
    return (num / 1000).toFixed(1) + 'K'
  }
  return num.toString()
}

// Get author initials
const authorInitials = computed(() => {
  return props.post.author.name
    .split(' ')
    .map(name => name.charAt(0))
    .join('')
    .toUpperCase()
    .slice(0, 2)
})
</script>

<template>
  <Link
    :href="route('blogs.show', post.slug)"
    class="group block"
  >
    <article class="relative bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-200 dark:border-gray-700 hover:border-ca-primary/50 dark:hover:border-ca-highlight/50 h-full">
      <!-- Featured Badge -->
      <div class="absolute top-4 left-4 z-10">
        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-ca-primary to-ca-highlight text-white shadow-lg">
          <TrendingUp class="h-3 w-3 mr-1" />
          Featured
        </span>
      </div>

      <!-- Featured Image -->
      <div class="relative aspect-w-16 aspect-h-10 overflow-hidden">
        <img
          v-if="post.featured_image"
          :src="post.featured_image.hero"
          :alt="post.title"
          class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
          loading="lazy"
        />
        <div
          v-else
          class="w-full h-full bg-gradient-to-br from-ca-primary to-ca-highlight flex items-center justify-center"
        >
          <span class="text-4xl font-bold text-white">
            {{ post.title.charAt(0) }}
          </span>
        </div>

        <!-- Gradient Overlay -->
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>

        <!-- Reading Time Badge -->
        <div class="absolute top-4 right-4">
          <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-black/70 text-white backdrop-blur-sm">
            <Clock class="h-3 w-3 mr-1" />
            {{ post.reading_time }} min read
          </span>
        </div>

        <!-- Bottom Content Overlay -->
        <div class="absolute bottom-0 left-0 right-0 p-6">
          <!-- Tags -->
          <div v-if="post.tags.length > 0" class="flex flex-wrap gap-2 mb-3">
            <span
              v-for="tag in post.tags.slice(0, 2)"
              :key="tag.id"
              class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-white/20 text-white backdrop-blur-sm border border-white/30"
            >
              {{ tag.name }}
            </span>
            <span
              v-if="post.tags.length > 2"
              class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-white/20 text-white/80 backdrop-blur-sm border border-white/30"
            >
              +{{ post.tags.length - 2 }}
            </span>
          </div>

          <!-- Title -->
          <h3 class="text-xl font-display font-bold text-white mb-2 group-hover:text-yellow-300 transition-colors duration-300 line-clamp-2">
            {{ post.title }}
          </h3>

          <!-- Author and Stats -->
          <div class="flex items-center justify-between">
            <!-- Author -->
            <div class="flex items-center">
              <div v-if="post.author.avatar_url" class="w-8 h-8 rounded-full overflow-hidden mr-3 border-2 border-white/30">
                <img
                  :src="post.author.avatar_url"
                  :alt="post.author.name"
                  class="w-full h-full object-cover"
                />
              </div>
              <div v-else class="w-8 h-8 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center mr-3 border-2 border-white/30">
                <span class="text-xs font-medium text-white">
                  {{ authorInitials }}
                </span>
              </div>
              <div>
                <p class="text-sm font-medium text-white">
                  {{ post.author.name }}
                </p>
                <p class="text-xs text-white/80">
                  {{ formatDate(post.published_at) }}
                </p>
              </div>
            </div>

            <!-- Engagement Stats -->
            <div class="flex items-center space-x-3 text-xs text-white/80">
              <span class="flex items-center">
                <Heart class="h-3 w-3 mr-1" />
                {{ formatNumber(post.likes_count) }}
              </span>
              <span class="flex items-center">
                <Eye class="h-3 w-3 mr-1" />
                {{ formatNumber(post.view_count) }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </article>
  </Link>
</template>
