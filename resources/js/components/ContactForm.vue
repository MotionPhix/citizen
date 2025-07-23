
<script setup lang="ts">
import { ref, onMounted, onUnmounted, watch, computed } from 'vue'
import { useForm, router } from '@inertiajs/vue3'
import { Input } from '@/components/ui/input'
import { Textarea } from '@/components/ui/textarea'
import { Button } from '@/components/ui/button'
import { Label } from '@/components/ui/label'
import { toast } from 'vue-sonner'
import { Send, Loader2, CheckCircle, AlertCircle } from 'lucide-vue-next'
import { useDark, useTextareaAutosize } from '@vueuse/core'

interface HoneypotConfig {
  enabled: boolean
  nameFieldName: string
  validFromFieldName: string
  encryptedValidFrom: string
}

interface Props {
  honeypot: HoneypotConfig,
  message?: string
}

const props = defineProps<Props>()

// Form setup with proper typing
const form = useForm({
  name: '',
  email: '',
  subject: '',
  message: '',
  'h-captcha-response': '',
  [props.honeypot.nameFieldName]: '',
  [props.honeypot.validFromFieldName]: props.honeypot.encryptedValidFrom,
})

// Reactive state
const siteKey = import.meta.env.VITE_HCAPTCHA_SITEKEY
const hcaptchaWidgetId = ref<string | null>(null)
const isDark = useDark()
const isFormValid = ref(false)
const submitAttempted = ref(false)

const { textarea: userMessageTextarea } = useTextareaAutosize()

// Computed properties
const isSubmitDisabled = computed(() => {
  return form.processing || !form['h-captcha-response'] || !isFormValid.value
})

const hasFieldError = (field: string) => {
  return form.errors[field] && submitAttempted.value
}

const getFieldError = (field: string) => {
  return form.errors[field]
}

// Form validation
const validateForm = () => {
  isFormValid.value = !!(
    form.name.trim() &&
    form.email.trim() &&
    form.subject.trim() &&
    form.message.trim()
  )
}

// Watch form changes for validation
watch([() => form.name, () => form.email, () => form.subject, () => form.message], validateForm, { immediate: true })

// Watch dark mode changes to update hCaptcha theme
watch(isDark, (newValue) => {
  if (window.hcaptcha && hcaptchaWidgetId.value) {
    // Reset and re-render with new theme
    try {
      window.hcaptcha.remove(hcaptchaWidgetId.value)
      hcaptchaWidgetId.value = null
      // Use setTimeout to ensure DOM is ready
      setTimeout(() => {
        initHCaptcha()
      }, 100)
    } catch (error) {
      console.warn('Error updating hCaptcha theme:', error)
      // Fallback: try to reinitialize
      hcaptchaWidgetId.value = null
      setTimeout(() => {
        initHCaptcha()
      }, 500)
    }
  }
}, { flush: 'post' })

// Load hCaptcha script with explicit rendering
const loadHCaptchaScript = (): Promise<void> => {
  return new Promise((resolve, reject) => {
    if (window.hcaptcha) {
      resolve()
      return
    }

    const script = document.createElement('script')
    script.src = 'https://js.hcaptcha.com/1/api.js?render=explicit&onload=hCaptchaLoaded'
    script.async = true
    script.defer = true

    // Set up global callback for when hCaptcha is loaded
    window.hCaptchaLoaded = () => {
      delete window.hCaptchaLoaded
      resolve()
    }

    script.onerror = () => {
      delete window.hCaptchaLoaded
      reject(new Error('Failed to load hCaptcha script'))
    }

    document.head.appendChild(script)
  })
}

// hCaptcha callback functions
const onHCaptchaVerify = (token: string) => {
  form['h-captcha-response'] = token
}

const onHCaptchaExpire = () => {
  form['h-captcha-response'] = ''
  toast.warning('Security Check Expired', {
    description: 'Please complete the security verification again.'
  })
}

const onHCaptchaError = (error: string) => {
  form['h-captcha-response'] = ''
  console.error('hCaptcha error:', error)
  toast.error('Security Check Error', {
    description: 'There was an issue with the security verification. Please try again.'
  })
}

// Initialize hCaptcha with proper DOM checking
const initHCaptcha = async () => {
  try {
    // Wait for hCaptcha script to load
    await loadHCaptchaScript()

    // Ensure the container element exists
    const container = document.getElementById('hcaptcha-container')
    if (!container) {
      console.warn('hCaptcha container not found, retrying...')
      // Retry after a short delay
      setTimeout(initHCaptcha, 100)
      return
    }

    // Check if hCaptcha API is available and we have a site key
    if (window.hcaptcha && siteKey) {
      // Remove any existing widget first
      if (hcaptchaWidgetId.value) {
        try {
          window.hcaptcha.remove(hcaptchaWidgetId.value)
        } catch (e) {
          // Ignore errors when removing non-existent widgets
        }
      }

      // Clear the container
      container.innerHTML = ''

      // Render the new widget
      hcaptchaWidgetId.value = window.hcaptcha.render('hcaptcha-container', {
        sitekey: siteKey,
        callback: onHCaptchaVerify,
        'expired-callback': onHCaptchaExpire,
        'error-callback': onHCaptchaError,
        theme: isDark.value ? 'dark' : 'light',
        size: 'normal'
      })

      console.log('hCaptcha widget initialized successfully')
    } else {
      throw new Error('hCaptcha API not available or site key missing')
    }
  } catch (error) {
    console.error('Failed to initialize hCaptcha:', error)
    toast.error('Security Check Loading Error', {
      description: 'Failed to load security verification. Please refresh the page.'
    })
  }
}

