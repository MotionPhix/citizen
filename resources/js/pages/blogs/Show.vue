<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import { onMounted, onUnmounted, ref, computed } from 'vue'
import { gsap } from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'
import GuestLayout from '@/layouts/GuestLayout.vue'
import ContentSection from '@/components/ContentSection.vue'
import LikeButton from '@/components/LikeButton.vue'
import ShareButton from '@/components/ShareButton.vue'
import Comments from '@/components/Comments.vue'
import { Button } from '@/components/ui/button'
import { Card, CardContent } from '@/components/ui/card'
import {
  ArrowLeft,
  MessageCircle,
  UserPlus,
  Clock,
  Calendar,
  Eye,
  Heart,
  User,
  Share2,
  BookOpen,
  TrendingUp
} from 'lucide-vue-next'

// Register GSAP plugins
gsap.registerPlugin(ScrollTrigger)

interface User {
  id: number
  name: string
  avatar_url?: string
}

interface Tag {
  id: number
  name: string
  slug: string
}

interface Comment {
  id: number
  content: string
  created_at: string
  user: User
}

interface Post {
  id: number
  title: string
  slug: string
  excerpt: string
  content: string
  published_at: string
  user: User
  tags: Tag[]
  featured_image_url?: string
  featured_image_thumbnail_url?: string
  likes_count: number
  comments_count: number
  is_liked_by_user: boolean
  comments: Comment[]
}

interface Props {
  post: Post
  relatedPosts: Post[]
  isAuthenticated: boolean
  currentUser?: User
}

const props = defineProps<Props>()

// Animation refs
const headerRef = ref<HTMLElement | null>(null)
const contentRef = ref<HTMLElement | null>(null)
const sidebarRef = ref<HTMLElement | null>(null)

// Reading progress and stats
const readingProgress = ref(0)
const calculateReadingProgress = () => {
  const element = document.documentElement
  const totalHeight = element.scrollHeight - element.clientHeight
  const progress = (element.scrollTop / totalHeight) * 100
  readingProgress.value = Math.min(100, Math.max(0, progress))
}

// Format number for stats
const formatNumber = (num: number) => {
  if (num === undefined || num === null) return '0'
  if (num >= 1000) return `${(num / 1000).toFixed(1)}k`
  return num.toString()
}

// Blog stats
const stats = computed(() => [
  {
    label: 'Views',
    value: formatNumber(props.post.view_count),
    icon: Eye
  },
  {
    label: 'Likes',
    value: formatNumber(props.post.likes_count),
    icon: Heart
  },
  {
    label: 'Comments',
    value: formatNumber(props.post.comments_count),
    icon: MessageCircle
  },
  {
    label: 'Reading Time',
    value: `${props.post.reading_time}m`,
    icon: Clock
  }
])

// Format date with proper localization
const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

onMounted(() => {
  // Add scroll event listener for reading progress
  window.addEventListener('scroll', calculateReadingProgress)

  // Header animation
  gsap.from(headerRef.value, {
    opacity: 0,
    y: -50,
    duration: 1,
    ease: 'power3.out'
  })

  // Content animation with scroll trigger
  gsap.from(contentRef.value, {
    scrollTrigger: {
      trigger: contentRef.value,
      start: 'top center',
      end: 'bottom center',
      toggleActions: 'play none none reverse'
    },
    opacity: 0,
    y: 50,
    duration: 1
  })

  // Sidebar animation
  gsap.from(sidebarRef.value, {
    scrollTrigger: {
      trigger: sidebarRef.value,
      start: 'top center+=100',
      toggleActions: 'play none none reverse'
    },
    opacity: 0,
    x: 50,
    duration: 1
  })
})

onUnmounted(() => {
  window.removeEventListener('scroll', calculateReadingProgress)
})
</script>

