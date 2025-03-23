<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Loader2 } from 'lucide-vue-next'
import { toast } from 'vue-sonner'

const form = useForm({
  email: ''
})

const handleSubmit = async () => {
  form.post('/newsletter', {
    preserveScroll: true,
    onSuccess: () => {
      toast.success({
        description: 'Thank you for subscribing to our newsletter!'
      })
      form.reset()
    },
    onError: () => {
      toast.error({
        description: 'Failed to subscribe to newsletter. Please try again.'
      })
    }
  })
}
</script>

<template>
  <section class="bg-white dark:bg-gray-950 bg-mint-500">
    <div class="container px-4 py-16 mx-auto">
      <div class="max-w-xl mx-auto text-center">
        <h2 class="mb-4 text-3xl font-bold tracking-tight text-gray-900 dark:text-gray-100 font-display">
          Stay Updated
        </h2>
        <p class="mb-8 font-display text-base leading-relaxed text-gray-600 dark:text-gray-400">
          Subscribe to our newsletter to get all our news in your inbox.
        </p>

        <form @submit.prevent="handleSubmit" class="relative">
          <div class="flex flex-col sm:flex-row">
            <Input
              v-model="form.email"
              type="email"
              required
              placeholder="Enter your email"
              :disabled="form.processing"
              class="flex-1 sm:rounded-e-none h-11"
              :class="{
                'border-red-500 dark:border-red-500 focus:ring-red-500 dark:focus:ring-red-500': form.errors.email
              }"
            />

            <Button
              size="lg"
              type="submit"
              :disabled="form.processing"
              class="sm:rounded-s-none bg-gray-900 dark:bg-gray-100 text-white dark:text-gray-900 hover:bg-gray-800 dark:hover:bg-gray-200"
            >
              <Loader2
                v-if="form.processing"
                class="w-4 h-4 mr-2 animate-spin"
              />
              {{ form.processing ? 'Processing...' : 'Subscribe' }}
            </Button>
          </div>

          <!-- Error Message -->
          <p
            v-if="form.errors.email"
            class="absolute mt-1 text-sm text-red-500 dark:text-red-400"
          >
            {{ form.errors.email }}
          </p>
        </form>
      </div>
    </div>
  </section>
</template>
