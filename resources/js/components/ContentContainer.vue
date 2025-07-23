<script setup lang="ts">
interface Props {
  class?: string
}

withDefaults(defineProps<Props>(), {
  class: ''
})
</script>

<template>
  <section :class="`max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 ${$props.class}`">
    <slot />
  </section>
</template>
