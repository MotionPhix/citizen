<script setup lang="ts">
import { Linkedin, Twitter, ExternalLink } from 'lucide-vue-next'
import { Separator } from '@/components/ui/separator';

interface TeamMember {
  name: string
  position: string
  image: string
  bio: string
  linkedin?: string
  twitter?: string
}

interface Props {
  member: TeamMember
  index: number
}

defineProps<Props>()
</script>

<template>
  <div class="flex flex-col bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-lg transition-all duration-300 group">
    <!-- Image -->
    <div class="aspect-w-1 aspect-h-1 bg-gray-100 dark:bg-gray-700">
      <img
        :src="member.image"
        :alt="member.name"
        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
        loading="lazy"
      />
    </div>

    <!-- Content -->
    <div class="p-4 flex flex-col h-full">
      <h3 class="text-lg font-display font-semibold text-gray-900 dark:text-white mb-1">
        {{ member.name }}
      </h3>

      <p class="text-sm font-medium text-ca-primary dark:text-ca-highlight mb-3">
        {{ member.position }}
      </p>

<!--      <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed mb-4 line-clamp-3">-->
<!--        {{ member.bio }}-->
<!--      </p>-->

      <div class="flex-1"></div>

      <Separator v-if="member.linkedin || member.twitter" class="mb-4" />

      <!-- Social Links -->
      <div v-if="member.linkedin || member.twitter" class="flex items-center space-x-3">
        <a
          v-if="member.linkedin"
          :href="member.linkedin"
          target="_blank"
          rel="noopener noreferrer"
          class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-lg flex items-center justify-center hover:bg-blue-200 dark:hover:bg-blue-800/50 transition-colors duration-300"
          title="LinkedIn Profile"
        >
          <Linkedin class="w-4 h-4" />
        </a>

        <a
          v-if="member.twitter"
          :href="member.twitter"
          target="_blank"
          rel="noopener noreferrer"
          class="w-8 h-8 bg-sky-100 dark:bg-sky-900/30 text-sky-600 dark:text-sky-400 rounded-lg flex items-center justify-center hover:bg-sky-200 dark:hover:bg-sky-800/50 transition-colors duration-300"
          title="Twitter Profile"
        >
          <Twitter class="w-4 h-4" />
        </a>
      </div>
    </div>
  </div>
</template>
