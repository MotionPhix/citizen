<script setup lang="ts">
import { ExternalLink } from 'lucide-vue-next'

interface Partner {
  name: string
  logo: string
  website: string
}

interface Props {
  partner: Partner
  index: number
}

defineProps<Props>()
</script>

<template>
  <a
    :href="partner.website"
    target="_blank"
    rel="noopener noreferrer"
    class="group bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-lg hover:border-ca-primary/50 dark:hover:border-ca-highlight/50 transition-all duration-300 flex flex-col items-center justify-center min-h-[120px]"
  >
    <!-- Logo -->
    <div class="w-full h-16 flex items-center justify-center mb-3">
      <img
        :src="partner.logo"
        :alt="partner.name"
        class="max-w-full max-h-full object-contain filter grayscale group-hover:grayscale-0 transition-all duration-300"
        loading="lazy"
      />
    </div>

    <!-- Partner Name -->
    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 text-center group-hover:text-ca-primary dark:group-hover:text-ca-highlight transition-colors duration-300 line-clamp-2">
      {{ partner.name }}
    </h3>

    <!-- External Link Icon -->
    <div class="mt-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
      <ExternalLink class="w-4 h-4 text-ca-primary dark:text-ca-highlight" />
    </div>
  </a>
</template>
