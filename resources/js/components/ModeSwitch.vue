<script setup lang="ts">
import { computed } from 'vue'
import { useDark, useToggle } from '@vueuse/core'
import { Button } from '@/components/ui/button'
import { Sun, Moon } from 'lucide-vue-next'

const isDark = useDark()
const toggleDark = useToggle(isDark)

const icon = computed(() => isDark.value ? Sun : Moon)
const label = computed(() => isDark.value ? 'Switch to light mode' : 'Switch to dark mode')
</script>

<template>
  <Button
    variant="ghost"
    size="sm"
    @click="toggleDark()"
    :aria-label="label"
    class="h-9 w-9 p-0">
    <component :is="icon" class="h-4 w-4" />
  </Button>
</template>