// Reset hCaptcha
const resetHCaptcha = () => {
  if (window.hcaptcha && hcaptchaWidgetId.value) {
    try {
      window.hcaptcha.reset(hcaptchaWidgetId.value)
    } catch (error) {
      console.warn('Error resetting hCaptcha:', error)
    }
  }
  form['h-captcha-response'] = ''
}

// Handle form submission with Inertia.js 2.0 best practices
const handleSubmit = () => {
  submitAttempted.value = true

  // Validate required fields
  if (!isFormValid.value) {
    toast.error('Please fill in all required fields', {
      description: 'All fields are required to submit the form.'
    })
    return
  }

  // Check if hCaptcha is completed
  if (!form['h-captcha-response']) {
    toast.error('Security Verification Required', {
      description: 'Please complete the security verification before submitting.'
    })
    return
  }

  // Submit using Inertia.js 2.0 pattern
  form.post(route('contact.submit'), {
    preserveScroll: true,
    onSuccess: (page) => {
      // Handle success response from backend
      const message = page.props.message || 'Thank you for your message! We\'ll get back to you soon.'

      toast.success('Message Sent!', {
        description: message,
        duration: 5000
      })

      // Reset form and captcha
      form.reset()
      resetHCaptcha()
      submitAttempted.value = false
    },
    onError: (errors) => {
      // Handle validation errors
      resetHCaptcha()

      // Show specific error messages
      if (errors.email) {
        toast.error('Invalid Email', { description: errors.email })
      } else if (errors.name) {
        toast.error('Name Required', { description: errors.name })
      } else if (errors.subject) {
        toast.error('Subject Required', { description: errors.subject })
      } else if (errors.message) {
        toast.error('Message Required', { description: errors.message })
      } else if (errors['h-captcha-response']) {
        toast.error('Security Verification Failed', { description: errors['h-captcha-response'] })
      } else {
        toast.error('Submission Failed', {
          description: 'Please check your information and try again.'
        })
      }
    },
    onFinish: () => {
      // Always reset captcha on finish
      if (form.hasErrors) {
        resetHCaptcha()
      }
    }
  })
}

// Lifecycle hooks
onMounted(() => {
  if (siteKey) {
    initHCaptcha()
  } else {
    console.warn('hCaptcha site key not found. Please set VITE_HCAPTCHA_SITEKEY in your environment variables.')
  }
})

onUnmounted(() => {
  if (window.hcaptcha && hcaptchaWidgetId.value) {
    try {
      window.hcaptcha.remove(hcaptchaWidgetId.value)
    } catch (error) {
      console.warn('Error removing hCaptcha widget:', error)
    }
  }
})

// Type declaration for hCaptcha
declare global {
  interface Window {
    hcaptcha: {
      render: (container: string, params: any) => string
      reset: (widgetId?: string) => void
      remove: (widgetId: string) => void
      execute: (widgetId?: string) => void
      getResponse: (widgetId?: string) => string
    }
    hCaptchaLoaded?: () => void
  }
}
</script>

