<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {Link} from "@inertiajs/vue3"
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
    as-child
    v-if="isLink">
    <Link :variant="isDark ? 'secondary': 'outline'" :href="href">
      <slot />
    </Link>
  </Button>

  <Button size="lg" v-else>
    <slot />
  </Button>
</template>

<style scoped>

</style>
