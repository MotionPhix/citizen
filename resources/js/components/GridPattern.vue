<script setup lang="ts">
interface Props {
  size?: string | number
  class?: string
}

const props = withDefaults(defineProps<Props>(), {
  size: '40',
  class: ''
})
</script>

<template>
  <div
    :class="props.class"
    :style="{
      backgroundSize: `${props.size}px ${props.size}px`,
      backgroundImage: `linear-gradient(to right, currentColor 1px, transparent 1px),
                       linear-gradient(to bottom, currentColor 1px, transparent 1px)`
    }"
  />
</template>
