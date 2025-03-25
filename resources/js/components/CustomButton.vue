<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {useDark} from '@vueuse/core';

defineProps<{
  isLink: boolean
  href?: string
}>()

const isDark = useDark()
</script>

<template>
  <Button
    size="lg"
    :variant="isDark ? 'secondary': 'outline'"
    as-child
    v-if="isLink">
    <a
      :href="route(href)">
      <slot />
    </a>
  </Button>

  <a :href="href" v-else>
    <slot />
  </a>
</template>

<style scoped>

</style>
