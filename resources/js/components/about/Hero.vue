<script setup lang="ts">
import { onMounted, ref } from 'vue';
import BoundingContainer from '@/components/BoundingContainer.vue';

const patternRef = ref<HTMLElement | null>(null);

onMounted(() => {
  const handleMouseMove = (e: MouseEvent) => {
    if (!patternRef.value) return;

    const { clientX, clientY } = e;
    const xPos = (clientX / window.innerWidth - 0.5) * 20;
    const yPos = (clientY / window.innerHeight - 0.5) * 20;

    patternRef.value.style.transform = `translate(${xPos}px, ${yPos}px)`;
  };

  window.addEventListener('mousemove', handleMouseMove);

  return () => {
    window.removeEventListener('mousemove', handleMouseMove);
  };
});
</script>

<template>
  <section
    class="relative min-h-[600px] flex items-center bg-gradient-to-br from-ca-primary to-ca-highlight dark:from-gray-900 dark:to-ca-primary/50 overflow-hidden">
    <div class="absolute inset-0 overflow-hidden">
      <div
        ref="patternRef"
        class="absolute inset-0 bg-[url('/images/pattern.svg')] opacity-10 dark:opacity-20"
      />
    </div>

    <div class="mx-auto px-4 relative pt-32 pb-20">
      <BoundingContainer>
        <div class="max-w-2xl text-left">
          <div class="space-y-6">
            <h1 class="text-4xl md:text-6xl font-display font-bold text-white leading-tight">
              Empowering Citizens for Lasting Change in Malawi
            </h1>

            <p class="text-xl md:text-2xl text-white/90">
              Since 2012, we've been at the forefront of strengthening citizen participation in governance and fostering
              sustainable development across Malawi.
            </p>
          </div>
        </div>
      </BoundingContainer>
    </div>
  </section>
</template>
