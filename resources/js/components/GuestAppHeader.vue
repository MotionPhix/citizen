<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'
import { useDark } from '@vueuse/core'
import { Menu, X } from 'lucide-vue-next'
import ModeSwitch from './ModeSwitch.vue'
import {Link} from "@inertiajs/vue3"

interface NavLink {
  name: string
  url: string
  isActive: boolean
}

interface Props {
  links: NavLink[]
  logo?: {
    light: string
    dark: string
  }
}

withDefaults(defineProps<Props>(), {
  logo: () => ({
    light: '/images/logo.png',
    dark: '/images/logo-white.png'
  })
})

const isDark = useDark()
const isMenuOpen = ref(false)

const toggleMenu = () => {
  isMenuOpen.value = !isMenuOpen.value
}

// Close menu when clicking outside
const closeMenu = (event: MouseEvent) => {
  const target = event.target as HTMLElement
  if (!target.closest('.mobile-menu') && !target.closest('.menu-button')) {
    isMenuOpen.value = false
  }
}

// Close menu on resize if screen becomes larger
const handleResize = () => {
  if (window.innerWidth >= 768) {
    isMenuOpen.value = false
  }
}

// Add event listeners
onMounted(() => {
  document.addEventListener('click', closeMenu)
  window.addEventListener('resize', handleResize)
})

// Remove event listeners
onUnmounted(() => {
  document.removeEventListener('click', closeMenu)
  window.removeEventListener('resize', handleResize)
})
</script>

<template>
  <header class="sticky top-0 z-50 bg-white/80 dark:bg-gray-900/80 backdrop-blur-lg border-b border-gray-200 dark:border-gray-800">
    <div class="container mx-auto px-4">
      <nav class="flex items-center justify-between h-16">
        <!-- Logo -->
        <div class="flex-shrink-0">
          <Link href="/" class="block">
            <img
              :src="isDark ? logo.dark : logo.light"
              alt="Citizen Alliance"
              class="h-8 w-auto"
            >
          </Link>
        </div>

        <!-- Desktop Navigation -->
        <div class="hidden md:flex items-center space-x-1">
          <template v-for="link in links" :key="link.url">
            <Link
              :href="link.url"
              class="px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200"
              :class="[
                link.isActive
                  ? 'text-ca-primary dark:text-ca-highlight bg-ca-primary/10 dark:bg-ca-highlight/10'
                  : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800'
              ]">
              {{ link.name }}
            </Link>
          </template>

          <!-- Dark Mode Toggle -->
          <div class="ml-4">
            <ModeSwitch />
          </div>
        </div>

        <!-- Mobile Menu Button -->
        <button
          class="md:hidden p-2 rounded-md text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-ca-primary dark:focus:ring-ca-highlight menu-button"
          @click="toggleMenu">
          <span class="sr-only">Open main menu</span>
          <Menu v-if="!isMenuOpen" class="h-6 w-6" />
          <X v-else class="h-6 w-6" />
        </button>
      </nav>

      <!-- Mobile Navigation -->
      <Transition
        enter-active-class="transition duration-200 ease-out"
        enter-from-class="transform -translate-y-4 opacity-0"
        enter-to-class="transform translate-y-0 opacity-100"
        leave-active-class="transition duration-150 ease-in"
        leave-from-class="transform translate-y-0 opacity-100"
        leave-to-class="transform -translate-y-4 opacity-0">
        <div
          v-if="isMenuOpen"
          class="md:hidden mobile-menu">
          <div class="px-2 pt-2 pb-3 space-y-1 border-t border-gray-200 dark:border-gray-800">
            <template v-for="link in links" :key="link.url">
              <Link
                :href="link.url"
                class="block px-3 py-2 rounded-md text-base font-medium transition-colors duration-200"
                :class="[
                  link.isActive
                    ? 'text-ca-primary dark:text-ca-highlight bg-ca-primary/10 dark:bg-ca-highlight/10'
                    : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800'
                ]"
                @click="isMenuOpen = false">
                {{ link.name }}
              </Link>
            </template>

            <!-- Mobile Dark Mode Toggle -->
            <div class="px-3 py-2">
              <ModeSwitch />
            </div>
          </div>
        </div>
      </Transition>
    </div>
  </header>
</template>

<style scoped>
.mobile-menu {
  position: absolute;
  top: 100%;
  left: 0;
  right: 0;
  background-color: rgb(255 255 255 / 0.8);
  backdrop-filter: blur(12px);
}

:deep(.dark) .mobile-menu {
  background-color: rgb(17 24 39 / 0.8);
}
</style>
