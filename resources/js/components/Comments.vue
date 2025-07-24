<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Textarea } from '@/components/ui/textarea';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card, CardContent } from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import AuthDialog from '@/components/AuthDialog.vue';
import axios from 'axios';
import { ref, computed, onMounted } from 'vue';
import { useToast } from '@/composables/useToast';
import { Heart, Reply, Trash, User, ExternalLink, Clock, MessageCircle } from 'lucide-vue-next';

interface User {
  id: number;
  name: string;
  email: string;
  avatar_url?: string;
}

interface CommentType {
  id: number;
  content: string;
  user?: User;
  display_name: string;
  gravatar_url?: string;
  author_website?: string;
  created_at: string;
  status: string;
  is_approved: boolean;
  is_anonymous: boolean;
  can_edit: boolean;
  can_delete: boolean;
  can_reply: boolean;
  parent_id?: number | null;
  replies?: CommentType[];
}

interface CommentFormData {
  content: string;
  author_name: string;
  author_email: string;
  author_website: string;
  notify_on_reply: boolean;
}

const props = defineProps<{
  postSlug: string;
  initialComments: CommentType[];
  isAuthenticated: boolean;
  currentUser?: User;
}>();

const comments = ref<CommentType[]>(props.initialComments);
const isSubmitting = ref(false);
const replyingTo = ref<number | null>(null);
const { showToast } = useToast();

// Comment form data - default notify_on_reply to false
const commentForm = ref<CommentFormData>({
  content: '',
  author_name: '',
  author_email: '',
  author_website: '',
  notify_on_reply: false
});

const replyForm = ref<CommentFormData>({
  content: '',
  author_name: '',
  author_email: '',
  author_website: '',
  notify_on_reply: false
});

// Load saved form data from localStorage for anonymous users
onMounted(() => {
  if (!props.isAuthenticated) {
    const savedData = localStorage.getItem('comment_form_data');
    if (savedData) {
      try {
        const parsed = JSON.parse(savedData);
        commentForm.value.author_name = parsed.author_name || '';
        commentForm.value.author_email = parsed.author_email || '';
        commentForm.value.author_website = parsed.author_website || '';
        // Keep notify_on_reply as false by default
        commentForm.value.notify_on_reply = parsed.notify_on_reply === true;
      } catch (e) {
        // Ignore parsing errors
      }
    }
  }
});

// Save form data to localStorage for anonymous users
const saveFormData = () => {
  if (!props.isAuthenticated) {
    localStorage.setItem('comment_form_data', JSON.stringify({
      author_name: commentForm.value.author_name,
      author_email: commentForm.value.author_email,
      author_website: commentForm.value.author_website,
      notify_on_reply: commentForm.value.notify_on_reply
    }));
  }
};

// Sort comments by newest first
const sortedComments = computed(() => {
  return [...comments.value].sort((a, b) => {
    return new Date(b.created_at).getTime() - new Date(a.created_at).getTime();
  });
});

// Total approved comments count
const totalComments = computed(() => {
  let count = 0;
  comments.value.forEach(comment => {
    if (comment.is_approved) {
      count++;
      if (comment.replies) {
        count += comment.replies.filter(reply => reply.is_approved).length;
      }
    }
  });
  return count;
});

// Validation - ALWAYS require name and email for anonymous users
const isFormValid = computed(() => {
  const contentValid = commentForm.value.content.trim().length >= 10;

  if (props.isAuthenticated) {
    return contentValid;
  }

  // For anonymous users, ALWAYS require name and email
  const nameValid = commentForm.value.author_name.trim().length >= 2;
  const emailValid = commentForm.value.author_email.trim().length > 0 &&
                     /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(commentForm.value.author_email);

  return contentValid && nameValid && emailValid;
});

const isReplyFormValid = computed(() => {
  const contentValid = replyForm.value.content.trim().length >= 10;

  if (props.isAuthenticated) {
    return contentValid;
  }

  // For anonymous users, ALWAYS require name and email
  const nameValid = replyForm.value.author_name.trim().length >= 2;
  const emailValid = replyForm.value.author_email.trim().length > 0 &&
                     /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(replyForm.value.author_email);

  return contentValid && nameValid && emailValid;
});

