<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import GuestLayout from '@/layouts/GuestLayout.vue'
import ContentContainer from '@/components/ContentContainer.vue'

// This is a placeholder page - you can expand it with actual partnerships content
</script>

<template>
  <Head>
    <title>Partnerships</title>
    <meta
      name="description"
      content="Learn about our partnerships and collaborations with organizations working towards sustainable development."
    />
  </Head>

  <GuestLayout>
    <ContentContainer class="py-16">
      <div class="text-center">
        <h1 class="text-4xl md:text-5xl font-display font-bold text-gray-900 dark:text-white mb-6">
          Our Partnerships
        </h1>
        <p class="text-xl text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
          Working together with organizations and communities to create lasting change.
        </p>
      </div>

      <div class="mt-16 text-center">
        <p class="text-gray-600 dark:text-gray-400">
          Partnership content coming soon...
        </p>
      </div>
    </ContentContainer>
  </GuestLayout>
</template>
