@props(['programs'])

<section class="py-16">
  <h2 class="text-3xl font-display font-bold mb-12 text-ca-primary dark:text-ca-highlight">
    Our Core Programs
  </h2>

  <div class="space-y-16">
    @foreach($programs as $index => $program)
      <div class="program-card group">
        <div class="container mx-auto px-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center {{ $index % 2 === 0 ? '' : 'md:flex-row-reverse' }}">
            <div class="space-y-4">

              <h3 class="text-2xl font-display font-semibold text-gray-800 dark:text-gray-200">
                {{ $program->title }}
              </h3>

              <p class="text-gray-600 dark:text-gray-300">
                {{ $program->description }}
              </p>

              <a href="#"
                 class="inline-flex items-center text-ca-amber font-semibold hover:text-ca-purple dark:hover:text-ca-highlight transition-colors duration-300">
                Learn More
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
              </a>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>
</section>