<template>
  <Head>
    <title>{{ post.title }} - Citizen Alliance</title>
    <meta name="description" :content="post.excerpt" />
    <meta property="og:title" :content="post.title" />
    <meta property="og:description" :content="post.excerpt" />
    <meta property="og:image" :content="post.featured_image_url" />
  </Head>

  <GuestLayout>
    <!-- Reading Progress Bar -->
    <div class="fixed top-0 left-0 z-50 h-1 bg-ca-primary transition-all duration-300 ease-out" :style="{ width: `${readingProgress}%` }" />

    <!-- Hero Section - Full Width -->
    <section class="relative min-h-[70vh] flex items-center bg-gradient-to-br from-ca-primary via-ca-primary to-ca-highlight overflow-hidden">
      <!-- Background Pattern -->
      <div class="absolute inset-0">
        <div class="absolute inset-0 bg-[url('/images/pattern.svg')] opacity-10"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
      </div>

      <!-- Featured Image Background (if available) -->
      <div v-if="post.featured_image_url" class="absolute inset-0">
        <img
          :src="post.featured_image_url"
          :alt="post.title"
          class="w-full h-full object-cover opacity-20"
        />
        <div class="absolute inset-0 bg-gradient-to-t from-ca-primary/90 via-ca-primary/70 to-ca-primary/50"></div>
      </div>

      <!-- Content -->
      <div class="relative w-full">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
          <!-- Back Button -->
          <Link
            :href="route('blogs.index')"
            class="inline-flex items-center text-white/80 hover:text-white transition-colors duration-200 mb-8 group"
          >
            <ArrowLeft class="h-5 w-5 mr-2 transition-transform group-hover:-translate-x-1" />
            Back to Stories
          </Link>

          <!-- Article Header -->
          <div class="text-center">
            <h1
              ref="headerRef"
              class="text-3xl md:text-4xl lg:text-5xl xl:text-6xl font-display font-bold text-white mb-6 leading-tight"
            >
              {{ post.title }}
            </h1>

            <p v-if="post.excerpt" class="text-lg md:text-xl text-white/90 max-w-3xl mx-auto mb-8 leading-relaxed">
              {{ post.excerpt }}
            </p>

            <!-- Article Meta -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-6 mb-8">
              <!-- Author Info -->
              <div class="flex items-center gap-3">
                <div class="h-12 w-12 overflow-hidden rounded-full ring-2 ring-white/20">
                  <img
                    v-if="post.user.avatar_url"
                    :src="post.user.avatar_url"
                    :alt="post.user.name"
                    class="h-full w-full object-cover"
                  />
                  <div v-else class="flex h-full w-full items-center justify-center bg-white/20">
                    <User class="h-6 w-6 text-white" />
                  </div>
                </div>
                <div class="text-left">
                  <div class="text-sm font-medium text-white">
                    {{ post.user.name }}
                  </div>
                  <div class="flex items-center gap-2 text-sm text-white/80">
                    <Calendar class="h-3 w-3" />
                    {{ formatDate(post.published_at) }}
                  </div>
                </div>
              </div>

              <!-- Article Stats -->
              <div class="flex items-center gap-6 text-white/80">
                <div class="flex items-center gap-2">
                  <Clock class="h-4 w-4" />
                  <span class="text-sm">{{ post.reading_time || 5 }}m read</span>
                </div>
                <div class="flex items-center gap-2">
                  <Eye class="h-4 w-4" />
                  <span class="text-sm">{{ formatNumber(post.view_count || 0) }} views</span>
                </div>
                <div class="flex items-center gap-2">
                  <Heart class="h-4 w-4" />
                  <span class="text-sm">{{ formatNumber(post.likes_count) }} likes</span>
                </div>
              </div>
            </div>

            <!-- Tags -->
            <div v-if="post.tags.length > 0" class="flex flex-wrap justify-center gap-2">
              <Link
                v-for="tag in post.tags"
                :key="tag.id"
                :href="route('blogs.index', { tag: tag.slug })"
                class="px-3 py-1 text-sm rounded-full bg-white/20 text-white hover:bg-white/30 transition-colors duration-200"
              >
                {{ tag.name }}
              </Link>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Main Content Section -->
    <ContentSection background="white" padding="lg">
      <div class="max-w-4xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
          <!-- Article Content -->
          <article class="lg:col-span-8">
            <!-- Featured Image (if not shown in hero) -->
            <div v-if="post.featured_image_url && !post.featured_image_url" class="mb-8 overflow-hidden rounded-2xl shadow-lg">
              <img
                :src="post.featured_image_url"
                :alt="post.title"
                class="w-full h-auto object-cover transform transition duration-500 hover:scale-105"
              />
            </div>

            <!-- Article Content -->
            <div
              ref="contentRef"
              class="prose prose-lg max-w-none dark:prose-invert prose-headings:scroll-mt-20 prose-a:text-ca-primary dark:prose-a:text-ca-highlight prose-img:rounded-xl prose-img:shadow-lg prose-blockquote:border-ca-primary prose-blockquote:bg-gray-50 dark:prose-blockquote:bg-gray-800"
              v-html="post.content"
            />

            <!-- Article Footer -->
            <div class="mt-12 pt-8 border-t border-gray-200 dark:border-gray-700">
              <!-- Interaction Buttons -->
              <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
                <div class="flex items-center gap-4">
                  <LikeButton
                    :post-slug="post.slug"
                    :initial-count="post.likes_count"
                    :is-authenticated="isAuthenticated"
                    :initial-is-liked="post.is_liked_by_user"
                    class="flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors"
                  />
                  <ShareButton
                    :url="route('blogs.show', post.slug)"
                    :title="post.title"
                    class="flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors"
                  >
                    <Share2 class="h-4 w-4" />
                    Share
                  </ShareButton>
                </div>

                <!-- Article Stats -->
                <div class="flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                  <div class="flex items-center gap-1">
                    <Eye class="h-4 w-4" />
                    {{ formatNumber(post.view_count || 0) }}
                  </div>
                  <div class="flex items-center gap-1">
                    <MessageCircle class="h-4 w-4" />
                    {{ formatNumber(post.comments_count) }}
                  </div>
                </div>
              </div>

              <!-- Author Bio Card -->
              <Card class="mb-8">
                <CardContent class="p-6">
                  <div class="flex items-start gap-4">
                    <div class="h-16 w-16 overflow-hidden rounded-full flex-shrink-0">
                      <img
                        v-if="post.user.avatar_url"
                        :src="post.user.avatar_url"
                        :alt="post.user.name"
                        class="h-full w-full object-cover"
                      />
                      <div v-else class="flex h-full w-full items-center justify-center bg-gray-100 dark:bg-gray-800">
                        <User class="h-8 w-8 text-gray-400" />
                      </div>
                    </div>
                    <div class="flex-1">
                      <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">
                        {{ post.user.name }}
                      </h3>
                      <p class="text-gray-600 dark:text-gray-400 text-sm mb-3">
                        Author • Contributing to positive change in our communities
                      </p>
                      <Button
                        v-if="isAuthenticated && currentUser?.id !== post.user.id"
                        size="sm"
                        variant="outline"
                      >
                        <UserPlus class="mr-2 h-4 w-4" />
                        Follow
                      </Button>
                    </div>
                  </div>
                </CardContent>
              </Card>
            </div>

            <!-- Comments Section -->
            <Comments
              :post-slug="post.slug"
              :is-authenticated="isAuthenticated"
              :current-user="currentUser"
              :initial-comments="post.comments"
              class="mt-8"
            />
          </article>

          <!-- Sidebar -->
          <aside ref="sidebarRef" class="lg:col-span-4">
            <div class="sticky top-8 space-y-6">
              <!-- Quick Stats -->
              <Card>
                <CardContent class="p-6">
                  <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <TrendingUp class="h-5 w-5" />
                    Article Stats
                  </h3>
                  <div class="grid grid-cols-2 gap-4">
                    <div v-for="stat in stats" :key="stat.label" class="text-center">
                      <component :is="stat.icon" class="h-6 w-6 mx-auto text-ca-primary mb-2" />
                      <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ stat.value }}</div>
                      <div class="text-sm text-gray-500 dark:text-gray-400">{{ stat.label }}</div>
                    </div>
                  </div>
                </CardContent>
              </Card>

              <!-- Related Posts -->
              <div v-if="relatedPosts.length > 0">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                  <BookOpen class="h-5 w-5" />
                  Related Stories
                </h3>
                <div class="space-y-4">
                  <Link
                    v-for="related in relatedPosts"
                    :key="related.id"
                    :href="route('blogs.show', related.slug)"
                    class="block group"
                  >
                    <Card class="transition-all duration-200 hover:shadow-md group-hover:border-ca-primary/20">
                      <CardContent class="p-4">
                        <div class="flex gap-4">
                          <div v-if="related.featured_image_thumbnail_url" class="h-16 w-16 flex-shrink-0">
                            <img
                              :src="related.featured_image_thumbnail_url"
                              :alt="related.title"
                              class="h-full w-full rounded-lg object-cover"
                            />
                          </div>
                          <div class="flex-1 min-w-0">
                            <h4 class="font-medium text-gray-900 dark:text-white group-hover:text-ca-primary transition-colors line-clamp-2 mb-2">
                              {{ related.title }}
                            </h4>
                            <div class="flex items-center gap-4 text-xs text-gray-500 dark:text-gray-400">
                              <span class="flex items-center gap-1">
                                <Calendar class="h-3 w-3" />
                                {{ formatDate(related.published_at) }}
                              </span>
                              <span class="flex items-center gap-1">
                                <Clock class="h-3 w-3" />
                                {{ related.reading_time || 5 }}m
                              </span>
                            </div>
                          </div>
                        </div>
                      </CardContent>
                    </Card>
                  </Link>
                </div>
              </div>

              <!-- Back to Blog -->
              <Card>
                <CardContent class="p-6 text-center">
                  <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                    Explore More Stories
                  </h3>
                  <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">
                    Discover more insights and updates from our community.
                  </p>
                  <Button :href="route('blogs.index')" class="w-full">
                    <ArrowLeft class="mr-2 h-4 w-4" />
                    View All Stories
                  </Button>
                </CardContent>
              </Card>
            </div>
          </aside>
        </div>
      </div>
    </ContentSection>
  </GuestLayout>
