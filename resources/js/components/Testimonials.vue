<script setup lang="ts">
import ContentContainer from '@/components/ContentContainer.vue'

interface Testimonial {
  name: string
  role: string
  avatar: string
  quote: string
}

interface Props {
  testimonials: Testimonial[]
}

defineProps<Props>()
</script>

<template>
  <section class="py-16 bg-gray-50 dark:bg-ca-secondary rounded-2xl">
    <ContentContainer>
<!--      <h2 class="text-4xl md:text-5xl font-display font-bold text-center mb-12 text-ca-primary dark:text-gray-200">-->
<!--        What People Say About Us-->
<!--      </h2>-->

      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div v-for="testimonial in testimonials" :key="testimonial.name" class="flex h-auto">
          <div class="flex flex-col bg-white rounded-xl dark:bg-neutral-900">
            <div class="flex-auto p-4 md:p-6">
              <p class="text-base italic md:text-lg text-gray-800 dark:text-neutral-200">
                " {{ testimonial.quote }} "
              </p>
            </div>

            <div class="p-4 bg-gray-100 rounded-b-xl md:px-7 dark:bg-neutral-800">
              <div class="flex items-center gap-x-3">
                <div class="shrink-0">
                  <img
                    class="size-8 sm:h-[2.875rem] sm:w-[2.875rem] rounded-lg object-cover"
                    :src="testimonial.avatar || '/images/default-avatar.png'"
                    :alt="`${testimonial.name}'s avatar`"
                  >
                </div>

                <div class="grow">
                  <p class="text-sm sm:text-base font-semibold text-gray-800 dark:text-neutral-200">
                    {{ testimonial.name }}
                  </p>
                  <p class="text-xs text-gray-500 dark:text-neutral-400">
                    {{ testimonial.role }}
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </ContentContainer>
  </section>
</template>
