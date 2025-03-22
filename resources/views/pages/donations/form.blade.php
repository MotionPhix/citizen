<x-app-layout>
  <x-slot name="title">Donate to Citizen Alliance</x-slot>
  <x-slot name="description">
    Support Citizen Alliance by making a donation. Your contribution helps us empower communities and create lasting
    change.
  </x-slot>

  <!-- Hero Section -->
  <section class="bg-ca-primary text-white py-16">
    <x-content-container>
      <div class="text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-6">Support Our Cause</h1>
        <p class="text-lg md:text-xl mb-8">
          Your donation helps us continue our mission to empower communities through education, healthcare, and
          sustainable development.
        </p>
      </div>
    </x-content-container>
  </section>

  <!-- Donation Form Section -->
  <section class="py-16 bg-gray-50 dark:bg-ca-secondary">
    <x-content-container>
      <div class="max-w-2xl mx-auto">
        <h2 class="text-3xl font-bold text-ca-primary dark:text-white mb-8">Make a Donation</h2>
        <x-donation-form/>
      </div>
    </x-content-container>
  </section>

  <!-- Why Donate Section -->
  <section class="py-16 bg-white dark:bg-ca-primary max-w-2xl mx-auto">
    <div class="text-center">
      <h2 class="text-3xl font-bold text-ca-primary dark:text-white mb-6">Why Donate?</h2>
      <p class="text-lg text-gray-600 dark:text-gray-300 mb-8">
        Your contribution directly supports our programs and initiatives, making a real difference in the lives of
        those we serve.
      </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3">
      <div class="bg-gray-50 dark:bg-ca-secondary p-6 rounded-lg">
        <h3 class="text-xl font-semibold text-ca-primary dark:text-white mb-4">Empower Communities</h3>
        <p class="text-gray-600 dark:text-gray-300">
          Help us provide education, healthcare, and sustainable development programs to underserved communities.
        </p>
      </div>

      <div class="bg-gray-50 dark:bg-ca-secondary p-6 rounded-lg">
        <h3 class="text-xl font-semibold text-ca-primary dark:text-white mb-4">Create Lasting Change</h3>
        <p class="text-gray-600 dark:text-gray-300">
          Your donation supports long-term initiatives that address the root causes of poverty and inequality.
        </p>
      </div>

      <div class="bg-gray-50 dark:bg-ca-secondary p-6 rounded-lg">
        <h3 class="text-xl font-semibold text-ca-primary dark:text-white mb-4">Transparency & Accountability</h3>
        <p class="text-gray-600 dark:text-gray-300">
          We ensure that every donation is used effectively and transparently to maximize impact.
        </p>
      </div>
    </div>
  </section>
</x-app-layout>
