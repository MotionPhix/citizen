<script setup lang="ts">
import { cn } from '@/lib/utils'
import { Check } from 'lucide-vue-next'

interface Props {
  class?: string
  checked?: boolean
  disabled?: boolean
  required?: boolean
  id?: string
}

const props = defineProps<Props>()

const emit = defineEmits<{
  'update:checked': [value: boolean]
}>()

const handleChange = (event: Event) => {
  const target = event.target as HTMLInputElement
  emit('update:checked', target.checked)
}
</script>

<template>
  <div class="relative">
    <input
      :id="id"
      type="checkbox"
      :checked="checked"
      :disabled="disabled"
      :required="required"
      :class="cn(
        'peer h-4 w-4 shrink-0 rounded-sm border border-primary ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 data-[state=checked]:bg-primary data-[state=checked]:text-primary-foreground',
        'appearance-none bg-background',
        props.class
      )"
      @change="handleChange"
    />
    <Check
      v-if="checked"
      class="absolute inset-0 h-4 w-4 text-primary-foreground pointer-events-none"
    />
  </div>
</template>

<style scoped>
input[type="checkbox"]:checked {
  background-color: hsl(var(--primary));
  border-color: hsl(var(--primary));
}
</style>
