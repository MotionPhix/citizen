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
    <x-content-container class="py-16">
      <div class="max-w-2xl mx-auto">
        <h2 class="text-3xl font-bold text-center mb-8">Send Us a Message</h2>

        @if (session('success'))
          <div class="mb-8 rounded-lg bg-green-100 p-4 text-green-700 dark:bg-green-800/30 dark:text-green-400">
            {{ session('success') }}
          </div>
        @endif

        @if (session('error'))
          <div class="mb-8 rounded-lg bg-red-100 p-4 text-red-700 dark:bg-red-800/30 dark:text-red-400">
            {{ session('error') }}
          </div>
        @endif

        <form action="{{ route('contact.submit') }}" method="POST" class="space-y-6">
          @csrf

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
              <input type="text" id="name" name="name" value="{{ old('name') }}" required
                     class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-ca-highlight focus:ring-ca-highlight dark:bg-gray-800 dark:border-gray-700 dark:text-white @error('name') border-red-500 @enderror">
              @error('name')
              <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
              @enderror
            </div>

            <div>
              <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
              <input type="email" id="email" name="email" value="{{ old('email') }}" required
                     class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-ca-highlight focus:ring-ca-highlight dark:bg-gray-800 dark:border-gray-700 dark:text-white @error('email') border-red-500 @enderror">
              @error('email')
              <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
              @enderror
            </div>
          </div>

          <div>
            <label for="subject" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Subject</label>
            <input type="text" id="subject" name="subject" value="{{ old('subject') }}" required
                   class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-ca-highlight focus:ring-ca-highlight dark:bg-gray-800 dark:border-gray-700 dark:text-white @error('subject') border-red-500 @enderror">
            @error('subject')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Message</label>
            <textarea id="message" name="message" rows="5" required
                      class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-ca-highlight focus:ring-ca-highlight dark:bg-gray-800 dark:border-gray-700 dark:text-white @error('message') border-red-500 @enderror">{{ old('message') }}</textarea>
            @error('message')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
          </div>

          {{-- hCaptcha Integration --}}
          {!! HCaptcha::renderJs() !!}

          {!! HCaptcha::display() !!}

          @error('h-captcha-response')
          <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
          @enderror

          <div>
            <button type="submit"
                    class="inline-flex items-center px-6 py-3 bg-ca-highlight text-white rounded-lg hover:bg-ca-highlight/90 transition-colors duration-300">
              Send Message
              <x-heroicon-o-paper-airplane class="w-5 h-5 ml-2" />
            </button>
          </div>
        </form>
      </div>
    </x-content-container>
  </div>

  <!-- Map Section -->
  <div class="w-full h-[600px] relative" x-data>
    <div id="map" class="w-full h-full" x-init="initContactMap()"></div>
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
