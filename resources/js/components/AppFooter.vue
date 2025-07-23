<script setup lang="ts">
import { useDark } from '@vueuse/core'
import { onMounted } from 'vue'
import { gsap } from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'
import type { RouteLink, SocialLink } from '@/types'
import SocialIcon from '@/components/icons/SocialIcon.vue'
import Newsletter from '@/components/Newsletter.vue'
import { MapPin, Phone, Mail, Clock } from 'lucide-vue-next'

interface Props {
  routes: RouteLink[]
  socials: SocialLink[]
  year?: number
}

withDefaults(defineProps<Props>(), {
  year: () => new Date().getFullYear()
})

const isDark = useDark()

// Register GSAP plugins
gsap.registerPlugin(ScrollTrigger)

// Footer animations
onMounted(() => {
  gsap.fromTo('.footer-column',
    {
      opacity: 0,
      y: 30
    },
    {
      opacity: 1,
      y: 0,
      duration: 0.8,
      stagger: 0.1,
      ease: "power2.out",
      scrollTrigger: {
        trigger: '.footer-content',
        start: 'top 90%',
        toggleActions: 'play none none reverse'
      }
    }
  )
})
</script>

<template>
  <footer class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 text-gray-600 dark:text-gray-300">
    <!-- Footer Content -->
    <div class="footer-content max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-12">

        <!-- Logo, Quick Links & Social Column -->
        <div class="footer-column">
          <!-- Logo -->
          <div class="mb-6">
            <img
              :src="isDark ? '/images/logo-white.png' : '/images/logo.png'"
              alt="Citizen Alliance"
              class="h-10 sm:h-12 mb-6"
            >
          </div>

          <!-- Quick Links -->
          <div class="mb-6 pl-6">
            <ul class="space-y-2 divide-y divide-gray-500 dark:divide-blue-100">
              <li v-for="path in routes" :key="path.name" class="pt-2">
                <a
                  :href="route(path.url)"
                  class="text-sm text-gray-600 dark:text-gray-400 hover:text-ca-primary dark:hover:text-ca-highlight transition-colors duration-300 flex items-center group"
                >
                  <span class="w-1.5 h-1.5 bg-gray-400 dark:bg-gray-600 rounded-full mr-3 group-hover:bg-ca-primary dark:group-hover:bg-ca-highlight transition-colors duration-300"></span>
                  {{ path.name }}
                </a>
              </li>
            </ul>
          </div>

          <!-- Social Links -->
          <div class="pl-6">
            <h3 class="text-gray-900 dark:text-white text-lg font-display font-semibold mb-4">
              Follow Us
            </h3>
            <div class="flex flex-wrap gap-3">
              <a
                v-for="social in socials"
                :key="social.platform"
                :href="social.url"
                :title="social.label"
                target="_blank"
                rel="noopener noreferrer"
                class="w-10 h-10 rounded-xl bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-ca-primary dark:hover:bg-ca-highlight hover:text-white dark:hover:text-white transition-all duration-300 flex items-center justify-center shadow-sm hover:shadow-md border border-gray-200 dark:border-gray-700 hover:border-transparent"
              >
                <SocialIcon :platform="social.platform" class="w-4 h-4" />
              </a>
            </div>
          </div>
        </div>

        <!-- Contact & Office Hours -->
        <div class="footer-column">
          <h3 class="text-gray-900 dark:text-white text-lg font-display font-semibold mb-6">
            Contact & Hours
          </h3>

          <!-- Contact Info -->
          <div class="space-y-4 mb-6">
            <div class="flex items-start">
              <Phone class="w-4 h-4 text-ca-primary dark:text-ca-highlight mt-0.5 mr-3 flex-shrink-0" />
              <div>
                <p class="text-sm text-gray-900 dark:text-white font-medium">Phone</p>
                <a href="tel:+265991602233" class="text-sm text-gray-600 dark:text-gray-400 hover:text-ca-primary dark:hover:text-ca-highlight transition-colors">
                  +265 (0) 991 602 233
                </a>
              </div>
            </div>

            <div class="flex items-start">
              <Mail class="w-4 h-4 text-ca-primary dark:text-ca-highlight mt-0.5 mr-3 flex-shrink-0" />
              <div>
                <p class="text-sm text-gray-900 dark:text-white font-medium">Email</p>
                <a href="mailto:support@citizenalliancemw.org" class="text-sm text-gray-600 dark:text-gray-400 hover:text-ca-primary dark:hover:text-ca-highlight transition-colors break-all">
                  support@citizenalliancemw.org
                </a>
              </div>
            </div>
          </div>

          <!-- Office Hours -->
          <div class="flex items-start">
            <Clock class="w-4 h-4 text-ca-primary dark:text-ca-highlight mt-0.5 mr-3 flex-shrink-0" />
            <div>
              <p class="text-sm text-gray-900 dark:text-white font-medium mb-2">Office Hours</p>
              <div class="space-y-1">
                <div class="text-xs text-gray-600 dark:text-gray-400">
                  <span class="block">Mon - Fri: 8:00 AM - 4:00 PM</span>
                  <span class="block">Sat - Sun: Closed</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Newsletter -->
        <div class="footer-column">
          <h3 class="text-gray-900 dark:text-white text-lg font-display font-semibold mb-6">
            Stay Updated
          </h3>

          <p class="text-sm text-gray-600 dark:text-gray-400 mb-6 leading-relaxed">
            Subscribe to our newsletter for the latest updates on our initiatives and community impact.
          </p>

          <!-- Newsletter Component -->
          <Newsletter variant="footer" compact />
        </div>
      </div>
    </div>

    <!-- Footer Bottom -->
    <div class="border-t border-gray-200 dark:border-gray-700">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
          <div class="text-center sm:text-left">
            <p class="text-xs text-gray-600 dark:text-gray-400">
              &copy; {{ year }} Citizen Alliance. All Rights Reserved.
            </p>
          </div>

          <div class="text-center sm:text-right">
            <p class="text-xs text-gray-600 dark:text-gray-400">
              Developed by
              <a
                href="https://ultrashots.net/"
                target="_blank"
                rel="noopener noreferrer"
                class="text-ca-primary dark:text-ca-highlight hover:text-ca-primary/80 dark:hover:text-ca-highlight/80 transition-colors duration-300 font-medium"
              >
                Ultrashots
              </a>
            </p>
          </div>
        </div>
      </div>
    </div>
  </footer>
</template>
