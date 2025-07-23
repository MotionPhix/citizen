<script setup lang="ts">
import { ref } from 'vue'
import { Button } from '@/components/ui/button'
import { Share2, Facebook, Twitter, Linkedin, Copy, Check } from 'lucide-vue-next'

interface Props {
  url: string
  title: string
  description?: string
  class?: string
}

const props = defineProps<Props>()

const showDropdown = ref(false)
const copied = ref(false)

const shareOptions = [
  {
    name: 'Facebook',
    icon: Facebook,
    url: `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(props.url)}`,
    color: 'text-blue-600'
  },
  {
    name: 'Twitter',
    icon: Twitter,
    url: `https://twitter.com/intent/tweet?url=${encodeURIComponent(props.url)}&text=${encodeURIComponent(props.title)}`,
    color: 'text-sky-500'
  },
  {
    name: 'LinkedIn',
    icon: Linkedin,
    url: `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(props.url)}`,
    color: 'text-blue-700'
  }
]

const copyToClipboard = async () => {
  try {
    await navigator.clipboard.writeText(props.url)
    copied.value = true
    setTimeout(() => {
      copied.value = false
    }, 2000)
  } catch (err) {
    console.error('Failed to copy: ', err)
  }
}

const shareToSocial = (shareUrl: string) => {
  window.open(shareUrl, '_blank', 'width=600,height=400')
  showDropdown.value = false
}

const toggleDropdown = () => {
  showDropdown.value = !showDropdown.value
}

// Close dropdown when clicking outside
const closeDropdown = () => {
  showDropdown.value = false
}
</script>

<template>
  <div class="relative inline-block">
    <Button
      @click="toggleDropdown"
      variant="outline"
      size="sm"
      :class="props.class"
    >
      <Share2 class="h-4 w-4 mr-2" />
      Share
    </Button>

    <!-- Dropdown Menu -->
    <div
      v-if="showDropdown"
      class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50"
      @click.stop
    >
      <div class="py-2">
        <!-- Social Share Options -->
        <button
          v-for="option in shareOptions"
          :key="option.name"
          @click="shareToSocial(option.url)"
          class="w-full px-4 py-2 text-left hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center gap-3 transition-colors"
        >
          <component :is="option.icon" :class="`h-4 w-4 ${option.color}`" />
          <span class="text-sm text-gray-700 dark:text-gray-300">{{ option.name }}</span>
        </button>

        <!-- Divider -->
        <div class="border-t border-gray-200 dark:border-gray-600 my-2"></div>

        <!-- Copy Link -->
        <button
          @click="copyToClipboard"
          class="w-full px-4 py-2 text-left hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center gap-3 transition-colors"
        >
          <component :is="copied ? Check : Copy" :class="`h-4 w-4 ${copied ? 'text-green-500' : 'text-gray-500'}`" />
          <span class="text-sm text-gray-700 dark:text-gray-300">
            {{ copied ? 'Copied!' : 'Copy Link' }}
          </span>
        </button>
      </div>
    </div>

    <!-- Backdrop to close dropdown -->
    <div
      v-if="showDropdown"
      class="fixed inset-0 z-40"
      @click="closeDropdown"
    ></div>
  </div>
</template>
