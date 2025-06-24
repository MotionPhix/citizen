<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'
import { useDark } from '@vueuse/core'
import { Menu, X } from 'lucide-vue-next'
import ModeSwitch from './ModeSwitch.vue'

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

// Close menu when clicking outside the mobile menu and the menu button
const closeMenu = (event: MouseEvent) => {
  const target = event.target as HTMLElement
  if (!target.closest('.mobile-menu') && !target.closest('.menu-button')) {
    isMenuOpen.value = false
  }
}

// Close the mobile menu if the window is resized to desktop width
const handleResize = () => {
  if (window.innerWidth >= 768) {
    isMenuOpen.value = false
  }
}

onMounted(() => {
  document.addEventListener('click', closeMenu)
  window.addEventListener('resize', handleResize)
})

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
          <a href="/" class="block">
            <img
              :src="isDark ? logo.dark : logo.light"
              alt="Logo"
              class="h-8 w-auto"
            >
          </a>
        </div>

        <!-- Desktop Navigation -->
        <div class="hidden md:flex items-center space-x-2">
          <template v-for="link in links" :key="link.url">
            <a
              :href="link.url"
              class="relative px-3 py-2 rounded-md transition ease-in-out duration-300"
              :class="link.isActive
                ? 'text-ca-primary font-black text-underline dark:text-ca-highlight underline decoration-wavy underline-offset-8 '
                : 'text-gray-600 dark:text-gray-300 hover:text-ca-primary'">
              {{ link.name }}
<!--              <span v-if="link.isActive" class="absolute inset-x-0 -bottom-1 border-wave border-b-2 border-ca-primary"></span>-->
            </a>
          </template>

          <!-- Dark Mode Toggle -->
          <div class="ml-4">
            <ModeSwitch />
          </div>
        </div>

        <!-- Mobile Menu Button -->
        <button
          class="md:hidden p-2 rounded-md text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-ca-primary dark:focus:ring-ca-highlight menu-button"
          @click="toggleMenu"
        >
          <span class="sr-only">Toggle main menu</span>
          <Menu v-if="!isMenuOpen" class="h-6 w-6" />
          <X v-else class="h-6 w-6" />
        </button>
      </nav>

      <!-- Mobile Navigation -->
      <transition
        enter-active-class="transition transform duration-200 ease-out"
        enter-from-class="opacity-0 -translate-y-2"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition transform duration-150 ease-in"
        leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0 -translate-y-2"
      >
        <div
          v-if="isMenuOpen"
          class="md:hidden mobile-menu absolute left-0 right-0 mt-2 rounded-b-md shadow-lg"
        >
          <div class="px-2 pt-2 pb-3 bg-white dark:bg-gray-900">
            <template v-for="link in links" :key="link.url">
              <a
                :href="link.url"
                class="block px-4 py-2 rounded-md text-base font-medium transition duration-200 ease-in-out"
                :class="link.isActive
                  ? 'text-ca-primary dark:text-ca-highlight bg-ca-primary/10 dark:bg-ca-highlight/10'
                  : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white'"
                @click="isMenuOpen = false"
              >
                {{ link.name }}
              </a>
            </template>

            <!-- Mobile Dark Mode Toggle -->
            <div class="px-4 py-2">
              <ModeSwitch />
            </div>
          </div>
        </div>
      </transition>
    </div>
  </header>
</template>

<style scoped>
.mobile-menu {
  position: absolute;
  top: 100%;
  left: 0;
  right: 0;
}

/* Optionally refine dark mode styling for the mobile menu */
@media (prefers-color-scheme: dark) {
  .mobile-menu {
    background-color: rgb(17 24 39 / 0.95);
  }
}
</style>
