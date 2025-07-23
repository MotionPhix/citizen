<script setup lang="ts">
import ContentContainer from '@/components/ContentContainer.vue'
import { ref } from 'vue'

interface MediaItem {
  id: number
  url: string
  thumbnail_url: string
  alt: string
}

interface Props {
  media: MediaItem[]
}

defineProps<Props>()

const selectedImage = ref<MediaItem | null>(null)
const showModal = ref(false)

const openModal = (image: MediaItem) => {
  selectedImage.value = image
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
  selectedImage.value = null
}
</script>

<template>
  <section class="py-16 bg-gray-50 dark:bg-gray-900">
    <ContentContainer>
      <h2 class="text-3xl md:text-4xl font-display font-bold text-center mb-12 text-ca-primary dark:text-white">
        Project Gallery
      </h2>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="image in media"
          :key="image.id"
          class="relative group cursor-pointer overflow-hidden rounded-lg shadow-lg hover:shadow-xl transition-all duration-300"
          @click="openModal(image)"
        >
          <img
            :src="image.thumbnail_url"
            :alt="image.alt"
            class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-300"
            loading="lazy"
          />
          <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 flex items-center justify-center">
            <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">
              <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
              </svg>
            </div>
          </div>
        </div>
      </div>
    </ContentContainer>

    <!-- Modal -->
    <div
      v-if="showModal && selectedImage"
      class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75"
      @click="closeModal"
    >
      <div class="relative max-w-4xl max-h-full p-4">
        <button
          @click="closeModal"
          class="absolute top-4 right-4 text-white hover:text-gray-300 z-10"
        >
          <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
        <img
          :src="selectedImage.url"
          :alt="selectedImage.alt"
          class="max-w-full max-h-full object-contain"
          @click.stop
        />
      </div>
    </div>
  </section>
</template>
