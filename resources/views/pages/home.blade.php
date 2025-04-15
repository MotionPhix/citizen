<x-app-layout>
  <x-slot name="title">Home</x-slot>
  <x-slot name="description">
    Citizen Alliance (CA) is a coalition of civil society organizations and citizen groups
    established in 2012 as a citizen-led engagement initiative on development and governance processes.
  </x-slot>

  <!-- Hero Slider Section -->
  <hero-slider :slides="{{ Js::from($slides) }}"></hero-slider>

  <x-content-container>
    <x-impact-stats :metrics="$metrics"></x-impact-stats>

    <program-section :programs="{{ Js::from($programs) }}"></program-section>

    <!-- Approaches Section -->
    <h2 class="text-3xl font-bold text-center font-display mb-12 text-ca-primary dark:text-ca-highlight">
      Our Approach
    </h2>

    <div class="grid md:grid-cols-3 gap-8">
      @foreach($approaches as $approach)
        <div
          class="bg-white rounded-lg shadow p-8 transform hover:-translate-y-1 transition-transform duration-300">
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
        class="relative h-[40rem] md:h-[calc(100vh-106px)] flex flex-col bg-cover bg-center bg-no-repeat">
        <div class="relative z-10 mt-auto w-3/4 md:max-w-full ps-5 pb-5 md:ps-10 md:pb-10">
          <span class="block text-lg text-white mb-2">Who We Are</span>

          <span class="block text-white text-lg sm:text-xl md:text-2xl">
            Citizen Alliance (CA) is a coalition of civil society organizations and citizen groups
            established in 2012 as a citizen-led engagement initiative on development and governance processes. The
            organization is registered under the laws of Malawi as a company limited by guaranteed and operates in all the
            districts through Citizen Forums (CFs).
          </span>
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
  <newsletter />
</x-app-layout>
