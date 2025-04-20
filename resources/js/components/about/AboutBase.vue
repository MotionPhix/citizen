<script setup lang="ts">
import { ref } from 'vue';
import { useIntersectionObserver, useMouseInElement } from '@vueuse/core';
import {
  Eye,
  Flag,
  Scale,
  HandIcon,
  Lightbulb,
  Loader2,
  ArrowRight,
  ArrowUpRight
} from 'lucide-vue-next';
import {
  IconSchool,
  IconHeart,
  IconUsers
} from '@tabler/icons-vue';
import { Button } from '@/components/ui/button';
import TimelineItem from './TimelineItem.vue'
import BoundingContainer from '@/components/BoundingContainer.vue';
import { provideTimeline } from './TimelineContext'
import TeamMember from '@/components/about/TeamMember.vue';
import Form from 'vform';
import {Input} from '@/components/ui/input'
import { toast } from 'vue-sonner';
const { activeIndex } = provideTimeline()

interface Value {
  icon: string;
  title: string;
  description: string;
}

interface TeamMember {
  name: string;
  position: string;
  bio: string;
  image: string;
  linkedin?: string;
  twitter?: string;
}

interface TimelineItem {
  year: string;
  title: string;
  description: string;
}

defineProps<{
  values: Value[]
  team: TeamMember[]
  timeline: TimelineItem[]
  subscriberCount: number
}>();

const heroSection = ref<HTMLElement | null>(null);
const missionSection = ref<HTMLElement | null>(null);
const valuesSection = ref<HTMLElement | null>(null);
const impactSection = ref<HTMLElement | null>(null);
const teamSection = ref<HTMLElement | null>(null);
const timelineSection = ref<HTMLElement | null>(null);
const ctaSection = ref<HTMLElement | null>(null);

const heroVisible = ref(false);
const missionVisible = ref(false);
const valuesVisible = ref(false);
const impactVisible = ref(false);
const teamVisible = ref(false);
const timelineVisible = ref(false);
const ctaVisible = ref(false);

// Use VueUse's intersection observer
useIntersectionObserver(heroSection, ([{ isIntersecting }]) => {
  heroVisible.value = isIntersecting;
});

useIntersectionObserver(missionSection, ([{ isIntersecting }]) => {
  missionVisible.value = isIntersecting;
});

useIntersectionObserver(valuesSection, ([{ isIntersecting }]) => {
  valuesVisible.value = isIntersecting;
});

useIntersectionObserver(impactSection, ([{ isIntersecting }]) => {
  impactVisible.value = isIntersecting;
});

useIntersectionObserver(teamSection, ([{ isIntersecting }]) => {
  teamVisible.value = isIntersecting;
});

useIntersectionObserver(timelineSection, ([{ isIntersecting }]) => {
  timelineVisible.value = isIntersecting;
});

useIntersectionObserver(ctaSection, ([{ isIntersecting }]) => {
  ctaVisible.value = isIntersecting;
});

const form = new Form<{ email: string, name?: string }>({
  email: '',
  name: ''
})

const achievements = [
  {
    icon: IconSchool,
    title: 'Education',
    description: '32 schools supported with resources and training programs'
  },
  {
    icon: IconHeart,
    title: 'Healthcare',
    description: '85 medical camps organized in rural communities'
  },
  {
    icon: IconUsers,
    title: 'Community',
    description: '450+ training sessions conducted for local leaders'
  }
];

// Mouse parallax effect using VueUse
const { elementX, elementY } = useMouseInElement(heroSection);

const handleSubmit = async () => {
  try {
    await form.post(route('newsletter.subscribe'))

    toast.success('Thank you for subscribing!', {
      description: 'You will receive our next newsletter in your inbox.'
    })

    form.reset()
  } catch (e) {
    console.log(e);

    toast.error('Subscription failed', {
      description: 'Please try again or contact support if the problem persists.'
    })
  }
}
</script>

