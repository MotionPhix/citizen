<script setup lang="ts">
import { ArrowRight, Heart } from 'lucide-vue-next'
import GridPattern from '@/components/GridPattern.vue'
import { Button } from '@/components/ui/button'
import BoundingContainer from '@/components/BoundingContainer.vue'
</script>

<template>
  <section class="relative py-20 bg-gradient-to-br from-ca-primary to-ca-highlight overflow-hidden">
    <GridPattern
      class="absolute inset-0 text-white/[0.1]"
      size="60"
    />

    <BoundingContainer class="relative">
      <div
        class="max-w-3xl mx-auto text-center"
        v-motion
        :initial="{ opacity: 0, y: 20 }"
        :enter="{ opacity: 1, y: 0 }">
        <h2 class="text-4xl font-display text-white mb-6">
          Ready to Make an Impact?
        </h2>
        <p class="text-xl text-gray-200 mb-8">
          Join us in our mission to create lasting positive change in communities across Malawi.
        </p>
        <div class="flex flex-wrap justify-center gap-4">
          <Button
            variant="secondary"
            size="lg"
            @click="() => window.location.href = route('contact.index')"
          >
            Partner With Us
            <ArrowRight class="w-5 h-5 ml-2" />
          </Button>

          <Button
            variant="outline"
            size="lg"
            class="border-white text-white hover:bg-white/10"
            @click="() => window.location.href = route('donation.form')"
          >
            <Heart class="w-5 h-5 mr-2" />
            Support Our Work
          </Button>
        </div>
      </div>
    </BoundingContainer>
  </section>
</template>
