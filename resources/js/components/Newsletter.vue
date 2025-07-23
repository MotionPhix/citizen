<script setup lang="ts">
import { ref } from 'vue'
import { Loader2, Mail } from 'lucide-vue-next'
import { toast } from 'vue-sonner'
import Form from 'vform'

const form = new Form<{ email: string, name?: string }>({
  email: '',
  name: ''
})

const handleSubmit = async () => {
  try {
    await form.post(route('newsletter.subscribe'))

    toast.success('Thank you for subscribing!', {
      description: 'You will receive our next newsletter in your inbox.'
    })

    form.reset()
  } catch (e) {
    console.log(e)

    toast.error('Subscription failed', {
      description: 'Please try again or contact support if the problem persists.'
    })
  }
}
</script>

<template>
  <form @submit.prevent="handleSubmit" class="flex flex-col md:flex-row gap-4">
    <input
      v-model="form.name"
      type="text"
      placeholder="Enter your full name"
      class="flex-1 h-12 px-4 bg-white/90 dark:bg-gray-900/90 border border-white/20 dark:border-gray-700 rounded-lg text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-white/30 dark:focus:ring-gray-700/50 focus:border-transparent"
    />

    <input
      v-model="form.email"
      type="email"
      required
      placeholder="Enter your email address"
      class="flex-1 h-12 px-4 bg-white/90 dark:bg-gray-900/90 border border-white/20 dark:border-gray-700 rounded-lg text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-white/30 dark:focus:ring-gray-700/50 focus:border-transparent"
    />

    <button
      type="submit"
      :disabled="form.busy"
      class="md:w-auto h-12 px-8 bg-white hover:bg-gray-100 text-ca-primary hover:text-ca-primary/90 dark:bg-gray-900 dark:hover:bg-gray-800 dark:text-white font-semibold rounded-lg transition-colors duration-300 flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed"
    >
      <Loader2
        v-if="form.busy"
        class="w-4 h-4 mr-2 animate-spin"
      />
      <Mail
        v-else
        class="w-4 h-4 mr-2"
      />
      {{ form.busy ? 'Subscribing...' : 'Subscribe' }}
    </button>
  </form>
</template>
