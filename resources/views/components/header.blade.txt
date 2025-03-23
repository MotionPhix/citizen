<header x-data="{ isOpen: false }" class="bg-white dark:bg-gray-900 shadow-sm">
  <!-- Top Bar -->
  <div class="bg-blue-600 dark:bg-blue-800">
    <div class="container mx-auto px-4 py-2">
      <div class="flex flex-col sm:flex-row sm:justify-end sm:space-x-4 text-white text-sm">
        <a href="tel:+265991602233" class="flex items-center hover:text-blue-200">
          <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path
              d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
          </svg>
          +265 (0) 991 602 233
        </a>

        <a href="mailto:enquiries@citizenalliancemw.org" class="flex items-center hover:text-blue-200">
          <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
          </svg>
          enquiries@citizenalliancemw.org
        </a>
      </div>
    </div>
  </div>

  <!-- Main Navigation -->
  <nav class="container mx-auto px-4 !sticky top-0 z-50 shadow-sm">
    <div class="flex justify-between items-center py-2">
      <a href="{{ route('home') }}" class="flex-shrink-0">
        <img src="{{ asset('images/logo.png') }}" alt="Citizen Alliance" class="h-12">
      </a>

      <!-- Desktop Navigation -->
      <div class="hidden lg:flex items-center space-x-8">
        <x-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')">
          Home
        </x-nav-link>

        <x-nav-link href="{{ route('about') }}" :active="request()->routeIs('about')">
          Organisation
        </x-nav-link>

        <x-nav-link href="{{ route('projects.index') }}" :active="request()->routeIs('projects.*')">
          Projects
        </x-nav-link>

        <x-nav-link href="{{ route('contact.index') }}" :active="request()->routeIs('contact.*')">
          Contact Us
        </x-nav-link>

        <x-nav-link href="{{ route('blogs.index') }}" :active="request()->routeIs('blogs.*')">
          Blogs
        </x-nav-link>

        <x-mode-switch />
      </div>

      <!-- Mobile menu button -->
      <button @click="isOpen = !isOpen" class="lg:hidden">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path x-show="!isOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16"/>
          <path x-show="isOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>

    <!-- Mobile Navigation -->
    <div x-show="isOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         class="lg:hidden"
         style="display: none;">
      <div class="py-4 space-y-2">
        <x-mobile-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')">
          Home
        </x-mobile-nav-link>

        <x-mobile-nav-link href="{{ route('about') }}" :active="request()->routeIs('about')">
          Organisation
        </x-mobile-nav-link>

        <x-mobile-nav-link
          href="{{ route('projects.index') }}"
          :active="request()->routeIs('projects.*')">
          Projects
        </x-mobile-nav-link>

        <x-mobile-nav-link
          href="{{ route('contact.index') }}"
          :active="request()->routeIs('contact')">
          Contact Us
        </x-mobile-nav-link>

        <x-mobile-nav-link
          href="{{ route('blogs.index') }}"
          :active="request()->routeIs('blogs.*')">
          Blogs
        </x-mobile-nav-link>

        <!-- Add the mode-switch component here for mobile -->
        <div class="px-4 py-2">
          <x-mode-switch />
        </div>
      </div>
    </div>
  </nav>
</header>
