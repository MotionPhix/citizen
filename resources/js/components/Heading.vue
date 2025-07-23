<script setup lang="ts">
interface Props {
  title: string
  description?: string
  level?: 1 | 2 | 3 | 4 | 5 | 6
  class?: string
}

const props = withDefaults(defineProps<Props>(), {
  level: 1,
  class: ''
})

const headingTag = `h${props.level}` as const
</script>

<template>
  <div :class="['space-y-2', props.class]">
    <component
      :is="headingTag"
      :class="[
        'font-semibold tracking-tight',
        {
          'text-3xl': level === 1,
          'text-2xl': level === 2,
          'text-xl': level === 3,
          'text-lg': level === 4,
          'text-base': level === 5,
          'text-sm': level === 6,
        }
      ]"
    >
      {{ title }}
    </component>

    <p v-if="description" class="text-sm text-muted-foreground">
      {{ description }}
    </p>
  </div>
</template>
