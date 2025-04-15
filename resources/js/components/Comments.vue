<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Textarea } from '@/components/ui/textarea';
import AuthDialog from '@/components/AuthDialog.vue';
import axios from 'axios';
import { ref } from 'vue';
import { useToast } from '@/composables/useToast';
import {Heart, Reply} from 'lucide-vue-next';

interface User {
  id: number;
  name: string;
  avatar: string;
}

interface CommentType {
  id: number;
  content: string;
  user: User;
  created_at: string;
  likes_count: number;
  is_liked: boolean;
  replies?: CommentType[];
}

const props = defineProps<{
  postSlug: string;
  initialComments: CommentType[];
  isAuthenticated: boolean;
}>();

const comments = ref<CommentType[]>(props.initialComments);
const newComment = ref('');
const isSubmitting = ref(false);
const replyingTo = ref<number | null>(null);
const replyContent = ref('');
const { showToast } = useToast();

const submitComment = async () => {
  if (!newComment.value.trim() || isSubmitting.value) return

  try {
    isSubmitting.value = true
    const response = await axios.post(`/blogs/${props.postSlug}/comments`, {
      content: newComment.value,
      parent_id: replyingTo.value
    })

    if (response.status === 200) {
      const { comment } = response.data

      if (replyingTo.value) {
        const parentComment = comments.value.find(c => c.id === replyingTo.value)
        if (parentComment) {
          parentComment.replies = [...(parentComment.replies || []), comment]
        }
        replyingTo.value = null
        replyContent.value = ''
      } else {
        comments.value = [comment, ...comments.value]
        newComment.value = ''
      }

      showToast({
        type: 'success',
        message: 'Comment posted successfully!'
      })
    }
  } catch (error: any) {
    showToast({
      type: 'error',
      message: error.response?.data?.message || 'Failed to post comment'
    })
  } finally {
    isSubmitting.value = false
  }
}

const formatDate = (date: string) => {
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
};
</script>

<template>
  <div class="mt-12">
    <h3 class="text-2xl font-bold mb-8">
      Comments ({{ comments.length }})
    </h3>

    <!-- New Comment Form -->
    <!-- New Comment Form -->
    <div class="mb-8">
      <div v-if="!isAuthenticated">
        <AuthDialog mode="comment">
          <Button variant="outline" class="w-full">
            Sign in to join the discussion
          </Button>
        </AuthDialog>
      </div>

      <div v-else>
        <Textarea
          v-model="newComment"
          placeholder="Write a comment..."
          rows="3"
          class="mb-4"
        />
        <Button
          @click="submitComment"
          :disabled="!newComment.trim() || isSubmitting">
          Post Comment
        </Button>
      </div>
    </div>

    <!-- Comments List -->
    <div class="space-y-8">
      <div
        v-for="comment in comments"
        :key="comment.id"
        class="bg-white dark:bg-gray-800 rounded-lg p-6">
        <div class="flex items-start gap-4">
          <img
            :src="comment.user.avatar"
            :alt="comment.user.name"
            class="w-10 h-10 rounded-full"
          />
          <div class="flex-1">
            <div class="flex items-center justify-between">
              <div>
                <h4 class="font-medium">{{ comment.user.name }}</h4>
                <p class="text-sm text-gray-500">
                  {{ formatDate(comment.created_at) }}
                </p>
              </div>
              <div class="flex items-center gap-4">
                <Button
                  variant="ghost"
                  size="sm"
                  @click="toggleLike(comment.id)">
                  <Heart
                    :class="[
                      'h-4 w-4',
                      comment.is_liked ? 'fill-red-500 stroke-red-500' : 'fill-none'
                    ]"
                  />
                  <span class="ml-1">{{ comment.likes_count }}</span>
                </Button>
                <Button
                  variant="ghost"
                  size="sm"
                  @click="toggleReply(comment.id)">
                  <Reply class="h-4 w-4" />
                  <span class="ml-1">Reply</span>
                </Button>
              </div>
            </div>
            <p class="mt-2 text-gray-700 dark:text-gray-300">
              {{ comment.content }}
            </p>

            <!-- Reply Form -->
            <div v-if="replyingTo === comment.id" class="mt-4">
              <Textarea
                v-model="replyContent"
                placeholder="Write a reply..."
                rows="2"
                class="mb-2"
              />
              <div class="flex items-center gap-2">
                <Button
                  size="sm"
                  @click="submitComment"
                  :disabled="!replyContent.trim() || isSubmitting"
                >
                  Post Reply
                </Button>
                <Button
                  size="sm"
                  variant="ghost"
                  @click="toggleReply(comment.id)"
                >
                  Cancel
                </Button>
              </div>
            </div>

            <!-- Replies -->
            <div
              v-if="comment.replies?.length"
              class="mt-4 space-y-4 pl-6 border-l-2 border-gray-200 dark:border-gray-700"
            >
              <div
                v-for="reply in comment.replies"
                :key="reply.id"
                class="bg-gray-50 dark:bg-gray-900 rounded p-4"
              >
                <div class="flex items-start gap-3">
                  <img
                    :src="reply.user.avatar"
                    :alt="reply.user.name"
                    class="w-8 h-8 rounded-full"
                  />
                  <div class="flex-1">
                    <div class="flex items-center justify-between">
                      <div>
                        <h5 class="font-medium">{{ reply.user.name }}</h5>
                        <p class="text-xs text-gray-500">
                          {{ formatDate(reply.created_at) }}
                        </p>
                      </div>
                      <Button
                        variant="ghost"
                        size="sm"
                        @click="toggleLike(reply.id)"
                      >
                        <Heart
                          :class="[
                            'h-4 w-4',
                            reply.is_liked ? 'fill-red-500 stroke-red-500' : 'fill-none'
                          ]"
                        />
                        <span class="ml-1">{{ reply.likes_count }}</span>
                      </Button>
                    </div>
                    <p class="mt-1 text-gray-700 dark:text-gray-300">
                      {{ reply.content }}
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
