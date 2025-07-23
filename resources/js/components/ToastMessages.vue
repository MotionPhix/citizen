<script setup lang="ts">
import { onMounted } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { toast } from 'vue-sonner'

const page = usePage()

onMounted(() => {
  // Handle flash messages from the server
  const flash = page.props.flash as Record<string, any> | undefined

  if (flash?.success) {
    toast.success(flash.success)
  }

  if (flash?.error) {
    toast.error(flash.error)
  }

  if (flash?.warning) {
    toast.warning(flash.warning)
  }

  if (flash?.info) {
    toast.info(flash.info)
  }

  // Handle validation errors
  const errors = page.props.errors as Record<string, string[]> | undefined
  if (errors && Object.keys(errors).length > 0) {
    const firstError = Object.values(errors)[0]
    if (Array.isArray(firstError) && firstError.length > 0) {
      toast.error(firstError[0])
    }
  }
})
</script>

<template>
  <!-- This component doesn't render anything visible -->
  <!-- It just handles displaying toast messages from server responses -->
</template>
