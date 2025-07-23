<script setup lang="ts">
interface Props {
  background?: 'white' | 'gray' | 'gradient' | 'primary' | 'transparent'
  padding?: 'sm' | 'md' | 'lg' | 'xl'
  maxWidth?: '2xl' | '3xl' | '4xl' | '5xl' | '6xl' | '7xl' | 'full'
  class?: string
}

const props = withDefaults(defineProps<Props>(), {
  background: 'white',
  padding: 'lg',
  maxWidth: '4xl',
  class: ''
})

const backgroundClasses = {
  white: 'bg-white dark:bg-gray-900',
  gray: 'bg-gray-50 dark:bg-gray-800',
  gradient: 'bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800',
  primary: 'bg-ca-primary dark:bg-ca-primary',
  transparent: 'bg-transparent'
}

const paddingClasses = {
  sm: 'py-8',
  md: 'py-12',
  lg: 'py-16',
  xl: 'py-20'
}

const maxWidthClasses = {
  '2xl': 'max-w-2xl',
  '3xl': 'max-w-3xl',
  '4xl': 'max-w-4xl',
  '5xl': 'max-w-5xl',
  '6xl': 'max-w-6xl',
  '7xl': 'max-w-7xl',
  'full': 'max-w-full'
}
</script>

<template>
  <section :class="[backgroundClasses[background], paddingClasses[padding], props.class]">
    <div :class="[maxWidthClasses[maxWidth], 'mx-auto px-4 sm:px-6 lg:px-8']">
      <slot />
    </div>
  </section>
</template>