const submitComment = async () => {
  if (!isFormValid.value || isSubmitting.value) return;

  try {
    isSubmitting.value = true;

    const data: any = {
      content: commentForm.value.content.trim(),
      notify_on_reply: commentForm.value.notify_on_reply
    };

    // ALWAYS add anonymous user data if not authenticated
    if (!props.isAuthenticated) {
      data.author_name = commentForm.value.author_name.trim();
      data.author_email = commentForm.value.author_email.trim();
      if (commentForm.value.author_website.trim()) {
        data.author_website = commentForm.value.author_website.trim();
      }

      // Save form data for next time
      saveFormData();
    }

    const response = await axios.post(`/blogs/${props.postSlug}/comments`, data);

    if (response.status === 200) {
      const { comment, message } = response.data;

      // Add comment to the list
      comments.value = [comment, ...comments.value];

      // Reset form content but keep user info for anonymous users
      commentForm.value.content = '';
      if (props.isAuthenticated) {
        commentForm.value.author_name = '';
        commentForm.value.author_email = '';
        commentForm.value.author_website = '';
      }

      showToast({
        type: 'success',
        message: message
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

const submitReply = async () => {
  if (!isReplyFormValid.value || isSubmitting.value || !replyingTo.value) return;

  try {
    isSubmitting.value = true;

    const data: any = {
      content: replyForm.value.content.trim(),
      parent_id: replyingTo.value,
      notify_on_reply: replyForm.value.notify_on_reply
    };

    // ALWAYS add anonymous user data if not authenticated
    if (!props.isAuthenticated) {
      data.author_name = replyForm.value.author_name.trim();
      data.author_email = replyForm.value.author_email.trim();
      if (replyForm.value.author_website.trim()) {
        data.author_website = replyForm.value.author_website.trim();
      }
    }

    const response = await axios.post(`/blogs/${props.postSlug}/comments`, data);

    if (response.status === 200) {
      const { comment, message } = response.data;

      // Find parent comment and add reply
      const parentComment = comments.value.find(c => c.id === replyingTo.value);
      if (parentComment) {
        if (!parentComment.replies) {
          parentComment.replies = [];
        }
        parentComment.replies.push(comment);
      }

      // Reset reply form
      replyForm.value.content = '';
      replyForm.value.author_name = '';
      replyForm.value.author_email = '';
      replyForm.value.author_website = '';
      replyForm.value.notify_on_reply = false;
      replyingTo.value = null;

      showToast({
        type: 'success',
        message: message
      });
    }
  } catch (error: any) {
    showToast({
      type: 'error',
      message: error.response?.data?.message || 'Failed to post reply'
    });
  } finally {
    isSubmitting.value = false;
  }
};

const toggleReply = (commentId: number | null) => {
  if (replyingTo.value === commentId) {
    replyingTo.value = null;
    replyForm.value.content = '';
  } else {
    replyingTo.value = commentId;
    replyForm.value.content = '';

    // Pre-fill form data for anonymous users from main form
    if (!props.isAuthenticated) {
      replyForm.value.author_name = commentForm.value.author_name;
      replyForm.value.author_email = commentForm.value.author_email;
      replyForm.value.author_website = commentForm.value.author_website;
      replyForm.value.notify_on_reply = false; // Default to false
    }
  }
};

const deleteComment = async (comment: CommentType) => {
  if (!confirm('Are you sure you want to delete this comment?')) return;

  try {
    const response = await axios.delete(`/comments/${comment.id}`);

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
  const commentDate = new Date(date);
  const now = new Date();
  const diffInHours = (now.getTime() - commentDate.getTime()) / (1000 * 60 * 60);

  if (diffInHours < 1) {
    return 'Just now';
  } else if (diffInHours < 24) {
    return `${Math.floor(diffInHours)} hours ago`;
  } else if (diffInHours < 48) {
    return 'Yesterday';
  } else {
    return commentDate.toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'short',
      day: 'numeric'
    });
  }
};

const isCurrentUser = (comment: CommentType) => {
  return props.currentUser?.id === comment.user?.id;
};
</script>

<template>
  <section class="mt-16 space-y-8">
    <div class="flex items-center justify-between">
      <h3 class="text-2xl font-display font-bold text-gray-900 dark:text-white flex items-center gap-2">
        <MessageCircle class="h-6 w-6" />
        Comments ({{ totalComments }})
      </h3>
    </div>

    <!-- New Comment Form -->
    <Card>
      <CardContent class="p-6">
        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
          Join the Discussion
        </h4>

        <div class="space-y-4">
          <!-- ALWAYS show name/email fields for anonymous users -->
          <div v-if="!isAuthenticated" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-2">
              <Label for="author_name">Name *</Label>
              <Input
                id="author_name"
                v-model="commentForm.author_name"
                placeholder="Your name"
                required
                @blur="saveFormData"
              />
              <p v-if="commentForm.author_name.trim().length > 0 && commentForm.author_name.trim().length < 2"
                 class="text-xs text-red-500">
                Name must be at least 2 characters
              </p>
            </div>

            <div class="space-y-2">
              <Label for="author_email">Email *</Label>
              <Input
                id="author_email"
                v-model="commentForm.author_email"
                type="email"
                placeholder="your@email.com"
                required
                @blur="saveFormData"
              />
              <p class="text-xs text-gray-500">Your email won't be published</p>
              <p v-if="commentForm.author_email.trim().length > 0 && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(commentForm.author_email)"
                 class="text-xs text-red-500">
                Please enter a valid email address
              </p>
            </div>

            <div class="space-y-2 md:col-span-2">
              <Label for="author_website">Website (optional)</Label>
              <Input
                id="author_website"
                v-model="commentForm.author_website"
                type="url"
                placeholder="https://yourwebsite.com"
                @blur="saveFormData"
              />
            </div>
          </div>

          <!-- Comment content -->
          <div class="space-y-2">
            <Label for="comment_content">Comment *</Label>
            <Textarea
              id="comment_content"
              v-model="commentForm.content"
              placeholder="Share your thoughts..."
              :rows="4"
              class="resize-none"
              required
            />
            <p class="text-xs text-gray-500">
              Minimum 10 characters. {{ commentForm.content.length }}/2000
            </p>
            <p v-if="commentForm.content.trim().length > 0 && commentForm.content.trim().length < 10"
               class="text-xs text-red-500">
              Comment must be at least 10 characters
            </p>
          </div>

          <!-- Notification preference -->
          <div class="flex items-center space-x-2">
            <Checkbox
              id="notify_on_reply"
              v-model:checked="commentForm.notify_on_reply"
              @update:checked="saveFormData"
            />
            <Label for="notify_on_reply" class="text-sm">
              Notify me of replies via email
            </Label>
          </div>

          <!-- Submit button -->
          <div class="flex justify-between items-center">
            <p v-if="!isAuthenticated" class="text-sm text-gray-500">
              Your information will be saved for future comments
            </p>
            <Button
              :disabled="!isFormValid || isSubmitting"
              @click="submitComment"
              class="ml-auto"
            >
              {{ isSubmitting ? 'Posting...' : 'Post Comment' }}
            </Button>
          </div>
        </div>
      </CardContent>
    </Card>

    <!-- Comments List -->
    <div v-if="sortedComments.length === 0" class="text-center py-12">
      <MessageCircle class="h-12 w-12 text-gray-400 mx-auto mb-4" />
      <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
        No comments yet
      </h4>
      <p class="text-gray-500">Be the first to share your thoughts!</p>
    </div>

    <div v-else class="space-y-6">
      <article
        v-for="comment in sortedComments"
        :key="comment.id"
        class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700"
      >
        <div class="space-y-4">
          <!-- Comment Header -->
          <div class="flex items-start justify-between">
            <div class="flex items-center space-x-3">
              <!-- Avatar -->
              <div class="h-10 w-10 rounded-full overflow-hidden bg-gray-100 dark:bg-gray-700 flex-shrink-0">
                <img
                  v-if="comment.user?.avatar_url || comment.gravatar_url"
                  :src="comment.user?.avatar_url || comment.gravatar_url"
                  :alt="comment.display_name"
                  class="h-full w-full object-cover"
                />
                <div v-else class="h-full w-full flex items-center justify-center">
                  <User class="h-5 w-5 text-gray-400" />
                </div>
              </div>

              <!-- Author Info -->
              <div>
                <div class="flex items-center gap-2">
                  <h4 class="font-medium text-gray-900 dark:text-white">
                    {{ comment.display_name }}
                  </h4>
                  <a
                    v-if="comment.author_website"
                    :href="comment.author_website"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="text-ca-primary hover:text-ca-primary/80 transition-colors"
                  >
                    <ExternalLink class="h-4 w-4" />
                  </a>
                  <span v-if="comment.is_anonymous" class="text-xs bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 px-2 py-1 rounded">
                    Guest
                  </span>
                </div>
                <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                  <Clock class="h-3 w-3" />
                  {{ formatDate(comment.created_at) }}
                  <span v-if="!comment.is_approved" class="text-yellow-600 dark:text-yellow-400">
                    • Pending approval
                  </span>
                </div>
              </div>
            </div>

            <!-- Actions -->
            <Button
              v-if="comment.can_delete"
              variant="ghost"
              size="sm"
              @click="deleteComment(comment)"
              class="text-red-600 hover:text-red-700 hover:bg-red-50"
            >
              <Trash class="h-4 w-4" />
            </Button>
          </div>

          <!-- Comment Content -->
          <div class="prose prose-sm max-w-none dark:prose-invert">
            <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ comment.content }}</p>
          </div>

          <!-- Comment Actions -->
          <div class="flex items-center space-x-4 pt-2">
            <Button
              v-if="comment.can_reply"
              variant="ghost"
              size="sm"
              @click="toggleReply(comment.id)"
              class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100"
            >
              <Reply class="h-4 w-4 mr-1" />
              Reply
            </Button>
          </div>

          <!-- Reply Form -->
          <div v-if="replyingTo === comment.id" class="mt-4 p-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
            <h5 class="text-sm font-medium text-gray-900 dark:text-white mb-3">
              Reply to {{ comment.display_name }}
            </h5>

            <div class="space-y-4">
              <!-- ALWAYS show name/email fields for anonymous replies -->
              <div v-if="!isAuthenticated" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-2">
                  <Label>Name *</Label>
                  <Input
                    v-model="replyForm.author_name"
                    placeholder="Your name"
                    required
                  />
                </div>

                <div class="space-y-2">
                  <Label>Email *</Label>
                  <Input
                    v-model="replyForm.author_email"
                    type="email"
                    placeholder="your@email.com"
                    required
                  />
                </div>

                <div class="space-y-2 md:col-span-2">
                  <Label>Website (optional)</Label>
                  <Input
                    v-model="replyForm.author_website"
                    type="url"
                    placeholder="https://yourwebsite.com"
                  />
                </div>
              </div>

              <!-- Reply content -->
              <div class="space-y-2">
                <Textarea
                  v-model="replyForm.content"
                  placeholder="Write your reply..."
                  :rows="3"
                  class="resize-none"
                  required
                />
              </div>

              <!-- Notification preference for replies -->
              <div class="flex items-center space-x-2">
                <Checkbox
                  v-model:checked="replyForm.notify_on_reply"
                />
                <Label class="text-sm">
                  Notify me of replies via email
                </Label>
              </div>

              <!-- Reply actions -->
              <div class="flex space-x-2">
                <Button
                  :disabled="!isReplyFormValid || isSubmitting"
                  @click="submitReply"
                  size="sm"
                >
                  {{ isSubmitting ? 'Posting...' : 'Post Reply' }}
                </Button>

                <Button
                  variant="outline"
                  size="sm"
                  @click="toggleReply(null)"
                >
                  Cancel
                </Button>
              </div>
            </div>
          </div>

          <!-- Nested Replies -->
          <div v-if="comment.replies && comment.replies.length > 0" class="mt-4 space-y-4 pl-6 border-l-2 border-gray-200 dark:border-gray-700">
            <article
              v-for="reply in comment.replies"
              :key="reply.id"
              class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4"
            >
              <div class="flex items-start justify-between mb-3">
                <div class="flex items-center space-x-3">
                  <!-- Reply Avatar -->
                  <div class="h-8 w-8 rounded-full overflow-hidden bg-gray-100 dark:bg-gray-700 flex-shrink-0">
                    <img
                      v-if="reply.user?.avatar_url || reply.gravatar_url"
                      :src="reply.user?.avatar_url || reply.gravatar_url"
                      :alt="reply.display_name"
                      class="h-full w-full object-cover"
                    />
                    <div v-else class="h-full w-full flex items-center justify-center">
                      <User class="h-4 w-4 text-gray-400" />
                    </div>
                  </div>

                  <!-- Reply Author Info -->
                  <div>
                    <div class="flex items-center gap-2">
                      <h5 class="font-medium text-gray-900 dark:text-white text-sm">
                        {{ reply.display_name }}
                      </h5>
                      <a
                        v-if="reply.author_website"
                        :href="reply.author_website"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="text-ca-primary hover:text-ca-primary/80 transition-colors"
                      >
                        <ExternalLink class="h-3 w-3" />
                      </a>
                      <span v-if="reply.is_anonymous" class="text-xs bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 px-2 py-1 rounded">
                        Guest
                      </span>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                      <Clock class="h-3 w-3" />
                      {{ formatDate(reply.created_at) }}
                      <span v-if="!reply.is_approved" class="text-yellow-600 dark:text-yellow-400">
                        • Pending approval
                      </span>
                    </div>
                  </div>
                </div>

                <!-- Reply Actions -->
                <Button
                  v-if="reply.can_delete"
                  variant="ghost"
                  size="sm"
                  @click="deleteComment(reply)"
                  class="text-red-600 hover:text-red-700 hover:bg-red-50"
                >
                  <Trash class="h-3 w-3" />
                </Button>
              </div>

              <!-- Reply Content -->
              <div class="prose prose-sm max-w-none dark:prose-invert">
                <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap text-sm">{{ reply.content }}</p>
              </div>
            </article>
          </div>
        </div>
      </article>
    </div>
  </section>
</template>

<style scoped>
.prose p {
  margin: 0;
}
</style>
