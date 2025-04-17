<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { useToast } from '@/composables/useToast';

const emit = defineEmits(['uploaded']);
const { showToast } = useToast();
const isUploading = ref(false);

const uploadAvatar = async (event) => {
  const file = event.target.files[0];
  if (!file) return;

  const formData = new FormData();
  formData.append('avatar', file);

  try {
    isUploading.value = true;
    const response = await axios.post('/profile/avatar', formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    });

    showToast({
      type: 'success',
      message: 'Avatar uploaded successfully',
    });

    emit('uploaded', response.data.avatar_url);
  } catch (error) {
    showToast({
      type: 'error',
      message: error.response?.data?.message || 'Failed to upload avatar',
    });
  } finally {
    isUploading.value = false;
  }
};
</script>

<template>
  <div>
    <input
      type="file"
      accept="image/*"
      class="hidden"
      ref="fileInput"
      @change="uploadAvatar"
    />
    <button
      type="button"
      :disabled="isUploading"
      @click="$refs.fileInput.click()"
      class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 disabled:opacity-50"
    >
      {{ isUploading ? 'Uploading...' : 'Upload Avatar' }}
    </button>
  </div>
</template>
