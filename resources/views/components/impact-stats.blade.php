@props(['metrics'])

<section class="py-16">
  <h2 class="text-3xl font-bold text-center font-display mb-12 text-ca-primary dark:text-ca-highlight">
    Our Impact in Numbers
  </h2>

  <!-- Impact Stats with Counter Animation -->
  <div class="grid md:grid-cols-3 gap-8 mb-16">
    @foreach($metrics as $metric)
      <div v-scope="impactCard({
      icon: '{{ $metric['icon'] }}',
      title: '{{ $metric['title'] }}',
      metric: {{ $metric['metric'] }},
      description: '{{ $metric['description'] }}'
    })" v-cloak>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
          <div class="flex items-center justify-center mb-4">
            <div class="w-12 h-12 bg-ca-primary rounded-lg flex items-center justify-center">
              <component :is="icon" class="text-white size-48" />
            </div>
          </div>
          <h3 class="text-xl font-semibold text-center mb-2 dark:text-white" v-text="title"></h3>
          <div class="text-center">
            <div v-scope="counterAnimation({ end: metric })"
                 class="text-2xl font-bold text-ca-primary"
                 @vue:mounted="start">
              @{{ formattedValue }}
            </div>
            <p class="mt-2 text-gray-600 dark:text-gray-300" v-text="description"></p>
          </div>
        </div>
      </div>
    @endforeach
  </div>
</section>
