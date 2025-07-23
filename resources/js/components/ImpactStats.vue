<script setup lang="ts">
import ImpactCard from '@/components/ImpactCard.vue'

interface Metric {
  id: number
  title: string
  value: string
  description: string
  icon: string
  is_published: boolean
  sort_order: number
}

interface Props {
  metrics: Metric[]
}

defineProps<Props>()
</script>

<template>
  <section class="py-16 px-4">
    <div class="max-w-7xl mx-auto">
      <h2 class="text-4xl md:text-5xl md:text-4xl font-bold text-center font-display mb-12 text-ca-primary dark:text-ca-highlight">
        Our Impact in Numbers
      </h2>

      <!-- Impact Stats Grid -->
      <div class="grid md:grid-cols-3 gap-8 mb-16">
        <ImpactCard
          v-for="metric in metrics"
          :key="metric.id"
          :metric="metric"
        />
      </div>
    </div>
  </section>
</template>
