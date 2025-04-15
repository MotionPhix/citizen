<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

const props = defineProps<{
  mode: 'like' | 'comment';
}>();

const dialog = ref(false);
const isLoginMode = ref(true);

const form = useForm({
  email: '',
  password: '',
  name: '',
  password_confirmation: '',
});

const emit = defineEmits<{
  (e: 'authenticated'): void;
}>();

const switchMode = () => {
  isLoginMode.value = !isLoginMode.value;
  form.reset();
};

const submit = async () => {
  const route = isLoginMode.value ? 'login' : 'register';

  form.post(route, {
    onSuccess: () => {
      dialog.value = false;
      emit('authenticated');
    },
    onFinish: () => {
      if (!form.hasErrors) {
        form.reset();
      }
    },
  });
};
</script>

<template>
  <Dialog v-model:open="dialog">
    <DialogTrigger asChild>
      <slot />
    </DialogTrigger>
    <DialogContent class="sm:max-w-[425px]">
      <DialogHeader>
        <DialogTitle>
          {{ isLoginMode ? 'Log in to continue' : 'Create an account' }}
        </DialogTitle>
        <DialogDescription>
          {{ mode === 'like'
          ? 'Sign in to like this post'
          : 'Sign in to join the discussion' }}
        </DialogDescription>
      </DialogHeader>

      <form @submit.prevent="submit" class="space-y-6">
        <div v-if="!isLoginMode" class="space-y-2">
          <Label for="name">Name</Label>
          <Input
            id="name"
            v-model="form.name"
            placeholder="Your name"
            :error="form.errors.name"
          />
        </div>

        <div class="space-y-2">
          <Label for="email">Email</Label>
          <Input
            id="email"
            type="email"
            v-model="form.email"
            placeholder="your@email.com"
            :error="form.errors.email"
          />
        </div>

        <div class="space-y-2">
          <Label for="password">Password</Label>
          <Input
            id="password"
            type="password"
            v-model="form.password"
            :error="form.errors.password"
          />
        </div>

        <div v-if="!isLoginMode" class="space-y-2">
          <Label for="password_confirmation">Confirm Password</Label>
          <Input
            id="password_confirmation"
            type="password"
            v-model="form.password_confirmation"
            :error="form.errors.password_confirmation"
          />
        </div>

        <DialogFooter>
          <Button type="submit" class="w-full" :disabled="form.processing">
            <LoaderCircle v-if="form.processing" class="mr-2 h-4 w-4 animate-spin" />
            {{ isLoginMode ? 'Log in' : 'Create account' }}
          </Button>

          <div class="mt-4 text-center text-sm text-muted-foreground">
            {{ isLoginMode ? "Don't have an account?" : 'Already have an account?' }}
            <button
              type="button"
              class="ml-1 text-primary hover:underline"
              @click="switchMode"
            >
              {{ isLoginMode ? 'Sign up' : 'Log in' }}
            </button>
          </div>
        </DialogFooter>
      </form>
    </DialogContent>
  </Dialog>
</template>
