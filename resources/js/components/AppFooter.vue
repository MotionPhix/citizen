<script setup lang="ts">
import { useDark } from '@vueuse/core'
import type { RouteLink, SocialLink } from '@/types'
import SocialIcon from '@/components/icons/SocialIcon.vue'
import Newsletter from '@/components/Newsletter.vue'

interface Props {
  routes: RouteLink[]
  socials: SocialLink[]
  year?: number
}

withDefaults(defineProps<Props>(), {
  year: () => new Date().getFullYear()
})

const isDark = useDark()
</script>

<template>
  <footer class="bg-gray-100 dark:bg-gray-900 text-gray-600 dark:text-gray-300">
    <!-- Footer Top -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        <!-- About Column -->
        <div>
          <img
            :src="isDark ? '/images/logo-white.png' : '/images/logo.png'"
            alt="Citizen Alliance"
            class="h-12 mb-4">

          <p class="text-sm leading-relaxed mb-6 text-gray-600 dark:text-gray-400">
            A coalition of civil society organizations and citizen groups established in 2012 as a citizen-led engagement initiative on development and governance processes.
          </p>

          <div class="flex gap-4">
            <a
              v-for="social in socials"
              :key="social.platform"
              :href="social.url"
              :title="social.label"
              target="_blank"
              rel="noopener noreferrer"
              class="p-2 rounded-full bg-gray-200 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-ca-primary dark:hover:bg-ca-highlight hover:text-white dark:hover:text-white transition-all duration-300">
              <SocialIcon :platform="social.platform" />
            </a>
          </div>
        </div>

        <!-- Quick Links -->
        <div>
          <h3 class="text-gray-900 dark:text-white text-lg font-semibold mb-6 font-display">
            Quick Links
          </h3>
          <ul class="space-y-2">
            <li v-for="path in routes" :key="path.name">
              <a
                :href="route(path.url)"
                class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors duration-300">
                {{ path.name }}
              </a>
            </li>
          </ul>
        </div>

        <!-- Office Hours -->
        <div>
          <h3 class="text-gray-900 dark:text-white text-lg font-semibold mb-6 font-display">
            Office Hours
          </h3>

          <ul class="space-y-4">
            <li class="grid">
              <span class="text-gray-600 dark:text-gray-400">Monday - Friday</span>
              <span class="text-gray-900 dark:text-white">8:00 AM - 4:00 PM</span>
            </li>

            <li class="grid">
              <span class="text-gray-600 dark:text-gray-400">Saturday & Sunday</span>
              <span class="text-gray-900 dark:text-white">Closed</span>
            </li>
          </ul>
        </div>

        <!-- Newsletter -->
        <div>
          <h3 class="text-gray-900 dark:text-white text-lg font-semibold mb-6 font-display">
            Newsletter
          </h3>

          <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
            Subscribe to our newsletter to get all our news in your inbox. Stay connected with our latest updates.
          </p>

          <!-- Using the Newsletter component with footer styling -->
          <Newsletter compact />
        </div>
      </div>
    </div>

    <!-- Copyright -->
    <div class="border-t border-gray-200 dark:border-gray-800">
      <div class="container mx-auto px-4 py-6">
        <div class="text-center text-xs text-gray-600 dark:text-gray-400">
          <p>&copy; {{ year }} Citizen Alliance. All Rights Reserved. Developed by
            <a href="https://ultrashots.net/"
               target="_blank"
               class="text-ca-primary dark:text-ca-highlight hover:text-ca-primary-dark dark:hover:text-ca-highlight/90 transition-colors duration-300">
              Ultrashots
            </a>
          </p>
        </div>
      </div>
    </div>
  </footer>
</template>
