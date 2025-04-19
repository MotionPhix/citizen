<script setup lang="ts">
import Form from 'vform'
import VueHcaptcha from '@hcaptcha/vue3-hcaptcha'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Textarea } from '@/components/ui/textarea'
import { LucideAirplay } from 'lucide-vue-next'
import { toast } from 'vue-sonner'
import { ref } from 'vue'
import { useDark } from '@vueuse/core';
import { Label } from '@/components/ui/label';

const sitekey = import.meta.env.VITE_HCAPTCHA_SITEKEY

const isDark = useDark()

const captchaToken = ref('')
const hcaptcha = ref()

const form = new Form({
  name: '',
  email: '',
  subject: '',
  message: '',
  'h-captcha-response': ''
})

const onVerify = (token: string) => {
  form['h-captcha-response'] = token
}

const onError = (error: Error) => {
  console.error('hCaptcha error:', error)

  toast.error('Error', {
    description: 'Failed to verify captcha. Please try again.'
  })
}

const onExpire = () => {
  form['h-captcha-response'] = ''

  toast.error({
    description: 'Captcha expired. Please verify again.',
  })
}

const handleSubmit = async () => {
  try {
    const resp = await form.post(route('contact.submit'))

    toast.success('Success!', {
      description: resp.data.message
    })

    form.reset()
    hcaptcha.value?.reset()

  } catch (error) {
    // Handle different types of errors based on status codes
    if (error.response?.status === 429) {

      toast.error('Rate Limit Exceeded', {
        description: error.response.data.message
      })

    } else if (error.response?.data?.errors) {
      Object.keys(error.response.data.errors).forEach(field => {
        toast.error('Validation Error', {
          description: error.response.data.errors[field][0]
        })
      })
    } else {
      toast.error('Error', {
        description: error.response?.data?.message || 'Failed to send message. Please try again.',
      })
    }
  }
}
</script>

<template>
  <form @submit.prevent="handleSubmit" class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="gap-y-2">
        <Label for="name">
          Name
        </Label>
        <Input
          id="name"
          class="h-12"
          v-model="form.name"
          type="text"
          required
          :class="{
            'border-red-500 dark:border-red-500 focus:ring-red-500 dark:focus:ring-red-500': form.errors.has('name')
          }"
        />
        <p
          v-if="form.errors.has('name')"
          class="mt-1 text-sm text-red-500"
        >
          {{ form.errors.get('name') }}
        </p>
      </div>

      <div class="gap-y-2">
        <Label for="email">
          Email
        </Label>

        <Input
          id="email"
          class="h-12"
          v-model="form.email"
          type="email"
          required
          :class="{
            'border-red-500 dark:border-red-500 focus:ring-red-500 dark:focus:ring-red-500': form.errors.has('email')
          }"
        />
        <p
          v-if="form.errors.has('email')"
          class="mt-1 text-sm text-red-500"
        >
          {{ form.errors.get('email') }}
        </p>
      </div>
    </div>

    <div class="gap-y-2">
      <Label for="subject">
        Subject
      </Label>
      <Input
        id="subject"
        v-model="form.subject"
        type="text"
        required
        class="h-12"
        :class="{
          'border-red-500 dark:border-red-500 focus:ring-red-500 dark:focus:ring-red-500': form.errors.has('subject')
        }"
      />
      <p
        v-if="form.errors.has('subject')"
        class="mt-1 text-sm text-red-500"
      >
        {{ form.errors.get('subject') }}
      </p>
    </div>

    <div class="gap-y-2">
      <Label for="message">
        Message
      </Label>
      <Textarea
        id="message"
        v-model="form.message"
        rows="5"
        required
        :class="{
          'border-red-500 dark:border-red-500 focus:ring-red-500 dark:focus:ring-red-500': form.errors.has('message')
        }"
      />
      <p
        v-if="form.errors.has('message')"
        class="mt-1 text-sm text-red-500"
      >
        {{ form.errors.get('message') }}
      </p>
    </div>

    <div>
      <VueHcaptcha
        ref="hcaptcha"
        :sitekey="sitekey"
        @verify="onVerify"
        @error="onError"
        @expired="onExpire"
        :theme="isDark ? 'dark' : 'light'"
      />
      <p
        v-if="form.errors.has('h-captcha-response')"
        class="mt-1 text-sm text-red-500">
        {{ form.errors.get('h-captcha-response') }}
      </p>
    </div>

    <div>
      <Button
        type="submit"
        :disabled="form.busy"
        class="inline-flex items-center h-14 px-6 py-3 bg-ca-highlight text-white rounded-lg hover:bg-ca-highlight/90 transition-colors duration-300"
      >
        {{ form.busy ? 'Sending...' : 'Send Message' }}
        <LucideAirplay
          v-if="!form.busy"
          class="w-5 h-5 ml-2"
        />
      </Button>
    </div>
  </form>
</template>
