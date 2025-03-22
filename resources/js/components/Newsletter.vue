<script setup lang="ts">
import { ref } from 'vue'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Loader2 } from 'lucide-vue-next'
import { toast } from 'vue-sonner';

const email = ref('')
const loading = ref(false)

const handleSubmit = async () => {
  loading.value = true

  try {
    // Add your newsletter subscription logic here
    await new Promise(resolve => setTimeout(resolve, 1000))

    toast.success({
      description: 'Thank you for subscribing to our newsletter!'
    })

    email.value = ''
  } catch (err) {
    console.log(err);

    toast.error({
      description: 'Failed to subscribe to newsletter. Please try again.',
    })
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <section class="py-16 bg-gray-50 dark:bg-ca-secondary">
    <div class="container mx-auto px-4">
      <div class="max-w-xl mx-auto text-center">
        <h2 class="text-3xl font-bold mb-4 font-display">Stay Updated</h2>
        <p class="text-gray-600 dark:text-gray-300 mb-8">
          Subscribe to our newsletter to get all our news in your inbox.
        </p>

        <form @submit.prevent="handleSubmit" class="relative">
          <div class="flex flex-col md:flex-row">
            <Input
              v-model="email"
              type="email"
              required
              placeholder="Enter your email"
              class="w-full md:rounded-e-none"
            />
            <Button
              type="submit"
              :disabled="loading"
              class="mt-2 md:mt-0 md:rounded-s-none bg-ca-highlight hover:bg-ca-primary"
            >
              <Loader2
                v-if="loading"
                class="mr-2 h-4 w-4 animate-spin"
              />
              {{ loading ? 'Processing...' : 'Subscribe' }}
            </Button>
          </div>
        </form>
      </div>
    </div>
  </section>
</template>
