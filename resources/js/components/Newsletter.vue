<script setup lang="ts">
import { computed } from 'vue'
import { Loader2, Mail, Send } from 'lucide-vue-next'
import { toast } from 'vue-sonner'
import { useForm } from '@inertiajs/vue3'

interface Props {
  compact?: boolean
  showName?: boolean
  variant?: 'default' | 'footer' | 'inline'
}

const props = withDefaults(defineProps<Props>(), {
  compact: false,
  showName: true,
  variant: 'default'
})

const form = useForm<{ email: string, name?: string }>({
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

// Computed classes based on variant
const containerClasses = computed(() => {
  switch (props.variant) {
    case 'footer':
      return 'space-y-3'
    case 'inline':
      return 'flex flex-col sm:flex-row gap-3'
    default:
      return 'flex flex-col md:flex-row gap-4'
  }
})

const inputClasses = computed(() => {
  const baseClasses = 'px-4 py-3 rounded-xl border transition-all duration-300 focus:outline-none focus:ring-2'

  switch (props.variant) {
    case 'footer':
      return `${baseClasses} w-full text-sm bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-ca-primary/50 dark:focus:ring-ca-highlight/50 focus:border-ca-primary dark:focus:border-ca-highlight`
    case 'inline':
      return `${baseClasses} flex-1 bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-ca-primary/50 dark:focus:ring-ca-highlight/50 focus:border-ca-primary dark:focus:border-ca-highlight`
    default:
      return `${baseClasses} flex-1 bg-white/90 dark:bg-gray-900/90 border-white/20 dark:border-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-white/30 dark:focus:ring-gray-700/50 focus:border-transparent`
  }
})

const buttonClasses = computed(() => {
  const baseClasses = 'px-6 py-3 rounded-xl font-semibold transition-all duration-300 flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed'

  switch (props.variant) {
    case 'footer':
      return `${baseClasses} w-full bg-ca-primary hover:bg-ca-primary/90 dark:bg-ca-highlight dark:hover:bg-ca-highlight/90 text-white shadow-sm hover:shadow-md`
    case 'inline':
      return `${baseClasses} bg-ca-primary hover:bg-ca-primary/90 dark:bg-ca-highlight dark:hover:bg-ca-highlight/90 text-white shadow-sm hover:shadow-md`
    default:
      return `${baseClasses} md:w-auto bg-white hover:bg-gray-100 text-ca-primary hover:text-ca-primary/90 dark:bg-gray-900 dark:hover:bg-gray-800 dark:text-white`
  }
})
</script>

<template>
  <form @submit.prevent="handleSubmit" :class="containerClasses">
    <!-- Name Input (conditional) -->
    <input
      v-if="showName && !compact"
      v-model="form.name"
      type="text"
      :placeholder="variant === 'footer' ? 'Full name' : 'Enter your full name'"
      :class="inputClasses"
    />

    <!-- Email Input -->
    <input
      v-model="form.email"
      type="email"
      required
      :placeholder="variant === 'footer' ? 'Email address' : 'Enter your email address'"
      :class="inputClasses"
    />

    <!-- Submit Button -->
    <button
      type="submit"
      :disabled="form.busy"
      :class="buttonClasses"
    >
      <Loader2
        v-if="form.busy"
        class="w-4 h-4 mr-2 animate-spin"
      />
      <template v-else>
        <Mail v-if="variant === 'default'" class="w-4 h-4 mr-2" />
        <Send v-else class="w-4 h-4 mr-2" />
      </template>

      <span class="hidden sm:inline">
        {{ form.busy ? 'Subscribing...' : (compact ? 'Subscribe' : 'Subscribe Now') }}
      </span>
      <span class="sm:hidden">
        {{ form.busy ? 'Subscribing...' : 'Subscribe' }}
      </span>
    </button>
  </form>

  <!-- Success Message (for footer variant) -->
  <div v-if="variant === 'footer'" class="mt-3">
    <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
      By subscribing, you agree to receive our newsletter. You can unsubscribe at any time.
    </p>
  </div>
</template>
