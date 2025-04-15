<script setup lang="ts">
import { Clock, Eye, Heart, User } from 'lucide-vue-next';

interface Tag {
  id: number;
  name: string;
}

defineProps<{
  title: string;
  tags: Tag[];
  author: string;
  publishedDate: string;
  likesCount: number|string;
  readingTime: number|string;
  viewCount: number|string;
}>();
</script>

<template>
  <div class="max-w-4xl mx-auto mb-12">
    <h1 class="text-4xl md:text-5xl font-display font-bold text-gray-900 dark:text-white mb-6">
      {{ title }}
    </h1>

    <!-- Tags -->
    <div class="flex flex-wrap gap-2 mb-6">
      <a
        v-for="tag in tags"
        :key="tag.id"
        :href="route('blogs.index', { tag: tag.name })"
        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors"
      >
        #{{ tag.name }}
      </a>
    </div>

    <!-- Meta Info -->
    <div class="flex flex-wrap items-center gap-x-6 gap-y-3 text-sm text-gray-600 dark:text-gray-400">
      <div class="flex items-center gap-1.5">
        <User class="h-4 w-4" />
        <span>{{ author }}</span>
      </div>
      <div class="flex items-center gap-1.5">
        <Clock class="h-4 w-4" />
        <time :datetime="publishedDate">{{ publishedDate }}</time>
      </div>
      <div class="flex items-center gap-1.5">
        <Heart class="h-4 w-4" />
        <span>{{ likesCount }} likes</span>
      </div>
      <div class="flex items-center gap-1.5">
        <Clock class="h-4 w-4" />
        <span>{{ readingTime }} min read</span>
      </div>
      <div class="flex items-center gap-1.5">
        <Eye class="h-4 w-4" />
        <span>{{ viewCount }} views</span>
      </div>
    </div>
  </div>
</template>
