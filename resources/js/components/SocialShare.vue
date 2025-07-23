<script setup lang="ts">
import { Share2, Facebook, Twitter, Linkedin, Link } from 'lucide-vue-next'
import { ref } from 'vue'

interface Props {
  url: string
  title: string
  description?: string
}

const props = defineProps<Props>()

const showTooltip = ref(false)

const shareOnFacebook = () => {
  const shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(props.url)}`
  window.open(shareUrl, '_blank', 'width=600,height=400')
}

const shareOnTwitter = () => {
  const shareUrl = `https://twitter.com/intent/tweet?url=${encodeURIComponent(props.url)}&text=${encodeURIComponent(props.title)}`
  window.open(shareUrl, '_blank', 'width=600,height=400')
}

const shareOnLinkedIn = () => {
  const shareUrl = `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(props.url)}`
  window.open(shareUrl, '_blank', 'width=600,height=400')
}

const copyToClipboard = async () => {
  try {
    await navigator.clipboard.writeText(props.url)
    showTooltip.value = true
    setTimeout(() => {
      showTooltip.value = false
    }, 2000)
  } catch (err) {
    console.error('Failed to copy: ', err)
  }
}
</script>

<template>
  <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
      Share This Project
    </h3>

    <div class="grid grid-cols-2 gap-3">
      <button
        @click="shareOnFacebook"
        class="flex items-center justify-center px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-300 text-sm font-medium"
        title="Share on Facebook"
      >
        <Facebook class="w-4 h-4 mr-2" />
        Facebook
      </button>

      <button
        @click="shareOnTwitter"
        class="flex items-center justify-center px-4 py-3 bg-sky-500 text-white rounded-lg hover:bg-sky-600 transition-colors duration-300 text-sm font-medium"
        title="Share on Twitter"
      >
        <Twitter class="w-4 h-4 mr-2" />
        Twitter
      </button>

      <button
        @click="shareOnLinkedIn"
        class="flex items-center justify-center px-4 py-3 bg-blue-700 text-white rounded-lg hover:bg-blue-800 transition-colors duration-300 text-sm font-medium"
        title="Share on LinkedIn"
      >
        <Linkedin class="w-4 h-4 mr-2" />
        LinkedIn
      </button>

      <div class="relative">
        <button
          @click="copyToClipboard"
          class="flex items-center justify-center w-full px-4 py-3 bg-gray-600 dark:bg-gray-700 text-white rounded-lg hover:bg-gray-700 dark:hover:bg-gray-600 transition-colors duration-300 text-sm font-medium"
          title="Copy link"
        >
          <Link class="w-4 h-4 mr-2" />
          Copy Link
        </button>

        <div
          v-if="showTooltip"
          class="absolute -top-10 left-1/2 transform -translate-x-1/2 px-3 py-1 bg-gray-900 dark:bg-gray-700 text-white text-xs rounded-lg whitespace-nowrap z-10"
        >
          Link copied!
          <div class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-900 dark:border-t-gray-700"></div>
        </div>
      </div>
    </div>
  </div>
</template>
