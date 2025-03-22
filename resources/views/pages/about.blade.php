<x-app-layout>
  <x-slot name="title">About Citizen Alliance</x-slot>
  <x-slot name="description">
    Discover our mission to empower citizens and drive positive change across Malawi through effective governance and
    community development initiatives.
  </x-slot>

  <!-- Hero Section with Parallax -->
  <section
    class="relative min-h-[600px] flex items-center bg-gradient-to-br from-ca-primary to-ca-highlight dark:from-gray-900 dark:to-ca-primary/50 overflow-hidden">
    <div class="absolute inset-0 overflow-hidden">
      <div
        class="absolute inset-0 bg-[url('/images/pattern.svg')] opacity-10 dark:opacity-20"
        x-data="{}"
        x-init="window.addEventListener('mousemove', (e) => {
          const { clientX, clientY } = e;
          const xPos = (clientX / window.innerWidth - 0.5) * 20;
          const yPos = (clientY / window.innerHeight - 0.5) * 20;
          $el.style.transform = `translate(${xPos}px, ${yPos}px)`;
        })"
      ></div>
    </div>

    <x-content-container class="relative pt-32 pb-20">
      <div class="max-w-2xl text-left" x-data="{ shown: false }" x-intersect="shown = true">
        <div class="space-y-6">
          <h1
            class="text-4xl md:text-6xl font-display font-bold text-white leading-tight"
            x-show="shown"
            x-transition:enter="transition ease-out duration-500"
            x-transition:enter-start="opacity-0 translate-y-10"
            x-transition:enter-end="opacity-100 translate-y-0"
          >
            Empowering Citizens for Lasting Change in Malawi
          </h1>

          <p
            class="text-xl md:text-2xl text-white/90"
            x-show="shown"
            x-transition:enter="transition ease-out delay-300 duration-500"
            x-transition:enter-start="opacity-0 translate-y-10"
            x-transition:enter-end="opacity-100 translate-y-0"
          >
            Since 2012, we've been at the forefront of strengthening citizen participation in governance and fostering
            sustainable development across Malawi.
          </p>

          <div
            x-show="shown"
            x-transition:enter="transition ease-out delay-500 duration-500"
            x-transition:enter-start="opacity-0 translate-y-10"
            x-transition:enter-end="opacity-100 translate-y-0"
          >
            <a
              href="#mission"
              class="inline-flex items-center px-8 py-4 bg-white dark:bg-gray-900 text-ca-primary rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition-all duration-300 group shadow-lg hover:shadow-xl"
            >
              Discover Our Story
              <x-heroicon-o-arrow-down
                class="w-5 h-5 ml-2 transform group-hover:translate-y-1 transition-transform duration-300"
              />
            </a>
          </div>
        </div>
      </div>
    </x-content-container>
  </section>

  <!-- Mission & Vision Section -->
  <section id="mission" class="py-24 bg-white dark:bg-gray-900" x-data="{ shown: false }" x-intersect="shown = true">
    <x-content-container>
      <div class="grid md:grid-cols-2 gap-12">
        <div
          class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-10 transform hover:-translate-y-2 transition-all duration-300"
          x-show="shown"
          x-transition:enter="transition ease-out duration-500"
          x-transition:enter-start="opacity-0 translate-x-10"
          x-transition:enter-end="opacity-100 translate-x-0"
        >
          <div
            class="w-16 h-16 bg-ca-primary/10 dark:bg-ca-primary/20 text-ca-primary rounded-xl flex items-center justify-center mb-6">
            <x-heroicon-o-eye class="w-8 h-8"/>
          </div>

          <h2 class="text-2xl font-display font-bold text-gray-900 dark:text-white mb-4">Our Vision</h2>
          <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
            A transformed Malawi where citizens are empowered, actively engaged in governance, and their voices shape
            the decisions that affect their lives.
          </p>
        </div>

        <div
          class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-10 transform hover:-translate-y-2 transition-all duration-300"
          x-show="shown"
          x-transition:enter="transition ease-out delay-300 duration-500"
          x-transition:enter-start="opacity-0 translate-x-10"
          x-transition:enter-end="opacity-100 translate-x-0"
        >
          <div
            class="w-16 h-16 bg-ca-primary/10 dark:bg-ca-primary/20 text-ca-primary rounded-xl flex items-center justify-center mb-6">
            <x-heroicon-o-flag class="w-8 h-8"/>
          </div>

          <h2 class="text-2xl font-display font-bold text-gray-900 dark:text-white mb-4">Our Mission</h2>
          <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
            To strengthen citizen participation in governance and development processes through advocacy, capacity
            building, and fostering meaningful dialogue between citizens and duty bearers.
          </p>
        </div>
      </div>
    </x-content-container>
  </section>

  <!-- Values Section -->
  <section class="py-24 bg-gray-50 dark:bg-gray-800/50">
    <x-content-container>
      <div
        class="text-center max-w-3xl mx-auto mb-16"
        x-data="{ shown: false }"
        x-intersect="shown = true"
      >
        <h2
          class="text-3xl md:text-4xl font-display font-bold text-gray-900 dark:text-white mb-6"
          x-show="shown"
          x-transition:enter="transition ease-out duration-500"
          x-transition:enter-start="opacity-0 translate-y-10"
          x-transition:enter-end="opacity-100 translate-y-0"
        >
          Our Core Values
        </h2>
        <p
          class="text-xl text-gray-600 dark:text-gray-300"
          x-show="shown"
          x-transition:enter="transition ease-out delay-300 duration-500"
          x-transition:enter-start="opacity-0 translate-y-10"
          x-transition:enter-end="opacity-100 translate-y-0"
        >
          The principles that guide our work and shape our approach to community development.
        </p>
      </div>

      <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
        @foreach($values as $index => $value)
          <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8 transform hover:-translate-y-2 transition-all duration-300"
            x-data="{ shown: false }"
            x-intersect="shown = true"
            x-show="shown"
            x-transition:enter="transition ease-out duration-500"
            x-transition:enter-start="opacity-0 translate-y-10"
            x-transition:enter-end="opacity-100 translate-y-0"
            style="transition-delay: {{ $index * 150 }}ms">
            <div
              class="w-14 h-14 bg-ca-primary/10 dark:bg-ca-primary/20 text-ca-primary rounded-xl flex items-center justify-center mb-6">
              @if($value['icon'] === 'scale')
                <x-heroicon-o-scale class="w-7 h-7"/>
              @elseif($value['icon'] === 'hand')
                <x-heroicon-o-hand-raised class="w-7 h-7"/>
              @else
                <x-heroicon-o-light-bulb class="w-7 h-7"/>
              @endif
            </div>

            <h3 class="text-xl font-display font-semibold text-gray-900 dark:text-white mb-3">
              {{ $value['title'] }}
            </h3>
            <p class="text-gray-600 dark:text-gray-300">
              {{ $value['description'] }}
            </p>
          </div>
        @endforeach
      </div>
    </x-content-container>
  </section>

  <!-- Impact Section -->
  <section class="relative py-24 overflow-hidden">
    <x-content-container class="relative">
      <div class="lg:mx-auto lg:grid lg:grid-cols-2 lg:items-center lg:gap-24">
        <!-- Image -->
        <div class="relative h-[600px] overflow-hidden rounded-xl lg:order-last" x-data="{ shown: false }"
             x-intersect="shown = true">
          <img
            src="{{ asset('images/about-us.jpg') }}"
            class="absolute inset-0 h-full w-full object-cover"
            x-show="shown"
            x-transition:enter="transition ease-out duration-1000"
            x-transition:enter-start="opacity-0 scale-110"
            x-transition:enter-end="opacity-100 scale-100"
          >
          <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>

          <!-- Stats -->
          <div class="absolute bottom-0 left-0 right-0 p-8">
            <div class="grid grid-cols-2 gap-8">
              <div class="text-white">
                <p class="text-3xl font-bold">50K+</p>
                <p class="text-white/80">People Reached</p>
              </div>
              <div class="text-white">
                <p class="text-3xl font-bold">85+</p>
                <p class="text-white/80">Communities Served</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Content -->
        <div class="relative pt-12 sm:pt-0 px-6 lg:px-0" x-data="{ shown: false }" x-intersect="shown = true">
          <div class="mb-12">
            <div
              class="flex items-center space-x-2 text-ca-primary mb-6"
              x-show="shown"
              x-transition:enter="transition ease-out duration-500"
              x-transition:enter-start="opacity-0 translate-y-5"
              x-transition:enter-end="opacity-100 translate-y-0"
            >
              <div class="w-12 h-1 bg-ca-primary rounded-full"></div>
              <p class="font-medium uppercase tracking-wide">Our Impact</p>
            </div>

            <h2
              class="text-3xl md:text-4xl font-display font-bold text-gray-900 dark:text-white mb-6"
              x-show="shown"
              x-transition:enter="transition ease-out delay-300 duration-500"
              x-transition:enter-start="opacity-0 translate-y-5"
              x-transition:enter-end="opacity-100 translate-y-0"
            >
              Creating Lasting Change in Communities
            </h2>

            <p
              class="text-lg text-gray-600 dark:text-gray-300"
              x-show="shown"
              x-transition:enter="transition ease-out delay-500 duration-500"
              x-transition:enter-start="opacity-0 translate-y-5"
              x-transition:enter-end="opacity-100 translate-y-0"
            >
              Through our programs and initiatives, we've touched thousands of lives, empowering individuals to take
              active roles in their communities' development and governance processes.
            </p>
          </div>

          <!-- Key Achievements -->
          <div class="space-y-8">
            @foreach([
              ['icon' => 'academic-cap', 'title' => 'Education', 'description' => '32 schools supported with resources and training programs'],
              ['icon' => 'heart', 'title' => 'Healthcare', 'description' => '85 medical camps organized in rural communities'],
              ['icon' => 'users', 'title' => 'Community', 'description' => '450+ training sessions conducted for local leaders']
            ] as $index => $achievement)
              <div
                class="flex items-start space-x-4"
                x-show="shown"
                x-transition:enter="transition ease-out duration-500"
                x-transition:enter-start="opacity-0 translate-y-5"
                x-transition:enter-end="opacity-100 translate-y-0"
                style="transition-delay: {{ 700 + ($index * 200) }}ms"
              >
                <div class="flex-shrink-0">
                  <div
                    class="w-10 h-10 bg-ca-primary/10 dark:bg-ca-primary/20 rounded-lg flex items-center justify-center">
                    <x-dynamic-component
                      :component="'heroicon-o-' . $achievement['icon']"
                      class="w-5 h-5 text-ca-primary"
                    />
                  </div>
                </div>
                <div>
                  <h3 class="font-display font-semibold text-gray-900 dark:text-white mb-1">
                    {{ $achievement['title'] }}
                  </h3>
                  <p class="text-gray-600 dark:text-gray-300">
                    {{ $achievement['description'] }}
                  </p>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </x-content-container>
  </section>

  <!-- Team Section -->
  <section class="py-24 bg-gray-50 dark:bg-gray-800/50">
    <x-content-container>
      <div
        class="text-center max-w-3xl mx-auto mb-16"
        x-data="{ shown: false }"
        x-intersect="shown = true"
      >
        <h2
          class="text-3xl md:text-4xl font-display font-bold text-gray-900 dark:text-white mb-6"
          x-show="shown"
          x-transition:enter="transition ease-out duration-500"
          x-transition:enter-start="opacity-0 translate-y-10"
          x-transition:enter-end="opacity-100 translate-y-0"
        >
          Meet Our Board Members
        </h2>
        <p
          class="text-xl text-gray-600 dark:text-gray-300"
          x-show="shown"
          x-transition:enter="transition ease-out delay-300 duration-500"
          x-transition:enter-start="opacity-0 translate-y-10"
          x-transition:enter-end="opacity-100 translate-y-0"
        >
          Dedicated professionals committed to driving positive change in our communities.
        </p>
      </div>

      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($team as $index => $member)
          <div
            class="group"
            x-data="{ shown: false }"
            x-intersect="shown = true"
            x-show="shown"
            x-transition:enter="transition ease-out duration-500"
            x-transition:enter-start="opacity-0 translate-y-10"
            x-transition:enter-end="opacity-100 translate-y-0"
            style="transition-delay: {{ $index * 200 }}ms">
            <div
              class="relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800 shadow-lg transform group-hover:-translate-y-2 transition-all duration-300">
              <div class="aspect-w-3 aspect-h-4 relative">
                <img
                  src="{{ $member['image'] }}"
                  alt="{{ $member['name'] }}"
                  class="object-cover w-full h-full transform group-hover:scale-110 transition-transform duration-500">
                <div
                  class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/0 to-black/0 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
              </div>

              <div class="p-6">
                <h3 class="text-xl font-display font-semibold text-gray-900 dark:text-white mb-1">
                  {{ $member['name'] }}
                </h3>
                <p class="text-ca-primary dark:text-ca-primary/90 font-medium mb-4">
                  {{ $member['position'] }}
                </p>
                <p class="text-gray-600 dark:text-gray-300 line-clamp-3">
                  {{ $member['bio'] }}
                </p>

                <!-- Social Links -->
                <div class="flex items-center space-x-4 mt-6">
                  @if(isset($member['linkedin']))
                    <a href="{{ $member['linkedin'] }}"
                       class="text-gray-400 hover:text-ca-primary dark:hover:text-white transition-colors duration-300">
                      <span class="sr-only">LinkedIn</span>
                      <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path
                          d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.784 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                      </svg>
                    </a>
                  @endif

                  @if(isset($member['twitter']))
                    <a href="{{ $member['twitter'] }}"
                       class="text-gray-400 hover:text-ca-primary dark:hover:text-white transition-colors duration-300">
                      <span class="sr-only">Twitter</span>
                      <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path
                          d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.061a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.937 4.937 0 004.604 3.417 9.868 9.868 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.054 0 13.999-7.496 13.999-13.986 0-.209 0-.42-.015-.63a9.936 9.936 0 002.46-2.548l-.047-.02z"/>
                      </svg>
                    </a>
                  @endif
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </x-content-container>
  </section>

  <!-- Timeline Section -->
  <section class="py-24 bg-white dark:bg-gray-900">
    <x-content-container>
      <div
        class="text-center max-w-3xl mx-auto mb-16"
        x-data="{ shown: false }"
        x-intersect="shown = true"
      >
        <h2
          class="text-3xl md:text-4xl font-display font-bold text-gray-900 dark:text-white mb-6"
          x-show="shown"
          x-transition:enter="transition ease-out duration-500"
          x-transition:enter-start="opacity-0 translate-y-10"
          x-transition:enter-end="opacity-100 translate-y-0"
        >
          Our Journey
        </h2>
        <p
          class="text-xl text-gray-600 dark:text-gray-300"
          x-show="shown"
          x-transition:enter="transition ease-out delay-300 duration-500"
          x-transition:enter-start="opacity-0 translate-y-10"
          x-transition:enter-end="opacity-100 translate-y-0"
        >
          A decade of growth, impact, and community transformation.
        </p>
      </div>

      <div class="max-w-4xl mx-auto">
        @foreach($timeline as $index => $item)
          <div
            class="relative pl-8 sm:pl-32 py-6 group"
            x-data="{ shown: false }"
            x-intersect="shown = true"
            x-show="shown"
            x-transition:enter="transition ease-out duration-500"
            x-transition:enter-start="opacity-0 translate-y-10"
            x-transition:enter-end="opacity-100 translate-y-0"
            style="transition-delay: {{ $index * 200 }}ms"
          >
            <!-- Line -->
            <div
              class="hidden sm:block absolute left-0 top-0 h-full w-[1px] bg-gray-200 dark:bg-gray-700 group-last:h-1/2"></div>

            <!-- Year -->
            <div
              class="absolute left-0 sm:left-16 top-6 flex items-center justify-center w-8 h-8 rounded-full bg-ca-primary text-white font-bold text-sm transform -translate-x-1/2 group-hover:scale-110 transition-transform duration-300">
              {{ substr($item['year'], -2) }}
            </div>

            <!-- Content -->
            <div
              class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 transform group-hover:-translate-y-1 transition-all duration-300">
              <time class="text-sm font-medium text-ca-primary dark:text-ca-primary/90 mb-1 block">
                {{ $item['year'] }}
              </time>
              <h3 class="text-xl font-display font-semibold text-gray-900 dark:text-white mb-2">
                {{ $item['title'] }}
              </h3>
              <p class="text-gray-600 dark:text-gray-300">
                {{ $item['description'] }}
              </p>
            </div>
          </div>
        @endforeach
      </div>
    </x-content-container>
  </section>

  <!-- Call to Action Section -->
  <section
    class="relative py-24 bg-gradient-to-br from-ca-primary to-ca-highlight dark:from-gray-900 dark:to-ca-primary/50 overflow-hidden">
    <div
      class="absolute inset-0 bg-[url('/images/pattern.svg')] opacity-10"
      x-data="{}"
      x-init="window.addEventListener('mousemove', (e) => {
        const { clientX, clientY } = e;
        const xPos = (clientX / window.innerWidth - 0.5) * 20;
        const yPos = (clientY / window.innerHeight - 0.5) * 20;
        $el.style.transform = `translate(${xPos}px, ${yPos}px)`;
      })"
    ></div>

    <x-content-container class="relative">
      <div
        class="max-w-3xl mx-auto text-center"
        x-data="{ shown: false }"
        x-intersect="shown = true"
      >
        <h2
          class="text-3xl md:text-4xl font-display font-bold text-white mb-6"
          x-show="shown"
          x-transition:enter="transition ease-out duration-500"
          x-transition:enter-start="opacity-0 translate-y-10"
          x-transition:enter-end="opacity-100 translate-y-0"
        >
          Join Us in Making a Difference
        </h2>
        <p
          class="text-xl text-white/90 mb-12"
          x-show="shown"
          x-transition:enter="transition ease-out delay-300 duration-500"
          x-transition:enter-start="opacity-0 translate-y-10"
          x-transition:enter-end="opacity-100 translate-y-0">
          Whether you're an organization looking to partner with us or an individual wanting to support our cause,
          your involvement can help create lasting change in Malawi.
        </p>

        <div
          class="flex flex-col sm:flex-row items-center justify-center space-y-4 sm:space-y-0 sm:space-x-6"
          x-show="shown"
          x-transition:enter="transition ease-out delay-500 duration-500"
          x-transition:enter-start="opacity-0 translate-y-10"
          x-transition:enter-end="opacity-100 translate-y-0">
          <a
            href="{{ route('contact.index') }}"
            class="inline-flex items-center px-8 py-4 bg-white dark:bg-gray-900 text-ca-primary rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1"
          >
            Contact Us
            <x-heroicon-o-arrow-right class="w-5 h-5 ml-2"/>
          </a>

          <a
            href="{{ route('projects.index') }}"
            class="inline-flex items-center px-8 py-4 border-2 border-white text-white rounded-xl hover:bg-white/10 transition-all duration-300"
          >
            View Our Projects
            <x-heroicon-o-arrow-up-right class="w-5 h-5 ml-2"/>
          </a>
        </div>
      </div>
    </x-content-container>
  </section>
</x-app-layout>
