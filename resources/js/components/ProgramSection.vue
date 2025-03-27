<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import { ArrowRight } from 'lucide-vue-next'

interface Program {
  title: string
  description: string
  slug: string
}

defineProps<{
  programs: Program[]
}>()
</script>

<template>
  <section class="relative py-16 overflow-hidden">
    <!-- Background Pattern (desktop only) -->
    <div class="absolute inset-0 hidden lg:block">
      <div class="absolute inset-y-0 right-0 w-1/2">
        <!-- Gradient overlay -->
        <div class="absolute inset-0 bg-gradient-to-l from-transparent via-white/90 to-white dark:from-transparent dark:via-gray-950/90 dark:to-gray-950" />

        <!-- Pattern -->
        <div class="absolute inset-0 opacity-10 dark:opacity-20">
          <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
            <defs>
              <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                <path d="M 10 0 L 0 0 0 10" fill="none" stroke="currentColor" stroke-width="0.5" />
              </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#grid)" />
          </svg>
        </div>

        <!-- Optional: Add decorative elements -->
        <div class="absolute inset-0">
          <div class="absolute top-1/4 right-1/4 w-64 h-64 bg-ca-primary/10 dark:bg-ca-highlight/10 rounded-full blur-3xl" />
          <div class="absolute bottom-1/4 right-1/3 w-48 h-48 bg-ca-amber/10 dark:bg-ca-amber/10 rounded-full blur-2xl" />
        </div>
      </div>
    </div>

    <div class="container relative mx-auto px-4">
      <h2 class="text-4xl text-center font-display font-bold mb-12 text-ca-primary dark:text-ca-highlight">
        Our Core Programs
      </h2>

      <div class="space-y-12 divide-y-2 dark:divide-gray-500">
        <div
          v-for="(program, index) in programs"
          :key="program.slug"
          class="group py-6">
          <div
            class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center"
            :class="index % 2 === 0 ? '' : 'lg:flex-row-reverse'">
            <div class="space-y-6 relative">
              <!-- Decorative line -->
              <div
                class="absolute -left-4 top-0 bottom-0 w-0.5 bg-gradient-to-b from-ca-primary via-ca-amber to-transparent dark:from-ca-highlight"
                :class="{'opacity-0 group-hover:opacity-100': true}"
              />

              <h3 class="text-2xl font-display font-semibold text-gray-900 dark:text-gray-100 group-hover:text-ca-primary dark:group-hover:text-ca-highlight transition-colors duration-300">
                {{ program.title }}
              </h3>

              <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                {{ program.description }}
              </p>

              <Link
                href="#"
                class="inline-flex items-center space-x-2 text-ca-amber hover:text-ca-primary dark:hover:text-ca-highlight transition-colors duration-300 group/link">
                <span class="font-semibold">Learn More</span>
<!--                route('programs.show', program.slug)-->
                <ArrowRight
                  class="w-4 h-4 transform group-hover/link:translate-x-1 transition-transform duration-300"
                />
              </Link>
            </div>

            <!-- Visual element for desktop -->
            <div class="hidden lg:block relative h-full">
              <div
                class="absolute inset-0 bg-gradient-to-r from-ca-primary/5 to-ca-amber/5 dark:from-ca-highlight/5 dark:to-ca-amber/5 rounded-2xl transform group-hover:scale-95 transition-transform duration-500"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
