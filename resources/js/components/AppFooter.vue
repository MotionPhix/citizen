<script setup lang="ts">
import { useDark } from '@vueuse/core'
import type { RouteLink, SocialLink } from '@/types'
import { Link, useForm } from '@inertiajs/vue3';
import SocialIcon from '@/components/icons/SocialIcon.vue';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';

interface Props {
  routes: RouteLink[]
  socials: SocialLink[]
  year?: number
}

withDefaults(defineProps<Props>(), {
  year: () => new Date().getFullYear()
})

const isDark = useDark()
const form = useForm({
  email: ''
})

const handleSubmit = async () => {
  if (!email.value) return

  try {
    // Add your newsletter subscription logic here
    await new Promise(resolve => setTimeout(resolve, 1000))
    success.value = true
    email.value = ''
  } catch (error) {
    console.error('Newsletter subscription failed:', error)
  } finally {
    loading.value = false
  }
}
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
          <ul class="space-y-4">
            <li v-for="path in routes" :key="path.name">
              <Link
                :href="route(path.url)"
                 class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors duration-300">
                {{ path.name }}
              </Link>
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

          <form
            @submit.prevent="handleSubmit"
            class="relative">
            <div class="flex">
              <Input
                v-model="form.email"
                type="email"
                required class="!rounded-e-none h-12 outline-none !focus:outline-none"
                placeholder="Email Address"
              />

              <Button
                type="submit"
                class="!rounded-s-none grow-0 h-11"
                :disabled="form.processing">
                <span v-if="!form.processing">Subscribe</span>
                <span v-else class="flex items-center">
                  <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                </span>
              </Button>
            </div>

            <Transition
              enter-active-class="transition ease-out duration-300"
              enter-from-class="opacity-0 transform translate-y-2"
              enter-to-class="opacity-100 transform translate-y-0">
              <div v-if="form.recentlySuccessful" class="absolute mt-2 text-sm text-green-500 dark:text-green-400">
                Thank you for subscribing!
              </div>
            </Transition>
          </form>
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
