<script setup lang="ts">
import { FileText, Eye, Heart, MessageCircle } from 'lucide-vue-next'

interface BlogStats {
  total_posts: number
  total_views: number
  total_likes: number
  total_comments: number
}

interface Props {
  stats: BlogStats
}

defineProps<Props>()

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
  <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
    <!-- Total Posts -->
    <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl p-6 text-center border border-blue-200 dark:border-blue-800">
      <div class="flex justify-center mb-4">
        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-full flex items-center justify-center">
          <FileText class="h-6 w-6" />
        </div>
      </div>
      <div class="text-2xl font-bold text-gray-900 dark:text-white mb-1">
        {{ formatNumber(stats.total_posts) }}
      </div>
      <div class="text-sm text-gray-600 dark:text-gray-400">
        Total Posts
      </div>
    </div>

    <!-- Total Views -->
    <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-xl p-6 text-center border border-green-200 dark:border-green-800">
      <div class="flex justify-center mb-4">
        <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-full flex items-center justify-center">
          <Eye class="h-6 w-6" />
        </div>
      </div>
      <div class="text-2xl font-bold text-gray-900 dark:text-white mb-1">
        {{ formatNumber(stats.total_views) }}
      </div>
      <div class="text-sm text-gray-600 dark:text-gray-400">
        Total Views
      </div>
    </div>

    <!-- Total Likes -->
    <div class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 rounded-xl p-6 text-center border border-red-200 dark:border-red-800">
      <div class="flex justify-center mb-4">
        <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-full flex items-center justify-center">
          <Heart class="h-6 w-6" />
        </div>
      </div>
      <div class="text-2xl font-bold text-gray-900 dark:text-white mb-1">
        {{ formatNumber(stats.total_likes) }}
      </div>
      <div class="text-sm text-gray-600 dark:text-gray-400">
        Total Likes
      </div>
    </div>

    <!-- Total Comments -->
    <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-xl p-6 text-center border border-purple-200 dark:border-purple-800">
      <div class="flex justify-center mb-4">
        <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-full flex items-center justify-center">
          <MessageCircle class="h-6 w-6" />
        </div>
      </div>
      <div class="text-2xl font-bold text-gray-900 dark:text-white mb-1">
        {{ formatNumber(stats.total_comments) }}
      </div>
      <div class="text-sm text-gray-600 dark:text-gray-400">
        Total Comments
      </div>
    </div>
  </div>
</template>
