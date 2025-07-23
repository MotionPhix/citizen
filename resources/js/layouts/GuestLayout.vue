<script setup lang="ts">
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import GuestAppHeader from '@/components/GuestAppHeader.vue'
import AppFooter from '@/components/AppFooter.vue'
import ToastMessages from '@/components/ToastMessages.vue'
import { Toaster } from 'vue-sonner'

interface NavLink {
  name: string
  url: string
  isActive: boolean
}

interface RouteLink {
  name: string
  url: string
}

interface SocialLink {
  platform: string
  url: string
  label: string
}

const page = usePage()

// Generate navigation links with active state
const navigationLinks = computed<NavLink[]>(() => [
  {
    name: 'Home',
    url: route('home'),
    isActive: page.url === '/' || page.url.startsWith('/home')
  },
  {
    name: 'Organisation',
    url: route('about'),
    isActive: page.url.startsWith('/organisation')
  },
  {
    name: 'Projects',
    url: route('projects.index'),
    isActive: page.url.startsWith('/projects')
  },
  {
    name: 'Contact',
    url: route('contact.index'),
    isActive: page.url.startsWith('/contact')
  },
  {
    name: 'Blog',
    url: route('blogs.index'),
    isActive: page.url.startsWith('/blogs')
  }
])

// Footer route links
const footerRoutes: RouteLink[] = [
  { name: 'Home', url: 'home' },
  { name: 'Organisation', url: 'about' },
  { name: 'Projects', url: 'projects.index' },
  { name: 'Contact Us', url: 'contact.index' },
  { name: 'Blog', url: 'blogs.index' }
]

// Social media links
const socialLinks: SocialLink[] = [
  {
    platform: 'facebook',
    url: 'https://facebook.com/citizenalliance',
    label: 'Follow us on Facebook'
  },
  {
    platform: 'twitter',
    url: 'https://twitter.com/citizenalliance',
    label: 'Follow us on Twitter'
  },
  {
    platform: 'linkedin',
    url: 'https://linkedin.com/company/citizenalliance',
    label: 'Connect with us on LinkedIn'
  },
  {
    platform: 'instagram',
    url: 'https://instagram.com/citizenalliance',
    label: 'Follow us on Instagram'
  }
]
</script>

<template>
  <div class="min-h-screen flex flex-col">
    <!-- Header -->
    <GuestAppHeader :links="navigationLinks" />

    <!-- Main Content -->
    <main class="flex-1">
      <slot />
    </main>

    <!-- Footer -->
    <AppFooter
      :routes="footerRoutes"
      :socials="socialLinks"
    />

    <!-- Toast Messages -->
    <Toaster rich-colors />
    <ToastMessages />
  </div>
</template>
