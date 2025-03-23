<div
  v-scope="impactCard()"
  v-cloak
  class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 transform transition-transform duration-300 hover:-translate-y-2"
>
  <div class="flex items-center justify-center mb-4">
    <div class="w-12 h-12 bg-ca-primary rounded-lg flex items-center justify-center">
      <i :class="icon" class="text-white text-2xl"></i>
    </div>
  </div>
  <h3 class="text-xl font-semibold text-center mb-2 dark:text-white">{{ title }}</h3>
  <div class="text-center text-gray-600 dark:text-gray-300">
    <x-counter-animation :end="metric" />
    <p class="mt-2">{{ description }}</p>
  </div>
</div>
