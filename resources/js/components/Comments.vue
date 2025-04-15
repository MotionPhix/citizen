<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Textarea } from '@/components/ui/textarea';
import AuthDialog from '@/components/AuthDialog.vue';
import axios from 'axios';
import { ref, computed } from 'vue';
import { useToast } from '@/composables/useToast';
import { Heart, Reply, Trash } from 'lucide-vue-next';

interface User {
  id: number;
  name: string;
  email: string;
  avatar?: string;
}

interface CommentType {
  id: number;
  content: string;
  user: User;
  created_at: string;
  likes_count: number;
  is_liked: boolean;
  parent_id: number | null;
  replies?: CommentType[];
  can_delete: boolean;
}

const props = defineProps<{
  postSlug: string;
  initialComments: CommentType[];
  isAuthenticated: boolean;
  currentUser?: User;
}>();

console.log(props.initialComments);

const comments = ref<CommentType[]>(props.initialComments);
const newComment = ref('');
const isSubmitting = ref(false);
const replyingTo = ref<number | null>(null);
const replyContent = ref('');
const { showToast } = useToast();

// Sort comments by newest first
const sortedComments = computed(() => {
  return [...comments.value].sort((a, b) => {
    return new Date(b.created_at).getTime() - new Date(a.created_at).getTime();
  });
});

const submitComment = async () => {
  const content = replyingTo.value ? replyContent.value : newComment.value;
  if (!content.trim() || isSubmitting.value) return;

  try {
    isSubmitting.value = true;
    const response = await axios.post(`/blogs/${props.postSlug}/comments`, {
      content: content.trim(),
      parent_id: replyingTo.value
    });

    if (response.status === 200) {
      const { comment } = response.data;

      if (replyingTo.value) {
        const parentComment = comments.value.find(c => c.id === replyingTo.value);
        if (parentComment) {
          parentComment.replies = [...(parentComment.replies || []), comment];
        }
        replyingTo.value = null;
        replyContent.value = '';
      } else {
        comments.value = [comment, ...comments.value];
        newComment.value = '';
      }

      showToast({
        type: 'success',
        message: 'Comment posted successfully!'
      });
    }
  } catch (error: any) {
    showToast({
      type: 'error',
      message: error.response?.data?.message || 'Failed to post comment'
    });
  } finally {
    isSubmitting.value = false;
  }
};

const toggleLike = async (comment: CommentType) => {
  if (!props.isAuthenticated) return;

  try {
    const response = await axios.post(route('blogs.comments.like.toggle', { blog: props.postSlug, comment: comment}));

    if (response.status === 200) {
      // Update the comment's like status and count
      comment.is_liked = response.data.is_liked;
      comment.likes_count = response.data.likes_count;

      showToast({
        type: 'success',
        message: response.data.message
      });
    }
  } catch (error: any) {
    showToast({
      type: 'error',
      message: error.response?.data?.message || 'Failed to update like status'
    });
  }
};

const toggleReply = (commentId: number | null) => {
  if (replyingTo.value === commentId) {
    replyingTo.value = null;
    replyContent.value = '';
  } else {
    replyingTo.value = commentId;
    replyContent.value = '';
  }
};

const deleteComment = async (comment: CommentType) => {
  if (!confirm('Are you sure you want to delete this comment?')) return;

  try {
    const response = await axios.delete(`/blogs/${props.postSlug}/comments/${comment.id}`);

    if (response.status === 200) {
      // Remove the comment from the list
      if (comment.parent_id) {
        const parentComment = comments.value.find(c => c.id === comment.parent_id);
        if (parentComment && parentComment.replies) {
          parentComment.replies = parentComment.replies.filter(r => r.id !== comment.id);
        }
      } else {
        comments.value = comments.value.filter(c => c.id !== comment.id);
      }

      showToast({
        type: 'success',
        message: 'Comment deleted successfully'
      });
    }
  } catch (error: any) {
    showToast({
      type: 'error',
      message: error.response?.data?.message || 'Failed to delete comment'
    });
  }
};

const formatDate = (date: string) => {
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
};

const isCurrentUser = (userId: number) => {
  return props.currentUser?.id === userId;
};
</script>

