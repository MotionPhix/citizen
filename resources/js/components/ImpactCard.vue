<script setup lang="ts">
import {
  Users,
  GraduationCap,
  Scale,
  HandHeart,
  Landmark,
  Venus,
  BarChart,
  Handshake,
} from 'lucide-vue-next';
import CounterAnimation from '@/components/CounterAnimation.vue'

interface Props {
  metric: {
    icon: string
    metric: string | number
    title: string
    description: string
  }
}

const props = defineProps<Props>()

const getIcon = (iconName: string) => {
  const icons = {
    users: Users,
    handshake: Handshake,
    medical: Scale,
    training: GraduationCap,
    women: Venus,
    volunteers: HandHeart,
    water: Landmark
  }

  return icons[iconName as keyof typeof icons] || BarChart
}
</script>

<template>
  <div class="impact-card group p-6 rounded-xl bg-gray-50 dark:bg-ca-secondary transition-all duration-300 hover:bg-ca-highlight/10 dark:hover:bg-ca-highlight/20 hover:shadow-xl">
    <div class="w-12 h-12 mb-4 text-ca-primary dark:text-ca-highlight transform group-hover:scale-110 transition-transform duration-300">
      <component
        :is="getIcon(metric.icon)"
        class="w-full h-full"
      />
    </div>

    <div class="text-4xl font-bold mb-2 text-ca-primary dark:text-white">
      <counter-animation
        :target-value="parseInt(String(metric.metric).replace(/,/g, ''))"
      />+
    </div>

    <h3 class="text-xl font-semibold mb-2 text-gray-800 dark:text-gray-200">
      {{ metric.title }}
    </h3>

    <p class="text-gray-600 dark:text-gray-300">
      {{ metric.description }}
    </p>
  </div>
</template>