<template>
  <!-- Hero Section -->
  <section
    ref="heroSection"
    class="relative min-h-[600px] flex items-center bg-gradient-to-br from-ca-primary to-ca-highlight dark:from-gray-900 dark:to-ca-primary/50 overflow-hidden">
    <div class="absolute inset-0 overflow-hidden">
      <div
        class="absolute inset-0 bg-[url('/images/pattern.svg')] opacity-10 dark:opacity-20"
        :style="{
          transform: `translate(${elementX / 20}px, ${elementY / 20}px)`
        }"
      />
    </div>

    <div class="container mx-auto px-4 relative pt-32 pb-20">
      <BoundingContainer>
        <div
          class="max-w-2xl text-left space-y-6"
          :class="{ 'opacity-0': !heroVisible, 'opacity-100 translate-y-0': heroVisible }">
          <h1 class="text-4xl md:text-6xl font-display font-bold text-white leading-tight">
            Empowering Citizens for Lasting Change in Malawi
          </h1>

          <p class="text-xl md:text-2xl text-white/90">
            Since 2012, we've been at the forefront of strengthening citizen participation in governance and fostering
            sustainable development across Malawi.
          </p>
        </div>
      </BoundingContainer>
    </div>
  </section>

  <!-- Mission & Vision Section -->
  <section
    id="mission"
    ref="missionSection"
    class="py-24 bg-white dark:bg-gray-900">
    <BoundingContainer>
      <div class="grid md:grid-cols-2 gap-12">
        <div
          class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-10 transform hover:-translate-y-2 transition-all duration-300"
          :class="{ 'opacity-0 translate-x-10': !missionVisible, 'opacity-100 translate-x-0': missionVisible }">
          <div
            class="w-16 h-16 bg-ca-primary/10 dark:bg-ca-primary/20 text-ca-primary rounded-xl flex items-center justify-center mb-6">
            <Eye class="w-8 h-8" />
          </div>

          <h2 class="text-2xl font-display font-bold text-gray-900 dark:text-white mb-4">Our Vision</h2>

          <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
            A transformed Malawi where citizens are empowered, actively engaged in governance, and their voices shape
            the decisions that affect their lives.
          </p>
        </div>

        <div
          class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-10 transform hover:-translate-y-2 transition-all duration-300"
          :class="{ 'opacity-0 translate-x-10': !missionVisible, 'opacity-100 translate-x-0': missionVisible }"
          style="transition-delay: 300ms"
        >
          <div
            class="w-16 h-16 bg-ca-primary/10 dark:bg-ca-primary/20 text-ca-primary rounded-xl flex items-center justify-center mb-6">
            <Flag class="w-8 h-8" />
          </div>

          <h2 class="text-2xl font-display font-bold text-gray-900 dark:text-white mb-4">Our Mission</h2>

          <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
            To strengthen citizen participation in governance and development processes through advocacy, capacity
            building, and fostering meaningful dialogue between citizens and duty bearers.
          </p>
        </div>
      </div>
    </BoundingContainer>
  </section>

  <!-- Values Section -->
  <section ref="valuesSection" class="py-24 bg-gray-50 dark:bg-gray-800/50">
    <BoundingContainer>
      <div class="text-center max-w-3xl mx-auto mb-16">
        <h2
          class="text-3xl md:text-4xl font-display font-bold text-gray-900 dark:text-white mb-6"
          :class="{ 'opacity-0 translate-y-10': !valuesVisible, 'opacity-100 translate-y-0': valuesVisible }"
        >
          Our Core Values
        </h2>
        <p
          class="text-xl text-gray-600 dark:text-gray-300"
          :class="{ 'opacity-0 translate-y-10': !valuesVisible, 'opacity-100 translate-y-0': valuesVisible }"
        >
          The principles that guide our work and shape our approach to community development.
        </p>
      </div>

      <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
        <div
          v-for="(value, index) in values"
          :key="index"
          class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8 transform hover:-translate-y-2 transition-all duration-300"
          :class="{ 'opacity-0 translate-y-10': !valuesVisible, 'opacity-100 translate-y-0': valuesVisible }"
          :style="{ transitionDelay: `${index * 150}ms` }"
        >
          <div
            class="w-14 h-14 bg-ca-primary/10 dark:bg-ca-primary/20 text-ca-primary rounded-xl flex items-center justify-center mb-6">
            <component
              :is="value.icon === 'scale' ? Scale : value.icon === 'hand' ? HandIcon : Lightbulb"
              class="w-7 h-7"
            />
          </div>

          <h3 class="text-xl font-display font-semibold text-gray-900 dark:text-white mb-3">
            {{ value.title }}
          </h3>
          <p class="text-gray-600 dark:text-gray-300">
            {{ value.description }}
          </p>
        </div>
      </div>
    </BoundingContainer>
  </section>

  <!-- Impact Section -->
  <slot name="impact" />

  <!-- Team Section -->
  <section ref="teamSection" class="py-24 bg-gray-50 dark:bg-gray-800/50">
    <BoundingContainer>
      <div class="text-center max-w-3xl mx-auto mb-16">
        <h2
          class="text-3xl md:text-4xl font-display font-bold text-gray-900 dark:text-white mb-6"
          :class="{ 'opacity-0 translate-y-10': !teamVisible, 'opacity-100 translate-y-0': teamVisible }"
        >
          Meet Our Board Members
        </h2>
        <p
          class="text-xl text-gray-600 dark:text-gray-300"
          :class="{ 'opacity-0 translate-y-10': !teamVisible, 'opacity-100 translate-y-0': teamVisible }"
        >
          Dedicated professionals committed to driving positive change in our communities.
        </p>
      </div>

      <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="(member, index) in team"
          :key="member.name"
          :class="{ 'opacity-0 translate-y-10': !teamVisible, 'opacity-100 translate-y-0': teamVisible }"
          :style="{ transitionDelay: `${index * 200}ms` }"
        >
          <TeamMember :member="member" />
        </div>
      </div>
    </BoundingContainer>
  </section>

  <!-- Timeline Section -->
  <section ref="timelineSection" class="py-24 bg-white dark:bg-gray-900">
    <BoundingContainer>
      <div class="text-center max-w-3xl mx-auto mb-16">
        <h2
          class="text-3xl md:text-4xl font-display font-bold text-gray-900 dark:text-white mb-6"
          :class="{ 'opacity-0 translate-y-10': !timelineVisible, 'opacity-100 translate-y-0': timelineVisible }"
        >
          Our Journey
        </h2>
        <p
          class="text-xl text-gray-600 dark:text-gray-300"
          :class="{ 'opacity-0 translate-y-10': !timelineVisible, 'opacity-100 translate-y-0': timelineVisible }"
        >
          A decade of growth, impact, and community transformation.
        </p>
      </div>

      <div
        class="max-w-4xl mx-auto"
        :class="{ 'opacity-0': !timelineVisible, 'opacity-100': timelineVisible }"
      >
        <TransitionGroup
          tag="div"
          enter-active-class="transition-all duration-500 ease-out"
          enter-from-class="opacity-0 translate-y-10"
          enter-to-class="opacity-100 translate-y-0"
          leave-active-class="transition-all duration-500 ease-out"
          leave-from-class="opacity-100 translate-y-0"
          leave-to-class="opacity-0 -translate-y-10"
        >
          <TimelineItem
            v-for="(item, index) in timeline"
            :key="item.year"
            v-bind="item"
            :index="index"
          />
        </TransitionGroup>
      </div>
    </BoundingContainer>
  </section>

  <!-- Call to Action Section -->
  <section
    ref="ctaSection"
    class="relative bg-gradient-to-br from-ca-primary to-ca-highlight dark:from-gray-900 dark:via-gray-800 dark:to-gray-700 overflow-hidden"
  >
    <!-- Subtle pattern overlay -->
    <div
      class="absolute inset-0 bg-[url('/images/pattern.svg')] opacity-10 dark:opacity-20"
      :style="{
        transform: `translate(${elementX / 20}px, ${elementY / 20}px)`
      }"
    />

    <div class="relative py-24">
      <div class="container mx-auto px-4">
        <div
          class="max-w-4xl mx-auto"
          :class="{ 'opacity-0 translate-y-6': !ctaVisible, 'opacity-100 translate-y-0': ctaVisible }"
        >
          <div class="text-center mb-8">
            <h2 class="text-3xl md:text-4xl font-display font-bold text-white mb-4">
              Join Our Newsletter
            </h2>
            <p class="text-xl text-white/90">
              Stay informed about our initiatives and make a difference in Malawi's development journey
            </p>
          </div>

          <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl p-6 md:p-8">
            <form @submit.prevent="handleSubmit" class="flex flex-col md:flex-row gap-4">
              <Input
                v-model="form.name"
                placeholder="Enter your full name"
                class="flex-1 h-12 bg-white/90 dark:bg-gray-900/90 border-0 focus:ring-2 focus:ring-white/30 dark:focus:ring-gray-700/50"
              />

              <Input
                v-model="form.email"
                type="email"
                required
                placeholder="Enter your email address"
                class="flex-1 h-12 bg-white/90 dark:bg-gray-900/90 border-0 focus:ring-2 focus:ring-white/30 dark:focus:ring-gray-700/50"
              />

              <Button
                type="submit"
                :disabled="form.busy"
                class="md:w-auto h-12 px-8 bg-white hover:bg-gray-100 text-ca-primary hover:text-ca-primary/90 dark:bg-gray-900 dark:hover:bg-gray-800 dark:text-white"
              >
                <Loader2
                  v-if="form.busy"
                  class="w-4 h-4 mr-2 animate-spin"
                />
                {{ form.busy ? 'Subscribing...' : 'Subscribe Now' }}
              </Button>
            </form>

            <p class="mt-4 text-sm text-white/80 text-center">
              Join {{ subscriberCount.toLocaleString() }}+ members who receive our updates. No spam, unsubscribe anytime.
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped>
.opacity-0 {
  opacity: 0;
}

.opacity-100 {
  opacity: 1;
}

.translate-y-10 {
  transform: translateY(2.5rem);
}

.translate-y-0 {
  transform: translateY(0);
}

.translate-x-10 {
  transform: translateX(2.5rem);
}

.translate-x-0 {
  transform: translateX(0);
}

[class*="translate"] {
  transition-property: all;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 500ms;
}
</style>