<template>
  <section class="mt-16 space-y-8">
    <h3 class="text-2xl font-display font-bold text-gray-900 dark:text-white">
      Comments ({{ comments.length }})
    </h3>

    <!-- New Comment Form -->
    <div v-if="!isAuthenticated">
      <AuthDialog mode="comment">
        <Button variant="outline" class="w-full">
          Sign in to join the discussion
        </Button>
      </AuthDialog>
    </div>

    <div v-else class="space-y-4">
      <Textarea
        v-model="newComment"
        placeholder="Write a comment..."
        :rows="4"
        class="resize-none"
      />
      <Button
        :disabled="!newComment.trim() || isSubmitting"
        @click="submitComment"
      >
        Post Comment
      </Button>
    </div>

    <!-- Comments List -->
    <div class="space-y-6">
      <article
        v-for="comment in sortedComments"
        :key="comment.id"
        class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm"
      >
        <div class="space-y-4">
          <div class="flex items-start justify-between">
            <div class="flex items-center space-x-2">
              <div v-if="comment.user.avatar" class="h-10 w-10 rounded-full overflow-hidden">
                <img
                  :src="comment.user.avatar"
                  :alt="comment.user.name"
                  class="h-full w-full object-cover"
                />
              </div>
              <div>
                <h4 class="font-medium text-gray-900 dark:text-white">
                  {{ comment.user.name }}
                </h4>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                  {{ formatDate(comment.created_at) }}
                </p>
              </div>
            </div>

            <Button
              v-if="isCurrentUser(comment.user.id)"
              variant="ghost"
              size="sm"
              @click="deleteComment(comment)"
            >
              <Trash class="h-4 w-4" />
            </Button>
          </div>

          <p class="text-gray-600 dark:text-gray-300">
            {{ comment.content }}
          </p>

          <div class="flex items-center space-x-4">
            <AuthDialog v-if="!isAuthenticated" mode="like">
              <Button variant="ghost" size="sm" class="group">
                <Heart class="h-4 w-4 fill-none stroke-current group-hover:stroke-red-500" />
                <span class="ml-1">{{ comment.likes_count }}</span>
              </Button>
            </AuthDialog>

            <Button
              v-else
              variant="ghost"
              size="sm"
              @click="toggleLike(comment)"
              class="group">
              <Heart
                :class="[
                  'h-4 w-4 transition-colors',
                  comment.is_liked ? 'fill-red-500 stroke-red-500' : 'fill-none stroke-current group-hover:stroke-red-500'
                ]"
              />
              <span class="ml-1">{{ comment.likes_count }}</span>
            </Button>

            <Button
              v-if="isAuthenticated"
              variant="ghost"
              size="sm"
              @click="toggleReply(comment.id)">
              <Reply class="h-4 w-4" />
              <span class="ml-1">Reply</span>
            </Button>
          </div>

          <!-- Reply Form -->
          <div v-if="replyingTo === comment.id" class="mt-4 space-y-4">
            <Textarea
              v-model="replyContent"
              placeholder="Write a reply..."
              :rows="3"
              class="resize-none"
            />

            <div class="flex space-x-2">
              <Button
                :disabled="!replyContent.trim() || isSubmitting"
                @click="submitComment">
                Post Reply
              </Button>

              <Button
                variant="outline"
                @click="toggleReply(null)">
                Cancel
              </Button>
            </div>
          </div>

          <!-- Nested Replies -->
          <div v-if="comment.replies && comment.replies.length > 0" class="mt-4 space-y-4 pl-6 border-l-2 border-gray-100 dark:border-gray-700">
            <article
              v-for="reply in comment.replies"
              :key="reply.id"
              class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
              <div class="flex items-start justify-between">
                <div class="flex items-center space-x-2">
                  <div v-if="reply.user.avatar" class="h-8 w-8 rounded-full overflow-hidden">
                    <img
                      :src="reply.user.avatar"
                      :alt="reply.user.name"
                      class="h-full w-full object-cover"
                    />
                  </div>
                  <div>
                    <h4 class="font-medium text-gray-900 dark:text-white">
                      {{ reply.user.name }}
                    </h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                      {{ formatDate(reply.created_at) }}
                    </p>
                  </div>
                </div>

                <Button
                  v-if="isCurrentUser(reply.user.id)"
                  variant="ghost"
                  size="sm"
                  @click="deleteComment(reply)">
                  <Trash class="h-4 w-4" />
                </Button>
              </div>

              <p class="mt-2 text-gray-600 dark:text-gray-300">
                {{ reply.content }}
              </p>

              <div class="mt-2 flex items-center space-x-4">
                <AuthDialog v-if="!isAuthenticated" mode="like">
                  <Button variant="ghost" size="sm" class="group">
                    <Heart class="h-4 w-4 fill-none stroke-current group-hover:stroke-red-500" />
                    <span class="ml-1">{{ reply.likes_count }}</span>
                  </Button>
                </AuthDialog>

                <Button
                  v-else
                  variant="ghost"
                  size="sm"
                  @click="toggleLike(reply)"
                  class="group">
                  <Heart
                    :class="[
                      'h-4 w-4 transition-colors',
                      reply.is_liked ? 'fill-red-500 stroke-red-500' : 'fill-none stroke-current group-hover:stroke-red-500'
                    ]"
                  />
                  <span class="ml-1">{{ reply.likes_count }}</span>
                </Button>
              </div>
            </article>
          </div>
        </div>
      </article>
    </div>
  </section>
</template>
