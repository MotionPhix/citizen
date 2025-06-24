<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { Form } from 'vform'
import VueHcaptcha from '@hcaptcha/vue3-hcaptcha'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Textarea } from '@/components/ui/textarea'
import { Label } from '@/components/ui/label'
import { useDark } from '@vueuse/core'
import { toast } from 'vue-sonner'
import { LucideAirplay } from 'lucide-vue-next'

const sitekey = import.meta.env.VITE_HCAPTCHA_SITEKEY
const isDark = useDark()
const hcaptcha = ref()

const props = defineProps<{
  honeypot: object
}>()

declare global {
  interface Window {
    grecaptcha: any;
    onRecaptchaLoad: () => void;
  }
}

const form = new Form({
  name: '',
  email: '',
  subject: '',
  message: '',
  'g-recaptcha-response': '',
  [props.honeypot.nameFieldName]: '',
  [props.honeypot.validFromFieldName]: props.honeypot.encryptedValidFrom,
})

const isSubmitting = ref(false)

onMounted(() => {
  // Load reCAPTCHA script
  const script = document.createElement('script')
  script.src = `https://www.google.com/recaptcha/api.js?render=${import.meta.env.VITE_GOOGLE_RECAPTCHA_SITE_KEY}`
  document.head.appendChild(script)
})

const executeRecaptcha = async () => {
  try {
    const token = await window.grecaptcha.execute(import.meta.env.VITE_GOOGLE_RECAPTCHA_SITE_KEY, { action: 'contact' })
    form['g-recaptcha-response'] = token
    return true
  } catch (error) {
    console.error('reCAPTCHA error:', error)
    toast.error('Verification Error', {
      description: 'Failed to verify you are human. Please try again.'
    })
    return false
  }
}

const onVerify = (token: string) => {
  form['h-captcha-response'] = token
}

const onError = (error: Error) => {
  console.error('hCaptcha error:', error)
  toast.error('Captcha Error', {
    description: 'Failed to load captcha. Please refresh and try again.'
  })
}

const onExpire = () => {
  form['h-captcha-response'] = ''
  toast.error('Captcha Expired', {
    description: 'The captcha has expired. Please verify again.'
  })
}

/*const handleSubmit = async () => {
  try {
    if (!form['h-captcha-response']) {
      toast.error('Verification Required', {
        description: 'Please complete the captcha verification.'
      })
      return
    }

    const response = await form.post(route('contact.submit'))

    if (response.data.message) {
      toast.success('Success!', {
        description: response.data.message
      })

      // Reset form and captcha
      form.reset()
      hcaptcha.value?.reset()
    }
  } catch (error: any) {
    if (error.response?.status === 429) {
      toast.error('Too Many Attempts', {
        description: error.response.data.message
      })
    } else {
      const errors = error.response?.data?.errors || {}
      Object.entries(errors).forEach(([field, messages]) => {
        toast.error('Validation Error', {
          description: Array.isArray(messages) ? messages[0] : messages
        })
      })
    }
  }
}*/

const handleSubmit = async () => {
  if (isSubmitting.value) return
  isSubmitting.value = true

  try {
    const recaptchaSuccess = await executeRecaptcha()
    if (!recaptchaSuccess) {
      isSubmitting.value = false
      return
    }

    const response = await form.post(route('contact.submit'))

    if (response.data.message) {
      toast.success('Success!', {
        description: response.data.message
      })
      form.reset()
    }
  } catch (error: any) {
    if (error.response?.status === 429) {
      toast.error('Too Many Attempts', {
        description: error.response.data.message
      })
    } else {
      const errors = error.response?.data?.errors || {}
      Object.entries(errors).forEach(([field, messages]) => {
        toast.error('Validation Error', {
          description: Array.isArray(messages) ? messages[0] : messages
        })
      })
    }
  } finally {
    isSubmitting.value = false
  }
}
</script>

<template>
  <form @submit.prevent="handleSubmit" class="space-y-6">
    <div v-if="honeypot.enabled" style="display:none;">
      <input type="text"
             :name="honeypot.nameFieldName"
             :id="honeypot.nameFieldName"
             v-model="form[honeypot.nameFieldName]" />
      <input type="text"
             name="valid_from"
             v-model="form.valid_from" />
    </div>

    <!-- Name and Email Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="space-y-2">
        <Label for="name" class="font-medium">
          Full Name
        </Label>
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
        <Label for="email" class="font-medium">
          Email Address
        </Label>
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
      <Label for="subject" class="font-medium">
        Subject
      </Label>
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
      <Label for="message" class="font-medium">
        Message
      </Label>
      <Textarea
        id="message"
        v-model="form.message"
        rows="5"
        placeholder="Write your message here..."
        required
        :class="{ 'border-red-500': form.errors.has('message') }"
      />
      <p v-if="form.errors.has('message')" class="text-sm text-red-500">
        {{ form.errors.get('message') }}
      </p>
    </div>

    <!-- Captcha -->
<!--    <div class="space-y-2">-->
<!--      <VueHcaptcha-->
<!--        ref="hcaptcha"-->
<!--        :sitekey="sitekey"-->
<!--        @verify="onVerify"-->
<!--        @error="onError"-->
<!--        @expired="onExpire"-->
<!--        :theme="isDark ? 'dark' : 'light'"-->
<!--      />-->
<!--      <p v-if="form.errors.has('h-captcha-response')" class="text-sm text-red-500">-->
<!--        {{ form.errors.get('h-captcha-response') }}-->
<!--      </p>-->
<!--    </div>-->

    <!-- Submit Button -->
    <div>
      <Button
        type="submit"
        :disabled="form.busy"
        class="inline-flex items-center h-14 px-6 py-3 bg-ca-highlight text-white rounded-lg hover:bg-ca-highlight/90 transition-colors duration-300"
      >
        <span>{{ form.busy ? 'Sending...' : 'Send Message' }}</span>
        <LucideAirplay v-if="!form.busy" class="w-5 h-5 ml-2" />
      </Button>
    </div>
  </form>
</template>
