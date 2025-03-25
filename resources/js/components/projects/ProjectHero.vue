<script setup lang="ts">
import GridPattern from '@/components/GridPattern.vue'
import BoundingContainer from '@/components/BoundingContainer.vue'
</script>

<template>
  <section class="relative bg-gradient-to-r from-ca-primary to-ca-highlight overflow-hidden">
    <GridPattern
      class="absolute inset-0 text-white/[0.1]"
      size="60"
    />

    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent" />

    <BoundingContainer class="relative py-24">
      <div
        class="max-w-3xl"
        v-motion
        :initial="{ opacity: 0, y: 20 }"
        :enter="{ opacity: 1, y: 0 }">
        <h1 class="text-5xl md:text-6xl font-display text-white mb-6">
          Transforming Communities
        </h1>

        <p class="text-xl text-gray-200 mb-8 leading-relaxed">
          Our projects focus on sustainable development, community empowerment, and lasting social change across Malawi.
        </p>
      </div>
    </BoundingContainer>
  </section>
</template>
