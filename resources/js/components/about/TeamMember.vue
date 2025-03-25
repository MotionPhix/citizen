<script setup lang="ts">
import { IconBrandLinkedin, IconBrandX } from '@tabler/icons-vue'
import { Button } from '@/components/ui/button'
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from "@/components/ui/dialog"

interface Props {
  member: {
    name: string
    position: string
    bio: string
    image: string
    linkedin?: string
    twitter?: string
  }
}

defineProps<Props>()
</script>

<template>
  <Dialog>
    <DialogTrigger asChild>
      <div
        class="group relative overflow-hidden rounded-xl bg-white dark:bg-gray-800 shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 cursor-pointer"
      >
        <div class="aspect-w-5 aspect-h-6">
          <img
            :src="member.image"
            :alt="member.name"
            class="object-cover w-full h-full transform group-hover:scale-105 transition-transform duration-500"
          >
          <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-60 group-hover:opacity-70 transition-opacity duration-300" />
        </div>

        <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
          <div class="transform translate-y-8 group-hover:translate-y-0 transition-transform duration-300">
            <h3 class="text-xl font-display font-semibold mb-1">
              {{ member.name }}
            </h3>

            <p class="text-white/90 font-medium">
              {{ member.position }}
            </p>

            <div class="h-0 group-hover:h-auto overflow-hidden transition-all duration-300 mt-3 opacity-0 group-hover:opacity-100">
              <div class="flex items-center space-x-4">
                <a
                  v-if="member.linkedin"
                  :href="member.linkedin"
                  class="text-white/70 hover:text-white transition-colors duration-300"
                  target="_blank"
                  rel="noopener noreferrer"
                >
                  <IconBrandLinkedin class="w-5 h-5" />
                </a>
                <a
                  v-if="member.twitter"
                  :href="member.twitter"
                  class="text-white/70 hover:text-white transition-colors duration-300"
                  target="_blank"
                  rel="noopener noreferrer"
                >
                  <IconBrandX class="w-5 h-5" />
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </DialogTrigger>

    <DialogContent class="sm:max-w-2xl">
      <DialogHeader>
        <DialogTitle>{{ member.name }}</DialogTitle>
        <DialogDescription class="text-ca-primary dark:text-ca-primary/90">
          {{ member.position }}
        </DialogDescription>
      </DialogHeader>

      <div class="grid sm:grid-cols-2 gap-6">
        <img
          :src="member.image"
          :alt="member.name"
          class="rounded-lg object-cover w-full aspect-square"
        >
        <div class="space-y-4">
          <p class="text-gray-600 dark:text-gray-300">
            {{ member.bio }}
          </p>

          <div class="flex items-center space-x-4">
            <Button
              v-if="member.linkedin"
              variant="outline"
              size="sm"
              :href="member.linkedin"
              target="_blank"
              rel="noopener noreferrer"
            >
              <IconBrandLinkedin class="w-4 h-4 mr-2" />
              LinkedIn
            </Button>

            <Button
              v-if="member.twitter"
              variant="outline"
              size="sm"
              :href="member.twitter"
              target="_blank"
              rel="noopener noreferrer"
            >
              <IconBrandX class="w-4 h-4 mr-2" />
              Twitter
            </Button>
          </div>
        </div>
      </div>
    </DialogContent>
  </Dialog>
</template>
