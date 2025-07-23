<script setup lang="ts">
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import { Calendar, Heart, Eye, MessageCircle, Clock, User } from 'lucide-vue-next'

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
  if (num === undefined || num === null) {
    return '0'
  }
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
    <article class="bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden border border-gray-200 dark:border-gray-700 hover:border-ca-primary/50 dark:hover:border-ca-highlight/50 h-full flex flex-col">
      <!-- Featured Image -->
      <div class="relative aspect-w-16 aspect-h-9 overflow-hidden">
        <img
          v-if="post.featured_image"
          :src="post.featured_image.preview"
          :alt="post.title"
          class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
          loading="lazy"
        />
        <div
          v-else
          class="w-full h-full bg-gradient-to-br from-ca-primary/20 to-ca-highlight/20 flex items-center justify-center"
        >
          <span class="text-2xl font-bold text-ca-primary dark:text-ca-highlight">
            {{ post.title.charAt(0) }}
          </span>
        </div>

        <!-- Reading Time Badge -->
        <div class="absolute top-3 right-3">
          <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-black/70 text-white backdrop-blur-sm">
            <Clock class="h-3 w-3 mr-1" />
            {{ post.reading_time }} min read
          </span>
        </div>
      </div>

      <!-- Content -->
      <div class="p-6 flex-1 flex flex-col">
        <!-- Tags -->
        <div v-if="post.tags.length > 0" class="flex flex-wrap gap-2 mb-4">
          <span
            v-for="tag in post.tags.slice(0, 2)"
            :key="tag.id"
            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-ca-primary/10 text-ca-primary"
          >
            {{ tag.name }}
          </span>
          <span
            v-if="post.tags.length > 2"
            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400"
          >
            +{{ post.tags.length - 2 }}
          </span>
        </div>

        <!-- Title -->
        <h3 class="text-lg font-display font-semibold text-gray-900 dark:text-white mb-3 group-hover:text-ca-primary dark:group-hover:text-ca-highlight transition-colors duration-300 line-clamp-2 flex-shrink-0">
          {{ post.title }}
        </h3>

        <!-- Excerpt -->
        <p class="text-gray-600 dark:text-gray-400 text-sm line-clamp-3 mb-4 flex-1">
          {{ post.excerpt }}
        </p>

        <!-- Author and Meta -->
        <div class="flex items-center justify-between pt-4 border-t border-gray-100 dark:border-gray-700">
          <!-- Author -->
          <div class="flex items-center">
            <div v-if="post.author.avatar_url" class="w-8 h-8 rounded-full overflow-hidden mr-3">
              <img
                :src="post.author.avatar_url"
                :alt="post.author.name"
                class="w-full h-full object-cover"
              />
            </div>
            <div v-else class="w-8 h-8 rounded-full bg-ca-primary/10 dark:bg-ca-highlight/20 flex items-center justify-center mr-3">
              <span class="text-xs font-medium text-ca-primary dark:text-ca-highlight">
                {{ authorInitials }}
              </span>
            </div>
            <div>
              <p class="text-sm font-medium text-gray-900 dark:text-white">
                {{ post.author.name }}
              </p>
              <p class="text-xs text-gray-500 dark:text-gray-400">
                {{ formatDate(post.published_at) }}
              </p>
            </div>
          </div>

          <!-- Engagement Stats -->
          <div class="flex items-center space-x-3 text-xs text-gray-500 dark:text-gray-400">
            <span class="flex items-center">
              <Heart class="h-3 w-3 mr-1" />
              {{ formatNumber(post.likes_count) }}
            </span>
            <span class="flex items-center">
              <Eye class="h-3 w-3 mr-1" />
              {{ formatNumber(post.view_count) }}
            </span>
            <span class="flex items-center">
              <MessageCircle class="h-3 w-3 mr-1" />
              {{ formatNumber(post.comments_count) }}
            </span>
          </div>
        </div>
      </div>
    </article>
  </Link>
</template>
