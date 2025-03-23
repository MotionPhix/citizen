<script setup lang="ts">
import {
  Facebook,
  Twitter,
  Linkedin,
  Instagram,
  Youtube,
  Globe
} from 'lucide-vue-next'

type SocialPlatform = 'facebook' | 'twitter' | 'linkedin' | 'instagram' | 'youtube' | 'website'

defineProps<{
  platform: SocialPlatform
}>()

const iconMap = {
  facebook: Facebook,
  twitter: Twitter,
  linkedin: Linkedin,
  instagram: Instagram,
  youtube: Youtube,
  website: Globe
}
</script>

<template>
  <component
    :is="iconMap[platform]"
    class="w-5 h-5"
  />
</template>
