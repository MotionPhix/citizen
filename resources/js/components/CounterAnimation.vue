<script setup lang="ts">
import { ref, onMounted } from 'vue'

interface Props {
  targetValue: number
  duration?: number
}

const props = withDefaults(defineProps<Props>(), {
  duration: 2000
})

const count = ref(0)

onMounted(() => {
  const increment = props.targetValue / (props.duration / 16)

  const updateCount = () => {
    if (count.value < props.targetValue) {
      count.value = Math.ceil(count.value + increment)
      if (count.value > props.targetValue) count.value = props.targetValue
      requestAnimationFrame(updateCount)
    }
  }

  updateCount()
})
</script>

<template>
  <span>{{ count.toLocaleString() }}</span>
</template>
