<x-app-layout>
  <x-slot name="title">Home</x-slot>
  <x-slot name="description">Citizen Alliance (CA) is a coalition of civil society organizations and citizen groups
    established in 2012 as a citizen-led engagement initiative on development and governance processes.
  </x-slot>

  <!-- Hero Slider Section -->
  <section class="relative">
    <div class="relative w-full h-[700px] overflow-hidden">
      <div x-data="{
            activeSlide: 0,
            slides: {{ json_encode($slides) }},
            init() {
                setInterval(() => {
                    this.activeSlide = (this.activeSlide + 1) % this.slides.length
                }, 5000)
            }
        }" class="relative h-full">
        <template x-for="(slide, index) in slides" :key="index">
          <div x-show="activeSlide === index"
               x-transition:enter="transition ease-out duration-300"
               x-transition:enter-start="opacity-0 transform translate-x-full"
               x-transition:enter-end="opacity-100 transform translate-x-0"
               x-transition:leave="transition ease-in duration-300"
               x-transition:leave-start="opacity-100 transform translate-x-0"
               x-transition:leave-end="opacity-0 transform -translate-x-full"
               class="absolute inset-0">
            <img :src="slide.image" :alt="slide.title"
                 class="object-cover w-full h-full transform hover:scale-105 transition-transform duration-500">

            <div class="absolute inset-0 bg-gradient-to-b from-black/50 to-black/70"></div>

            <div class="absolute inset-0 flex items-center">
              <div class="container mx-auto px-4">
                <div class="max-w-3xl text-white">
                  <h1 x-text="slide.title"
                      class="text-4xl font-display md:text-6xl font-bold mb-4 animate-fade-in">
                  </h1>
                  <p x-text="slide.description"
                     class="text-xl md:text-2xl mb-8 animate-fade-in-delay">
                  </p>
                  <a href="#about"
                     class="inline-flex items-center px-8 py-3 bg-ca-purple text-white rounded-lg hover:bg-ca-primary transition-all duration-300">
                    Learn More
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </template>

        <!-- Slider Controls -->
        <div class="absolute bottom-5 left-0 right-0 flex justify-center space-x-2">
          <template x-for="(slide, index) in slides" :key="index">
            <button
              @click="activeSlide = index"
              :class="{'bg-white': activeSlide === index, 'bg-white/50': activeSlide !== index}"
              class="w-3 h-3 rounded-full transition-all duration-300">
            </button>
          </template>
        </div>
      </div>
    </div>
  </section>

  <x-content-container>

    <x-impact-stats
      :metrics="[
            [
                'icon' => 'users',
                'title' => 'People Reached',
                'metric' => '50,000+',
                'description' => 'Individuals directly impacted across Malawi'
            ],
            [
                'icon' => 'school',
                'title' => 'Schools Supported',
                'metric' => '32',
                'description' => 'Educational institutions receiving our assistance'
            ],
            [
                'icon' => 'medical',
                'title' => 'Medical Camps',
                'metric' => '85',
                'description' => 'Health outreach programs conducted'
            ],
            [
                'icon' => 'water',
                'title' => 'Water Projects',
                'metric' => '120',
                'description' => 'Clean water access points installed'
            ],
            [
                'icon' => 'training',
                'title' => 'Training Sessions',
                'metric' => '450+',
                'description' => 'Skill development workshops conducted'
            ],
            [
                'icon' => 'women',
                'title' => 'Women Empowered',
                'metric' => '15,000+',
                'description' => 'Female participants in our programs'
            ],
            [
                'icon' => 'agriculture',
                'title' => 'Farmers Trained',
                'metric' => '2,500+',
                'description' => 'In sustainable agricultural practices'
            ],
            [
                'icon' => 'volunteers',
                'title' => 'Volunteers',
                'metric' => '1,200+',
                'description' => 'Active community volunteers'
            ]
        ]"
    />

    <x-programs-section :programs="$programs"/>

    <!-- Approaches Section -->
    <h2 class="text-3xl font-bold text-center font-display mb-12 text-ca-primary dark:text-ca-highlight">
      Our Approach
    </h2>

    <div class="grid md:grid-cols-3 gap-8">
      @foreach($approaches as $approach)
        <div
          class="bg-white rounded-lg shadow-lg p-8 transform hover:-translate-y-1 transition-transform duration-300">
          <div class="w-16 h-16 bg-blue-600 text-white rounded-lg flex items-center justify-center mb-6">

            @if($approach['icon'] === 'chat-bubble-left-right')
              <x-heroicon-o-chat-bubble-left-right class="w-8 h-8"/>
            @elseif($approach['icon'] === 'megaphone')
              <x-heroicon-o-megaphone class="w-8 h-8"/>
            @else
              <x-heroicon-o-chart-bar class="w-8 h-8"/>
            @endif

          </div>

          <h3 class="text-xl font-display font-semibold mb-4">{{ $approach['title'] }}</h3>
          <p class="text-gray-600">{{ $approach['description'] }}</p>
        </div>
      @endforeach
    </div>

    <!-- About Section -->
    <div class="rounded-2xl overflow-hidden my-12">
      <div
        style="background-image: url('{{ asset("/images/about-alliance.jpg") }}')"
        class="relative h-[30rem] md:h-[calc(100vh-106px)] flex flex-col bg-cover bg-center bg-no-repeat">
        <div class="relative z-10 mt-auto w-2/3 md:max-w-full ps-5 pb-5 md:ps-10 md:pb-10">
          <span class="block text-lg text-white mb-2">Who We Are</span>

          <span class="block text-white text-xl md:text-2xl">
            Citizen Alliance (CA) is a coalition of civil society organizations and citizen groups
            established in 2012 as a citizen-led engagement initiative on development and governance processes. The
            organization is registered under the laws of Malawi as a company limited by guaranteed and operates in all the
            districts through Citizen Forums (CFs).
          </span>

          <div class="mt-5">
            <a
              class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-xl bg-white border border-transparent text-black hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none"
              href="{{ route('about') }}">
              Read more
            </a>
          </div>
        </div>

        <div class="inset-0 bg-black absolute bg-opacity-55"></div>
      </div>
    </div>
    <!-- End About Section -->

  </x-content-container>

  <!-- Testimonials Section -->
  <x-testimonials :testimonials="[
    [
      'name' => 'Steven Phiri',
      'role' => 'Community Leader',
      'image' => 'images/testimonials/john-doe.jpg',
      'quote' => 'Citizen Alliance has transformed our community through their education programs.'
    ],
    [
      'name' => 'Sydney Gondwe',
      'role' => 'Health Worker',
      'image' => 'images/testimonials/jane-smith.jpg',
      'quote' => 'Their health initiatives have saved countless lives in our district.'
    ],
    [
      'name' => 'Joshua Lukhere',
      'role' => 'Farmer',
      'image' => 'images/testimonials/michael-johnson.jpg',
      'quote' => 'The agricultural training has helped me double my crop yields.'
    ]
  ]"
  />

  <!-- Call to Action Section -->
  <section class="py-16 bg-blue-600 text-white relative overflow-hidden">
    <div class="absolute inset-0 bg-[url('/images/pattern.svg')] opacity-10"></div>
    <div class="container mx-auto px-4 relative">
      <div class="text-center max-w-3xl mx-auto">
        <h2 class="text-3xl md:text-4xl font-bold mb-6 font-display">Support Citizen Alliance</h2>
        <div class="flex flex-col md:flex-row items-center justify-center space-y-4 md:space-y-0 md:space-x-8 mb-8">
          <a href="tel:+265991602233" class="flex items-center">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
            </svg>
            +265 (0) 991 602 233
          </a>
          <a href="mailto:support@citizenalliancemw.org" class="flex items-center">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            support@citizenalliancemw.org
          </a>
        </div>
        <div class="flex justify-center space-x-4">
          <a href="https://wa.me/+265991602233"
             class="inline-flex items-center px-6 py-3 bg-white text-blue-600 rounded-lg hover:bg-blue-50 transition-colors duration-300">
            <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
              <path
                d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
            </svg>
            WhatsApp Us
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- Newsletter Section -->
  <section class="py-16 bg-gray-50 dark:bg-ca-secondary">
    <div class="container mx-auto px-4">
      <div class="max-w-xl mx-auto text-center">
        <h2 class="text-3xl font-bold mb-4 font-display">Stay Updated</h2>
        <p class="text-gray-600 dark:text-gray-300 mb-8">Subscribe to our newsletter to get all our news in your
          inbox.</p>

        <form x-data="{
            email: '',
            success: false,
            error: false,
            loading: false,
            submit() {
                this.loading = true;
                // Add your newsletter subscription logic here
                setTimeout(() => {
                    this.loading = false;
                    this.success = true;
                    this.email = '';
                }, 1000);
            }
          }" @submit.prevent="submit" class="relative">
          <div class="flex flex-col md:flex-row">
            <input type="email"
                   x-model="email"
                   required
                   class="w-full px-4 py-3 rounded-b-none rounded-t-lg md:rounded-s-lg md:rounded-e-none border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                   placeholder="Enter your email">
            <button type="submit"
                    :disabled="loading"
                    class="px-6 py-3 bg-ca-highlight rounded-t-none rounded-b-lg md:rounded-e-lg md:rounded-s-none text-white hover:bg-ca-primary transition-colors duration-300 disabled:opacity-50">
              <span x-show="!loading">Subscribe</span>
              <span x-show="loading" class="flex items-center">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                     fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Processing...
              </span>
            </button>
          </div>

          <!-- Success Message -->
          <div x-show="success"
               x-transition:enter="transition ease-out duration-300"
               x-transition:enter-start="opacity-0 transform translate-y-2"
               x-transition:enter-end="opacity-100 transform translate-y-0"
               class="absolute mt-2 text-green-600">
            Thank you for subscribing to our newsletter!
          </div>
        </form>
      </div>
    </div>
  </section>
</x-app-layout>
