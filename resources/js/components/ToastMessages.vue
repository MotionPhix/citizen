<script setup lang="ts">
import { useEventBus } from '@vueuse/core';
import { ref } from 'vue';
import { Check, X, AlertCircle } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';

interface Toast {
  id: number;
  type: 'success' | 'error';
  message: string;
}

const toasts = ref<Toast[]>([]);
const toast = useEventBus('toast');

let counter = 0;

toast.on('add', (message: { type: 'success' | 'error'; message: string }) => {
  const id = counter++;
  toasts.value.push({ ...message, id });

  // Remove toast after 5 seconds
  setTimeout(() => {
    removeToast(id);
  }, 5000);
});

const removeToast = (id: number) => {
  const index = toasts.value.findIndex(t => t.id === id);
  if (index > -1) {
    toasts.value.splice(index, 1);
  }
};
</script>

<template>
  <div
    class="fixed bottom-0 right-0 z-50 p-4 space-y-4"
    style="max-height: 100vh; overflow-y: auto;"
  >
    <transition-group name="toast">
      <div
        v-for="item in toasts"
        :key="item.id"
        :class="[
          'flex items-center gap-3 p-4 rounded-lg shadow-lg min-w-[300px] max-w-[500px]',
          item.type === 'success' ? 'bg-green-50 dark:bg-green-900/50' : 'bg-red-50 dark:bg-red-900/50'
        ]"
      >
        <div
          :class="[
            'flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-full',
            item.type === 'success' ? 'bg-green-100 dark:bg-green-800' : 'bg-red-100 dark:bg-red-800'
          ]"
        >
          <Check
            v-if="item.type === 'success'"
            class="w-5 h-5 text-green-600 dark:text-green-300"
          />
          <AlertCircle
            v-else
            class="w-5 h-5 text-red-600 dark:text-red-300"
          />
        </div>

        <div class="flex-1">
          <p
            :class="[
              'text-sm font-medium',
              item.type === 'success' ? 'text-green-800 dark:text-green-200' : 'text-red-800 dark:text-red-200'
            ]"
          >
            {{ item.message }}
          </p>
        </div>

        <Button
          variant="ghost"
          size="sm"
          @click="removeToast(item.id)"
          :class="[
            'flex-shrink-0',
            item.type === 'success' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'
          ]"
        >
          <X class="w-4 h-4" />
        </Button>
      </div>
    </transition-group>
  </div>
</template>

<style scoped>
.toast-enter-active,
.toast-leave-active {
  transition: all 0.3s ease;
}

.toast-enter-from {
  opacity: 0;
  transform: translateX(30px);
}

.toast-leave-to {
  opacity: 0;
  transform: translateX(30px);
}
</style>
