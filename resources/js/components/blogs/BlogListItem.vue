<script setup lang="ts">
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import { Heart, Eye, MessageCircle, Clock, ArrowRight } from 'lucide-vue-next'

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
    <article class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg hover:border-ca-primary/50 dark:hover:border-ca-highlight/50 transition-all duration-300">
      <div class="flex flex-col lg:flex-row gap-6">
        <!-- Featured Image -->
        <div class="lg:w-64 lg:flex-shrink-0">
          <div class="aspect-w-16 aspect-h-10 lg:aspect-w-1 lg:aspect-h-1 rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-700">
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
          </div>
        </div>

        <!-- Content -->
        <div class="flex-1 min-w-0">
          <div class="flex flex-col h-full">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 mb-4">
              <div class="flex-1 min-w-0">
                <!-- Tags -->
                <div v-if="post.tags.length > 0" class="flex flex-wrap gap-2 mb-3">
                  <span
                    v-for="tag in post.tags.slice(0, 3)"
                    :key="tag.id"
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-ca-primary/10 text-ca-primary"
                  >
                    {{ tag.name }}
                  </span>
                  <span
                    v-if="post.tags.length > 3"
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400"
                  >
                    +{{ post.tags.length - 3 }}
                  </span>
                </div>

                <!-- Title -->
                <h3 class="text-xl font-display font-semibold text-gray-900 dark:text-white group-hover:text-ca-primary dark:group-hover:text-ca-highlight transition-colors duration-300 line-clamp-2 mb-2">
                  {{ post.title }}
                </h3>
              </div>

              <!-- Reading Time -->
              <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 flex-shrink-0">
                <Clock class="h-4 w-4 mr-1" />
                {{ post.reading_time }} min read
              </div>
            </div>

            <!-- Excerpt -->
            <p class="text-gray-600 dark:text-gray-400 line-clamp-3 mb-4 flex-1">
              {{ post.excerpt }}
            </p>

            <!-- Footer -->
            <div class="flex items-center justify-between pt-4 border-t border-gray-100 dark:border-gray-700">
              <!-- Author -->
              <div class="flex items-center">
                <div v-if="post.author.avatar_url" class="w-10 h-10 rounded-full overflow-hidden mr-3">
                  <img
                    :src="post.author.avatar_url"
                    :alt="post.author.name"
                    class="w-full h-full object-cover"
                  />
                </div>
                <div v-else class="w-10 h-10 rounded-full bg-ca-primary/10 dark:bg-ca-highlight/20 flex items-center justify-center mr-3">
                  <span class="text-sm font-medium text-ca-primary dark:text-ca-highlight">
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

              <!-- Engagement Stats and CTA -->
              <div class="flex items-center space-x-6">
                <!-- Stats -->
                <div class="flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-400">
                  <span class="flex items-center">
                    <Heart class="h-4 w-4 mr-1" />
                    {{ formatNumber(post.likes_count) }}
                  </span>
                  <span class="flex items-center">
                    <Eye class="h-4 w-4 mr-1" />
                    {{ formatNumber(post.view_count) }}
                  </span>
                  <span class="flex items-center">
                    <MessageCircle class="h-4 w-4 mr-1" />
                    {{ formatNumber(post.comments_count) }}
                  </span>
                </div>

                <!-- Read More -->
                <div class="flex items-center text-ca-primary dark:text-ca-highlight group-hover:text-ca-highlight dark:group-hover:text-white transition-colors duration-300">
                  <span class="text-sm font-medium">Read More</span>
                  <ArrowRight class="h-4 w-4 ml-1 group-hover:translate-x-1 transition-transform duration-300" />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </article>
  </Link>
</template>