<template>
  <form @submit.prevent="handleSubmit" class="space-y-6">
    <!-- Honeypot fields (hidden from users) -->
    <div v-if="honeypot.enabled" style="display:none;" aria-hidden="true">
      <input
        type="text"
        :name="honeypot.nameFieldName"
        :id="honeypot.nameFieldName"
        v-model="form[honeypot.nameFieldName]"
        tabindex="-1"
        autocomplete="off"
      />
      <input
        type="text"
        :name="honeypot.validFromFieldName"
        v-model="form[honeypot.validFromFieldName]"
        tabindex="-1"
        autocomplete="off"
      />
    </div>

    <!-- Name and Email Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <!-- Full Name Field -->
      <div class="space-y-2">
        <Label for="contact-name" class="text-sm font-medium text-gray-900 dark:text-white">
          Full Name <span class="text-red-500">*</span>
        </Label>
        <Input
          id="contact-name"
          v-model="form.name"
          type="text"
          class="h-12 transition-all duration-200"
          placeholder="Enter your full name"
          required
          autocomplete="name"
          :class="{
            'border-red-500 focus:border-red-500 focus:ring-red-500/20': hasFieldError('name'),
            'border-green-500 focus:border-green-500 focus:ring-green-500/20': form.name && !hasFieldError('name') && submitAttempted
          }"
        />
        <div v-if="hasFieldError('name')" class="flex items-center text-sm text-red-600 dark:text-red-400">
          <AlertCircle class="w-4 h-4 mr-1" />
          {{ getFieldError('name') }}
        </div>
      </div>

      <!-- Email Field -->
      <div class="space-y-2">
        <Label for="contact-email" class="text-sm font-medium text-gray-900 dark:text-white">
          Email Address <span class="text-red-500">*</span>
        </Label>
        <Input
          id="contact-email"
          v-model="form.email"
          type="email"
          class="h-12 transition-all duration-200"
          placeholder="Enter your email address"
          required
          autocomplete="email"
          :class="{
            'border-red-500 focus:border-red-500 focus:ring-red-500/20': hasFieldError('email'),
            'border-green-500 focus:border-green-500 focus:ring-green-500/20': form.email && !hasFieldError('email') && submitAttempted
          }"
        />
        <div v-if="hasFieldError('email')" class="flex items-center text-sm text-red-600 dark:text-red-400">
          <AlertCircle class="w-4 h-4 mr-1" />
          {{ getFieldError('email') }}
        </div>
      </div>
    </div>

    <!-- Subject Field -->
    <div class="space-y-2">
      <Label for="contact-subject" class="text-sm font-medium text-gray-900 dark:text-white">
        Subject <span class="text-red-500">*</span>
      </Label>
      <Input
        id="contact-subject"
        v-model="form.subject"
        type="text"
        class="h-12 transition-all duration-200"
        placeholder="What is this regarding?"
        required
        :class="{
          'border-red-500 focus:border-red-500 focus:ring-red-500/20': hasFieldError('subject'),
          'border-green-500 focus:border-green-500 focus:ring-green-500/20': form.subject && !hasFieldError('subject') && submitAttempted
        }"
      />
      <div v-if="hasFieldError('subject')" class="flex items-center text-sm text-red-600 dark:text-red-400">
        <AlertCircle class="w-4 h-4 mr-1" />
        {{ getFieldError('subject') }}
      </div>
    </div>

    <!-- Message Field -->
    <div class="space-y-2">
      <Label for="contact-message" class="text-sm font-medium text-gray-900 dark:text-white">
        Message <span class="text-red-500">*</span>
      </Label>
      <Textarea
        ref="userMessageTextarea"
        id="contact-message"
        v-model="form.message"
        rows="6"
        placeholder="Please provide details about your inquiry, partnership opportunity, or how we can help you..."
        required
        class="resize-none transition-all duration-200 min-h-[120px]"
        :class="{
          'border-red-500 focus:border-red-500 focus:ring-red-500/20': hasFieldError('message'),
          'border-green-500 focus:border-green-500 focus:ring-green-500/20': form.message && !hasFieldError('message') && submitAttempted
        }"
      />
      <div class="flex justify-between items-start">
        <div v-if="hasFieldError('message')" class="flex items-center text-sm text-red-600 dark:text-red-400">
          <AlertCircle class="w-4 h-4 mr-1" />
          {{ getFieldError('message') }}
        </div>
        <div class="text-xs text-gray-500 dark:text-gray-400 ml-auto">
          {{ form.message.length }}/1000 characters
        </div>
      </div>
    </div>

    <!-- Security Verification (hCaptcha) -->
    <div class="space-y-3">
      <Label class="text-sm font-medium text-gray-900 dark:text-white">
        Security Verification <span class="text-red-500">*</span>
      </Label>
      <div class="flex justify-center">
        <div id="hcaptcha-container" class="transform scale-90 sm:scale-100 origin-center"></div>
      </div>
      <div v-if="hasFieldError('h-captcha-response')" class="flex items-center justify-center text-sm text-red-600 dark:text-red-400">
        <AlertCircle class="w-4 h-4 mr-1" />
        {{ getFieldError('h-captcha-response') }}
      </div>
    </div>

    <!-- Form Status Indicator -->
    <div v-if="submitAttempted && !isFormValid" class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg p-4">
      <div class="flex items-center text-amber-800 dark:text-amber-200">
        <AlertCircle class="w-5 h-5 mr-2" />
        <span class="text-sm font-medium">Please complete all required fields before submitting.</span>
      </div>
    </div>

    <!-- Submit Button -->
    <div class="pt-4">
      <Button
        type="submit"
        :disabled="isSubmitDisabled"
        class="w-full h-14 px-8 py-4 bg-ca-primary hover:bg-ca-primary/90 dark:bg-ca-highlight dark:hover:bg-ca-highlight/90 text-white font-semibold rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:shadow-lg"
      >
        <div class="flex items-center justify-center">
          <Loader2 v-if="form.processing" class="w-5 h-5 mr-2 animate-spin" />
          <Send v-else class="w-5 h-5 mr-2" />
          <span>
            {{ form.processing ? 'Sending Message...' : 'Send Message' }}
          </span>
        </div>
      </Button>

      <!-- Form Help Text -->
      <p class="mt-3 text-xs text-gray-500 dark:text-gray-400 text-center">
        We typically respond within 24 hours. Your information is kept confidential and secure.
      </p>
    </div>
  </form>
</template>
