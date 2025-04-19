<script setup lang="ts">
import Form from 'vform'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Loader2 } from 'lucide-vue-next'
import { toast } from 'vue-sonner'
import { ref } from 'vue';

interface Props {
  compact?: boolean
}

defineProps<Props>()

const form = new Form({
  email: '',
  name: ''
})

const isLoading = ref(false)

const handleSubmit = async () => {
  try {
    isLoading.value = true;

    await form.post(route('newsletter.subscribe'))

    toast({
      title: 'Success!',
      description: 'Thank you for subscribing to our newsletter!',
    })

    form.reset()
  } catch (err) {
    toast({
      title: 'Error!',
      description: 'Failed to subscribe to newsletter. Please try again.',
      variant: 'destructive'
    })
  }

}
</script>

<template>
  <section :class="{ 'bg-white dark:bg-gray-950 bg-mint-500': !compact }">
    <div :class="{ 'container px-4 py-16 mx-auto': !compact }">
      <div :class="{ 'max-w-xl mx-auto text-center': !compact }">
        <template v-if="!compact">
          <h2 class="mb-4 text-3xl font-bold tracking-tight text-gray-900 dark:text-gray-100 font-display">
            Stay Updated
          </h2>
          <p class="mb-8 font-display text-base leading-relaxed text-gray-600 dark:text-gray-400">
            Subscribe to our newsletter to get all our news in your inbox.
          </p>
        </template>

        <form @submit.prevent="handleSubmit" class="space-y-3">
          <!-- Regular layout for non-compact mode -->
          <div v-if="!compact" class="flex flex-col sm:flex-row gap-4">
            <Input
              v-model="form.name"
              type="text"
              placeholder="Your Name (Optional)"
              :disabled="form.startProcessing"
              class="flex-1"
              :class="{
                'border-red-500 dark:border-red-500 focus:ring-red-500 dark:focus:ring-red-500': form.errors.name
              }"
            />

            <Input
              v-model="form.email"
              type="email"
              required
              placeholder="Your Email"
              :disabled="form.startProcessing"
              class="flex-1"
              :class="{
                'border-red-500 dark:border-red-500 focus:ring-red-500 dark:focus:ring-red-500': form.errors.email
              }"
            />

            <Button
              size="lg"
              type="submit"
              :disabled="form.startProcessing"
              class="w-full sm:w-auto bg-gray-900 dark:bg-gray-100 text-white dark:text-gray-900 hover:bg-gray-800 dark:hover:bg-gray-200"
            >
              <Loader2
                v-if="form.startProcessing"
                class="w-4 h-4 mr-2 animate-spin"
              />
              {{ form.startProcessing ? 'Processing...' : 'Subscribe' }}
            </Button>
          </div>

          <!-- Compact layout for footer -->
          <div v-else class="space-y-3">
            <Input
              v-model="form.email"
              type="email"
              required
              placeholder="Your Email"
              :disabled="form.startProcessing"
              class="w-full"
              :class="{
                'border-red-500 dark:border-red-500 focus:ring-red-500 dark:focus:ring-red-500': form.errors.email
              }"
            />

            <Button
              size="lg"
              type="submit"
              :disabled="form.startProcessing"
              class="w-full bg-gray-900 dark:bg-gray-100 text-white dark:text-gray-900 hover:bg-gray-800 dark:hover:bg-gray-200"
            >
              <Loader2
                v-if="form.processing"
                class="w-4 h-4 mr-2 animate-spin"
              />
              {{ form.startProcessing ? 'Processing...' : 'Subscribe' }}
            </Button>
          </div>

          <!-- Error Messages -->
          <div
            v-if="form.errors.email || form.errors.name"
            class="space-y-1 text-left"
          >
            <p
              v-if="form.errors.name"
              class="text-sm text-red-500 dark:text-red-400">
              {{ form.errors.get('name') }}
            </p>
            <p
              v-if="form.errors.email"
              class="text-sm text-red-500 dark:text-red-400"
            >
              {{ form.errors.get('email') }}
            </p>
          </div>
        </form>
      </div>
    </div>
  </section>
</template>
