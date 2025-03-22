<footer class="bg-gray-900 text-gray-300">
  <!-- Footer Top -->
  <x-content-container class="py-16">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
      <!-- About Column -->
      <div>
        <h3 class="text-white text-lg font-semibold mb-6 font-display">About Us</h3>
        <img src="{{ asset('images/logo-white.png') }}" alt="Citizen Alliance" class="h-12 mb-4">
        <p class="text-sm leading-relaxed mb-6">
          A coalition of civil society organizations and citizen groups established in 2012 as a citizen-led engagement initiative on development and governance processes.
        </p>

        <div class="flex space-x-4">
          <x-social-icons :links="[
              'facebook' => '#',
              'twitter' => '#',
              'linkedin' => '#',
              'instagram' => '#'
            ]" class="text-gray-400 hover:text-white transition-colors duration-300"
          />
        </div>
      </div>

      <!-- Quick Links -->
      <div>
        <h3 class="text-white text-lg font-semibold mb-6 font-display">Quick Links</h3>
        <ul class="space-y-4">
          <li><a href="{{ route('home') }}" class="text-gray-400 hover:text-white transition-colors duration-300">Home</a></li>
          <li><a href="{{ route('about') }}" class="text-gray-400 hover:text-white transition-colors duration-300">Organisation</a></li>
          <li><a href="{{ route('projects.index') }}" class="text-gray-400 hover:text-white transition-colors duration-300">Projects</a></li>
          <li><a href="{{ route('contact.index') }}" class="text-gray-400 hover:text-white transition-colors duration-300">Contact Us</a></li>
          <li><a href="{{ route('donation.form') }}" class="text-gray-400 hover:text-white transition-colors duration-300">Donate</a></li>
        </ul>
      </div>

      <!-- Office Hours -->
      <div>
        <h3 class="text-white text-lg font-semibold mb-6 font-display">Office Hours</h3>
        <ul class="space-y-4">
          <li class="grid">
            <span class="text-gray-400">Monday - Friday</span>
            <span class="text-white">8:00 AM - 4:00 PM</span>
          </li>

          <li class="grid">
            <span class="text-gray-400">Saturday & Sunday</span>
            <span class="text-white">Closed</span>
          </li>
        </ul>
      </div>

      <!-- Newsletter -->
      <div>
        <h3 class="text-white text-lg font-semibold mb-6 font-display">Newsletter</h3>
        <p class="text-sm text-gray-400 mb-4">
          Subscribe to our newsletter to get all our news in your inbox. Stay connected with our latest updates.
        </p>
        <form x-data="{
            email: '',
            loading: false,
            success: false,
            submit() {
                this.loading = true;
                // Add newsletter subscription logic here
                setTimeout(() => {
                    this.loading = false;
                    this.success = true;
                    this.email = '';
                }, 1000);
            }
        }" @submit.prevent="submit" class="relative">
          <div class="flex">
            <input type="email"
                   x-model="email"
                   required
                   class="w-full px-4 py-2 bg-gray-800 text-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                   placeholder="Email Address">
            <button type="submit"
                    :disabled="loading"
                    class="px-4 py-2 bg-blue-600 text-white rounded-r-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50">
              <span x-show="!loading">
                Subscribe
              </span>
              <span x-show="loading" class="flex items-center">
                <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
              </span>
            </button>
          </div>
          <div x-show="success"
               x-transition:enter="transition ease-out duration-300"
               x-transition:enter-start="opacity-0 transform translate-y-2"
               x-transition:enter-end="opacity-100 transform translate-y-0"
               class="absolute mt-2 text-sm text-green-400">
            Thank you for subscribing!
          </div>
        </form>
      </div>
    </div>
  </x-content-container>

  <!-- Copyright -->
  <div class="border-t border-gray-800">
    <x-content-container class="py-6">
      <div class="text-center text-xs">
        <p>&copy; {{ date('Y') }} Citizen Alliance. All Rights Reserved. Developed by
          <a href="https://ultrashots.net/"
             target="_blank"
             class="text-blue-400 hover:text-blue-300 transition-colors duration-300">
            Ultrashots
          </a>
        </p>
      </div>
    </x-content-container>
  </div>
</footer>
