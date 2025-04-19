<x-app-layout>
  <x-slot name="title">Contact Us</x-slot>
  <x-slot name="description">Get in touch with Citizen Alliance for inquiries, collaborations, or support.</x-slot>

  <!-- Hero Section -->
  <section class="relative">
    <div class="relative w-full h-[400px] overflow-hidden bg-gradient-to-br from-ca-primary to-ca-highlight p-4 text-center">
      <div class="absolute inset-0 bg-pattern opacity-10"></div>
      <div class="absolute inset-0 flex items-center">
        <div class="container mx-auto px-4 text-center">
          <h1 class="text-4xl md:text-6xl font-bold text-white mb-4">Contact Us</h1>
          <p class="text-xl md:text-2xl text-white/90 max-w-2xl mx-auto">
            We'd love to hear from you! Reach out for inquiries, collaborations, or support.
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- Contact Information Section -->
  <x-content-container class="py-16">
    <h2 class="text-3xl font-bold text-center mb-8">Get in Touch</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      <!-- Address -->
      <div class="text-center">
        <div class="w-16 h-16 bg-ca-primary text-white rounded-lg flex items-center justify-center mx-auto mb-6">
          <x-heroicon-o-map-pin class="w-8 h-8" />
        </div>
        <h3 class="text-xl font-semibold mb-2">Address</h3>
        <p class="text-gray-600 dark:text-gray-400">Area 47/2/113, Lilongwe Street, P.O. Box 619, Lilongwe, Malawi</p>
      </div>

      <!-- Phone -->
      <div class="text-center">
        <div class="w-16 h-16 bg-ca-primary text-white rounded-lg flex items-center justify-center mx-auto mb-6">
          <x-heroicon-o-phone class="w-8 h-8" />
        </div>
        <h3 class="text-xl font-semibold mb-2">Phone</h3>
        <p class="text-gray-600 dark:text-gray-400">
          <a href="tel:+265991602233" class="hover:text-ca-highlight transition-colors">
            +265 (0) 991 602 233
          </a>
        </p>
      </div>

      <!-- Email -->
      <div class="text-center">
        <div class="w-16 h-16 bg-ca-primary text-white rounded-lg flex items-center justify-center mx-auto mb-6">
          <x-heroicon-o-envelope class="w-8 h-8" />
        </div>
        <h3 class="text-xl font-semibold mb-2">Email</h3>
        <p class="text-gray-600 dark:text-gray-400">
          <a href="mailto:citizenalliance12@gmail.com" class="hover:text-ca-highlight transition-colors">
            citizenalliance12@gmail.com
          </a>
        </p>
      </div>
    </div>
  </x-content-container>

  <!-- Contact Form Section -->
  <div class="bg-gray-50 dark:bg-gray-900">

    <!-- Contact Form Section -->
    <div class="bg-gray-50 dark:bg-gray-900">

      <x-content-container class="py-16">

        <div class="max-w-2xl mx-auto">
          <h2 class="text-3xl font-bold text-center mb-8">Send Us a Message</h2>
          <contact-form></contact-form>
        </div>

      </x-content-container>

    </div>

  </div>

  <!-- Map Section -->
  <div class="w-full h-[600px] relative">
    <leaflet-map></leaflet-map>
  </div>

  <!-- Call to Action Section -->
  <section class="py-16 bg-ca-primary text-white relative overflow-hidden">
    <div class="absolute inset-0 bg-pattern opacity-10"></div>
    <div class="container mx-auto px-4 relative">
      <div class="text-center max-w-3xl mx-auto">
        <h2 class="text-3xl md:text-4xl font-bold mb-6">Partner With Us</h2>
        <p class="text-xl mb-8">
          Join us in our mission to empower citizens and drive sustainable development in Malawi.
        </p>
        <a href="#contact-form"
           class="inline-flex items-center px-8 py-3 bg-white text-ca-primary rounded-lg hover:bg-gray-100 transition-colors duration-300">
          Get in Touch
          <x-heroicon-o-arrow-right class="w-5 h-5 ml-2" />
        </a>
      </div>
    </div>
  </section>
</x-app-layout>
