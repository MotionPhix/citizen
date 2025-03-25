<script setup lang="ts">
import { ref, onMounted } from 'vue'

const sectionRef = ref<HTMLElement | null>(null)
const isVisible = ref(false)

onMounted(() => {
  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          isVisible.value = true
        }
      })
    },
    { threshold: 0.1 }
  )

  if (sectionRef.value) {
    observer.observe(sectionRef.value)
  }

  return () => {
    if (sectionRef.value) {
      observer.unobserve(sectionRef.value)
    }
  }
})
</script>

<template>
  <section
    ref="sectionRef"
    id="mission"
    class="py-24 bg-white dark:bg-gray-900"
  >
    <div class="container mx-auto px-4">
      <div class="grid md:grid-cols-2 gap-12">
        <div
          class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-10 transform hover:-translate-y-2 transition-all duration-300"
          :class="{ 'translate-x-enter': isVisible }"
        >
          <div class="w-16 h-16 bg-ca-primary/10 dark:bg-ca-primary/20 text-ca-primary rounded-xl flex items-center justify-center mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
              <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
          </div>

          <h2 class="text-2xl font-display font-bold text-gray-900 dark:text-white mb-4">Our Vision</h2>
          <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
            A transformed Malawi where citizens are empowered, actively engaged in governance, and their voices shape
            the decisions that affect their lives.
          </p>
        </div>

        <div
          class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-10 transform hover:-translate-y-2 transition-all duration-300"
          :class="{ 'translate-x-enter-delayed': isVisible }"
        >
          <div class="w-16 h-16 bg-ca-primary/10 dark:bg-ca-primary/20 text-ca-primary rounded-xl flex items-center justify-center mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3 3v1.5M3 21v-6m0 0l2.77-.693a9 9 0 016.208.682l.108.054a9 9 0 006.086.71l3.114-.732a48.524 48.524 0 01-.005-10.499l-3.11.732a9 9 0 01-6.085-.711l-.108-.054a9 9 0 00-6.208-.682L3 4.5M3 15V4.5" />
            </svg>
          </div>

          <h2 class="text-2xl font-display font-bold text-gray-900 dark:text-white mb-4">Our Mission</h2>
          <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
            To strengthen citizen participation in governance and development processes through advocacy, capacity
            building, and fostering meaningful dialogue between citizens and duty bearers.
          </p>
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped>
.translate-x-enter {
  opacity: 0;
  transform: translateX(40px);
  animation: slide-in 0.5s ease-out forwards;
}

.translate-x-enter-delayed {
  opacity: 0;
  transform: translateX(40px);
  animation: slide-in 0.5s ease-out 0.3s forwards;
}

@keyframes slide-in {
  to {
    opacity: 1;
    transform: translateX(0);
  }
}
</style>