</template>

<style scoped>
/* Reading progress bar */
.reading-progress {
  position: fixed;
  top: 0;
  left: 0;
  height: 3px;
  background: linear-gradient(90deg, #3B82F6, #8B5CF6);
  z-index: 50;
  transition: width 0.3s ease-out;
}

/* Enhanced prose styling */
.prose {
  @apply text-gray-700 dark:text-gray-300;
}

.prose h1,
.prose h2,
.prose h3,
.prose h4,
.prose h5,
.prose h6 {
  @apply text-gray-900 dark:text-white font-display;
}

.prose blockquote {
  @apply border-l-4 border-ca-primary bg-gray-50 dark:bg-gray-800 p-4 rounded-r-lg;
}

.prose code {
  @apply bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded text-sm;
}

.prose pre {
  @apply bg-gray-900 dark:bg-gray-950 rounded-lg overflow-x-auto;
}

.prose img {
  @apply rounded-xl shadow-lg mx-auto;
}

/* Smooth animations */
.group:hover .group-hover\:border-ca-primary\/20 {
  border-color: rgba(59, 130, 246, 0.2);
}

/* Mobile responsiveness */
@media (max-width: 640px) {
  .prose {
    @apply text-base;
  }

  .prose h1 {
    @apply text-2xl;
  }

  .prose h2 {
    @apply text-xl;
  }

  .prose h3 {
    @apply text-lg;
  }
}
</style>
