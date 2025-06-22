<script setup lang="ts">
import { ArrowRight } from 'lucide-vue-next';
import { Badge } from '@/components/ui/badge';
import { computed } from 'vue';

interface MediaItem {
  collection_name: string;
  preview_url: string;
  original_url: string;
}

interface Tag {
  id: number;
  name: {
    en: string
  };
}

interface Project {
  id: number;
  title: string;
  slug: string;
  description: string;
  status: 'current' | 'completed' | 'upcoming';
  start_date: string;
  funded_by: string;
  people_reached: number;
  budget: string;
  media: MediaItem[];
  tags: Tag[];
}

const props = defineProps<{
  project: Project
}>();

// Get the main project image (first image from project_image collection)
const projectImage = computed(() => {
  return props.project.media.find(
    media => media.collection_name === 'project_image'
  )?.preview_url;
});

// Format the status text
const statusLabel = computed(() => {
  return props.project.status.charAt(0).toUpperCase() + props.project.status.slice(1);
});

// Format numbers with commas
const formatNumber = (num: number) => {
  return new Intl.NumberFormat().format(num);
};

// Format currency
const formatCurrency = (amount: string) => {
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
    minimumFractionDigits: 0,
    maximumFractionDigits: 0
  }).format(Number(amount));
};

// Get status badge variant
const statusVariant = computed(() => {
  switch (props.project.status) {
    case 'current':
      return 'success';
    case 'completed':
      return 'secondary';
    case 'upcoming':
      return 'warning';
    default:
      return 'secondary';
  }
});
</script>

<template>
  <a
    :href="`/projects/s/${project.uuid}`"
    class="group block"
  >
    <div class="relative aspect-w-16 aspect-h-12 overflow-hidden bg-gray-100 rounded-2xl dark:bg-neutral-800">
      <img
        :src="projectImage"
        :alt="project.title"
        class="group-hover:scale-105 transition-transform duration-500 ease-in-out object-cover rounded-2xl"
      >

      <span>
      <Badge :variant="statusVariant" class="absolute top-0 -right-1 rounded-bl-lg rounded-t-none rounded-br-none">
        {{ statusLabel }}
      </Badge>
      </span>
    </div>

    <div class="pt-4 space-y-3">
      <h3
        class="relative font-display inline-block font-medium prose-xl before:absolute before:bottom-0.5 before:start-0 before:-z-[1] before:w-full before:h-1 before:bg-blue-400 before:transition before:origin-left before:scale-x-0 group-hover:before:scale-x-100 dark:text-white">
        {{ project.title }}
      </h3>

      <div class="text-gray-600 dark:text-neutral-400 prose line-clamp-2">
        {{ project.description }}
      </div>

<!--      <div class="flex items-center gap-4 text-sm text-gray-500 dark:text-neutral-400">-->
<!--        <div class="flex items-center gap-1">-->
<!--          <span class="font-medium">{{ formatNumber(project.people_reached) }}</span>-->
<!--          <span>people reached</span>-->
<!--        </div>-->
<!--        -->
<!--        <div class="flex items-center gap-1">-->
<!--          <span class="font-medium">{{ formatCurrency(project.budget) }}</span>-->
<!--          <span>invested</span>-->
<!--        </div>-->
<!--      </div>-->

<!--      <div class="flex flex-wrap gap-2">-->
<!--        <Badge-->
<!--          v-for="tag in project.tags"-->
<!--          :key="tag.id"-->
<!--          variant="outline"-->
<!--          class="dark:border-neutral-700 dark:text-neutral-400"-->
<!--        >-->
<!--          {{ tag.name.en }}-->
<!--        </Badge>-->
<!--      </div>-->

      <div
        class="flex items-center text-ca-primary dark:text-ca-highlight group-hover:text-ca-highlight dark:group-hover:text-white transition-colors duration-300">
        <span>Get Insights</span>
        <ArrowRight class="w-4 h-4 ml-1" />
      </div>
    </div>
  </a>
</template>
