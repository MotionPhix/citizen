<script setup lang="ts">
import { useDark, useToggle } from '@vueuse/core';

const isDark = useDark()
const toggle = useToggle(isDark)
</script>

<template>
  <button
    @click="toggle()"
    :class="{'bg-gray-200 dark:bg-gray-700': !isDark, 'bg-gray-700': isDark}"
    class="flex items-center p-1 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors duration-300">
    <!-- Light Mode Icon -->
    <svg v-if="!isDark" class="w-5 h-5 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
    </svg>

    <!-- Dark Mode Icon -->
    <svg v-else class="w-5 h-5 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
    </svg>
  </button>
</template>
