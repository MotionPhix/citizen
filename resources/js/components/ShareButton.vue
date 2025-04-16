<script setup>
import { computed } from 'vue'
import { useToast } from '@/composables/useToast'
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu"
import { Copy, Facebook, Twitter, Share } from 'lucide-vue-next';

const props = defineProps({
  url: {
    type: String,
    required: true
  },
  title: {
    type: String,
    required: true
  }
})

const { toast } = useToast()

const copyToClipboard = async () => {
  try {
    await navigator.clipboard.writeText(props.url)
    toast({
      title: "Success",
      description: "Link copied to clipboard!",
    })
  } catch (err) {
    toast({
      variant: "destructive",
      title: "Error",
      description: "Failed to copy link",
    })
  }
}

const shareLinks = [
  {
    name: 'Copy link',
    icon: Copy,
    action: copyToClipboard
  },
  {
    name: 'Share on Twitter',
    icon: Twitter,
    href: computed(() => `https://twitter.com/intent/tweet?url=${encodeURIComponent(props.url)}&text=${encodeURIComponent(props.title)}`)
  },
  {
    name: 'Share on Facebook',
    icon: Facebook,
    href: computed(() => `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(props.url)}`)
  }
]
</script>

<template>
  <DropdownMenu>
    <DropdownMenuTrigger
      class="inline-flex items-center gap-x-2 text-sm text-gray-500 hover:text-gray-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-primary dark:text-neutral-400 dark:hover:text-neutral-200"
    >
      <Share class="h-4 w-4" />
      Share
    </DropdownMenuTrigger>

    <DropdownMenuContent>
      <template v-for="(link, index) in shareLinks" :key="index">
        <template v-if="link.action">
          <DropdownMenuItem @click="link.action" class="cursor-pointer">
            <component
              class="mr-2 h-4 w-4"
              :is="link.icon"
            />

            <span>{{ link.name }}</span>
          </DropdownMenuItem>
        </template>

        <template v-else>
          <DropdownMenuItem asChild>
            <a
              :href="link.href"
              target="_blank"
              rel="noopener noreferrer"
              class="flex items-center">
              <component
                class="mr-2 h-4 w-4"
                :is="link.icon"
              />
              <span>{{ link.name }}</span>
            </a>
          </DropdownMenuItem>
        </template>

        <DropdownMenuSeparator
          v-if="index < shareLinks.length - 1"
        />
      </template>
    </DropdownMenuContent>
  </DropdownMenu>
</template>
