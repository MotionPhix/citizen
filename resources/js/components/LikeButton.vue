<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AuthDialog from '@/components/AuthDialog.vue';
import { Heart } from 'lucide-vue-next';
import { ref } from 'vue';
import { useEventBus } from '@vueuse/core';
import axios from 'axios'

const props = defineProps<{
  postSlug: string;
  initialCount: number;
  isAuthenticated: boolean;
}>();

const likesCount = ref(props.initialCount);
const isLiked = ref(false);
const isLoading = ref(false);
const toast = useEventBus('toast');

const toggleLike = async () => {
  if (isLoading.value) return;

  isLoading.value = true;

  try {
    const response = await axios.post(`/blogs/${props.postSlug}/like`);

    if (response.status === 200) {
      likesCount.value = response.data.likes_count;
      isLiked.value = response.data.is_liked;

      toast.emit('add', {
        type: 'success',
        message: response.data.message
      });
    }
  } catch (error) {
    toast.emit('add', {
      type: 'error',
      message: 'Failed to update like status'
    });
  } finally {
    isLoading.value = false;
  }
};

const onAuthenticated = () => {
  toggleLike();
};
</script>

<template>
  <div class="flex items-center justify-center gap-2">
    <AuthDialog v-if="!isAuthenticated" mode="like" @authenticated="onAuthenticated">
      <Button variant="ghost" size="sm" class="group">
        <Heart class="h-6 w-6 transition-colors fill-none stroke-current group-hover:stroke-red-500" />
      </Button>
    </AuthDialog>

    <Button
      v-else
      variant="ghost"
      size="sm"
      :disabled="isLoading"
      @click="toggleLike"
      class="group">
      <Heart
        :class="[
          'h-6 w-6 transition-colors',
          isLiked ? 'fill-red-500 stroke-red-500' : 'fill-none stroke-current group-hover:stroke-red-500'
        ]"
      />
    </Button>

    <span class="text-sm font-medium">
      {{ likesCount }} {{ likesCount === 1 ? 'like' : 'likes' }}
    </span>
  </div>
</template>
