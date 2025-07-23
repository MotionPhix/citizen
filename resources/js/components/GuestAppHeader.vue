<script setup lang="ts">
import { ref, computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import AppLogo from './AppLogo.vue'
import ModeSwitch from './ModeSwitch.vue'
import { Button } from '@/components/ui/button'
import { Sheet, SheetContent, SheetHeader, SheetTitle, SheetTrigger } from '@/components/ui/sheet'
import { Menu, X } from 'lucide-vue-next'

interface NavLink {
  name: string
  url: string
  isActive: boolean
}

interface Props {
  links: NavLink[]
}

const props = defineProps<Props>()
const page = usePage()

const mobileMenuOpen = ref(false)

const isAuthenticated = computed(() => !!page.props.auth?.user)

const closeMobileMenu = () => {
  mobileMenuOpen.value = false
}
</script>

<template>
  <header class="sticky top-0 z-50 w-full border-b bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex h-16 items-center justify-between">
        <!-- Logo -->
        <div class="flex items-center">
          <Link :href="route('home')" class="flex items-center space-x-2">
            <AppLogo class="h-8 w-auto" />
          </Link>
        </div>

        <!-- Desktop Navigation -->
        <nav class="hidden md:flex items-center space-x-8">
          <Link
            v-for="link in links"
            :key="link.name"
            :href="link.url"
            :class="[
              'text-sm font-medium transition-colors hover:text-primary',
              link.isActive
                ? 'text-primary'
                : 'text-muted-foreground'
            ]"
          >
            {{ link.name }}
          </Link>
        </nav>

        <!-- Desktop Actions -->
        <div class="hidden md:flex items-center space-x-4">
          <ModeSwitch />

          <div v-if="!isAuthenticated" class="flex items-center space-x-2">
            <Button variant="ghost" size="sm" :href="route('login')">
              Sign In
            </Button>
            <Button size="sm" :href="route('register')">
              Get Started
            </Button>
          </div>

          <div v-else class="flex items-center space-x-2">
            <Button variant="ghost" size="sm" :href="route('dashboard')">
              Dashboard
            </Button>
          </div>
        </div>

        <!-- Mobile Menu Button -->
        <div class="flex items-center space-x-2 md:hidden">
          <ModeSwitch />

          <Sheet v-model:open="mobileMenuOpen">
            <SheetTrigger as-child>
              <Button variant="ghost" size="sm">
                <Menu class="h-5 w-5" />
                <span class="sr-only">Toggle menu</span>
              </Button>
            </SheetTrigger>
            <SheetContent side="right" class="w-[300px] sm:w-[400px]">
              <SheetHeader>
                <SheetTitle class="text-left">
                  <AppLogo class="h-8 w-auto" />
                </SheetTitle>
              </SheetHeader>

              <div class="mt-6 space-y-6">
                <!-- Mobile Navigation -->
                <nav class="space-y-4">
                  <Link
                    v-for="link in links"
                    :key="link.name"
                    :href="link.url"
                    @click="closeMobileMenu"
                    :class="[
                      'block text-lg font-medium transition-colors hover:text-primary',
                      link.isActive
                        ? 'text-primary'
                        : 'text-muted-foreground'
                    ]"
                  >
                    {{ link.name }}
                  </Link>
                </nav>

                <!-- Mobile Actions -->
                <div class="space-y-4 pt-6 border-t">
                  <div v-if="!isAuthenticated" class="space-y-2">
                    <Button variant="ghost" class="w-full justify-start" :href="route('login')" @click="closeMobileMenu">
                      Sign In
                    </Button>
                    <Button class="w-full" :href="route('register')" @click="closeMobileMenu">
                      Get Started
                    </Button>
                  </div>

                  <div v-else class="space-y-2">
                    <Button variant="ghost" class="w-full justify-start" :href="route('dashboard')" @click="closeMobileMenu">
                      Dashboard
                    </Button>
                  </div>
                </div>
              </div>
            </SheetContent>
          </Sheet>
        </div>
      </div>
    </div>
  </header>
</template>
