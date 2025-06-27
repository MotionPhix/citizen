
<script setup lang="ts">
import { ref, defineProps, onMounted, onUnmounted } from 'vue';
import { Form } from 'vform'
import { Input } from '@/components/ui/input'
import { Textarea } from '@/components/ui/textarea'
import { Button } from '@/components/ui/button'
import { Label } from '@/components/ui/label'
import { toast } from 'vue-sonner'
import { LucideAirplay } from 'lucide-vue-next'
import { useDark, useTextareaAutosize } from '@vueuse/core';

const props = defineProps<{
  honeypot: {
    enabled: boolean
    nameFieldName: string
    validFromFieldName: string
    encryptedValidFrom: string
  }
}>()

const form = new Form({
  name: '',
  email: '',
  subject: '',
  message: '',
  'h-captcha-response': '',
  [props.honeypot.nameFieldName]: '',
  [props.honeypot.validFromFieldName]: props.honeypot.encryptedValidFrom,
})

// Use hCaptcha site key instead of Google reCAPTCHA
const siteKey = import.meta.env.VITE_HCAPTCHA_SITEKEY
const isSubmitting = ref(false)
const hcaptchaWidgetId = ref<string | null>(null)
const isDark = useDark()

const { textarea: userMessageTextarea } = useTextareaAutosize()

// Load hCaptcha script
const loadHCaptchaScript = (): Promise<void> => {
  return new Promise((resolve, reject) => {
    if (window.hcaptcha) {
      resolve()
      return
    }

    const script = document.createElement('script')
    script.src = 'https://js.hcaptcha.com/1/api.js'
    script.async = true
    script.defer = true

    script.onload = () => resolve()
    script.onerror = () => reject(new Error('Failed to load hCaptcha script'))

    document.head.appendChild(script)
  })
}

// hCaptcha callback functions
const onHCaptchaVerify = (token: string) => {
  form['h-captcha-response'] = token
}

const onHCaptchaExpire = () => {
  form['h-captcha-response'] = ''
  toast.warning('Captcha Expired', {
    description: 'Please complete the captcha again.'
  })
}

const onHCaptchaError = (error: string) => {
  form['h-captcha-response'] = ''
  console.error('hCaptcha error:', error)
  toast.error('Captcha Error', {
    description: 'There was an issue with the captcha. Please try again.'
  })
}

// Initialize hCaptcha
const initHCaptcha = async () => {
  try {
    await loadHCaptchaScript()

    if (window.hcaptcha && siteKey) {
      hcaptchaWidgetId.value = window.hcaptcha.render('hcaptcha-container', {
        sitekey: siteKey,
        callback: onHCaptchaVerify,
        'expired-callback': onHCaptchaExpire,
        'error-callback': onHCaptchaError,
        theme: isDark.value ? 'dark' : 'light',
        size: 'normal' // or 'compact'
      })
    }
  } catch (error) {
    console.error('Failed to initialize hCaptcha:', error)
    toast.error('Captcha Loading Error', {
      description: 'Failed to load captcha. Please refresh the page.'
    })
  }
}

const handleSubmit = async () => {
  if (isSubmitting.value) return

  // Check if hCaptcha is completed
  if (!form['h-captcha-response']) {
    toast.error('Captcha Required', {
      description: 'Please complete the captcha before submitting.'
    })
    return
  }

  isSubmitting.value = true

  try {
    const response = await form.post(route('contact.submit'))

    if (response.data.message) {
      toast.success('Success!', {
        description: response.data.message
      })
      form.reset()

      if (window.hcaptcha && hcaptchaWidgetId.value) {
        window.hcaptcha.reset(hcaptchaWidgetId.value)
      }
    }
  } catch (error: any) {
    if (window.hcaptcha && hcaptchaWidgetId.value) {
      window.hcaptcha.reset(hcaptchaWidgetId.value)
    }

    if (error.response?.status === 429) {
      toast.error('Too Many Attempts', {
        description: error.response.data.message
      })
    } else {
      const errors = error.response?.data?.errors || {}
      Object.entries(errors).forEach(([, messages]) => {
        toast.error('Validation Error', {
          description: Array.isArray(messages) ? messages[0] : messages
        })
      })

      toast.error(error.response?.data?.error_code, {
        description: error.response?.data?.message
      })
    }
  } finally {
    isSubmitting.value = false
  }
}

onMounted(() => {
  if (siteKey) {
    initHCaptcha()
  } else {
    console.warn('hCaptcha site key not found. Please set VITE_HCAPTCHA_SITE_KEY in your environment variables.')
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
  }
}
</script>

<template>
  <form @submit.prevent="handleSubmit" class="space-y-6">
    <!-- Honeypot fields -->
    <div v-if="honeypot.enabled" style="display:none;">
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
      <div class="space-y-2">
        <Label for="name" class="font-medium">Full Name</Label>
        <Input
          id="name"
          v-model="form.name"
          type="text"
          class="h-12"
          placeholder="John Doe"
          required
          :class="{ 'border-red-500': form.errors.has('name') }"
        />

        <p v-if="form.errors.has('name')" class="text-sm text-red-500">
          {{ form.errors.get('name') }}
        </p>
      </div>

      <div class="space-y-2">
        <Label for="email" class="font-medium">Email Address</Label>
        <Input
          id="email"
          v-model="form.email"
          type="email"
          class="h-12"
          placeholder="johndoe@example.com"
          required
          :class="{ 'border-red-500': form.errors.has('email') }"
        />

        <p v-if="form.errors.has('email')" class="text-sm text-red-500">
          {{ form.errors.get('email') }}
        </p>
      </div>
    </div>

    <!-- Subject -->
    <div class="space-y-2">
      <Label for="subject" class="font-medium">Subject</Label>
      <Input
        id="subject"
        v-model="form.subject"
        type="text"
        class="h-12"
        placeholder="How can we help you?"
        required
        :class="{ 'border-red-500': form.errors.has('subject') }"
      />

      <p v-if="form.errors.has('subject')" class="text-sm text-red-500">
        {{ form.errors.get('subject') }}
      </p>
    </div>

    <!-- Message -->
    <div class="space-y-2">
      <Label for="message" class="font-medium">Message</Label>
      <Textarea
        ref="userMessageTextarea"
        id="message"
        v-model="form.message"
        rows="5"
        placeholder="Write your message here..."
        required
        class="resize-none"
        :class="{ 'border-red-500': form.errors.has('message') }"
      />

      <p v-if="form.errors.has('message')" class="text-sm text-red-500">
        {{ form.errors.get('message') }}
      </p>
    </div>

    <!-- hCaptcha -->
    <div class="space-y-2">
      <div id="hcaptcha-container"></div>
      <p v-if="form.errors.has('h-captcha-response')" class="text-sm text-red-500">
        {{ form.errors.get('h-captcha-response') }}
      </p>
    </div>

    <!-- Submit Button -->
    <div>
      <Button
        type="submit"
        :disabled="form.busy"
        class="h-14 px-6 py-3 bg-ca-highlight text-white rounded-lg hover:bg-ca-highlight/90 disabled:opacity-50">
        <span>{{ (form.busy) ? 'Sending...' : 'Send Message' }}</span>
        <LucideAirplay v-if="!form.busy" class="w-5 h-5 ml-2" />
      </Button>
    </div>
  </form>
</template>
