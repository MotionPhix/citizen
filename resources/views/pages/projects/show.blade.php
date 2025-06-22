<x-app-layout>
  <x-slot name="title">
    {{ $project->title }}
  </x-slot>

  <x-slot name="description">
    {{ Str::limit(strip_tags($project->description), 160) }}
  </x-slot>

  {{-- Project Hero Section --}}
  <section class="relative min-h-[60vh] flex items-center bg-gradient-to-br from-ca-primary to-ca-highlight overflow-hidden">
    <div class="absolute inset-0">
      @if($project->hasMedia('project_image'))
        {{ $project->getFirstMedia('project_image')->img('hero', [
            'class' => 'w-full h-full object-cover',
            'alt' => $project->title
        ]) }}
      @endif
      <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/50 to-transparent"></div>
    </div>

    <x-content-container class="relative">
      <div class="max-w-6xl">
        <div class="flex items-center gap-2 mb-4 text-white/80">
          <x-badge :color="match($project->status) {
                        'current' => 'success',
                        'completed' => 'gray',
                        'upcoming' => 'warning',
                        default => 'secondary'
                    }">
            {{ Str::title($project->status) }}
          </x-badge>
          @if($project->tags->isNotEmpty())
            @foreach($project->tags as $tag)
              <span
                class="px-3 py-1 text-sm bg-white/10 rounded-full backdrop-blur-sm">
                {{ $tag->name }}
            </span>
            @endforeach
          @endif
        </div>

        <h1 class="text-4xl md:text-6xl font-display text-white mb-6">
          {{ $project->title }}
        </h1>

        <div class="flex flex-wrap gap-6 text-gray-200">
          @if($project->start_date)
            <div class="flex items-center">
              <x-heroicon-o-calendar class="w-5 h-5 mr-2" />
              <time datetime="{{ $project->start_date }}">
                {{ Carbon\Carbon::parse($project->start_date)->format('M d, Y') }}
              </time>
              @if($project->end_date)
                <span class="mx-2">-</span>
                <time datetime="{{ $project->end_date }}">
                  {{ Carbon\Carbon::parse($project->end_date)->format('M d, Y') }}
                </time>
              @endif
            </div>
          @endif

          @if($project->funded_by)
            <div class="flex items-center">
              <x-heroicon-o-building-office class="w-5 h-5 mr-2" />
              <span>{{ $project->funded_by }}</span>
            </div>
          @endif

          @if($project->people_reached)
            <div class="flex items-center">
              <x-heroicon-o-users class="w-5 h-5 mr-2" />
              <span>{{ number_format($project->people_reached) }} people reached</span>
            </div>
          @endif
        </div>
      </div>
    </x-content-container>
  </section>

  {{-- Project Content --}}
  <section class="py-16 bg-white dark:bg-ca-primary">
    <x-content-container>
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        {{-- Main Content --}}
        <div class="lg:col-span-2">
          <div class="prose prose-lg dark:prose-invert max-w-none fix-images">
            {!! $project->content !!}
          </div>

          {{-- Key Achievements --}}
          @if(!empty($project->key_achievements))
            <div class="mt-12 bg-gray-50 dark:bg-ca-secondary rounded-2xl p-8">
              <h2 class="text-2xl font-display text-ca-primary dark:text-white mb-6">
                Key Achievements
              </h2>
              <div class="grid gap-4">
                @foreach($project->key_achievements as $achievement)
                  <div class="flex items-start group">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-ca-primary/10 dark:bg-ca-highlight/20 flex items-center justify-center group-hover:bg-ca-primary dark:group-hover:bg-ca-highlight transition-colors duration-300">
                      <x-heroicon-o-check class="w-5 h-5 text-ca-primary dark:text-ca-highlight group-hover:text-white transition-colors duration-300" />
                    </div>

                    <p class="ml-4 text-gray-700 dark:text-gray-300">
                      {{ $achievement }}
                    </p>
                  </div>
                @endforeach
              </div>
            </div>
          @endif

          {{-- Project Timeline --}}
          <div class="mt-12 bg-gray-50 dark:bg-ca-secondary rounded-2xl p-8">
            <h2 class="text-2xl font-display text-ca-primary dark:text-white mb-6">
              Project Timeline
            </h2>
            <div class="relative pl-8 border-l-2 border-ca-primary/20">
              <div class="absolute w-4 h-4 -left-[9px] bg-ca-primary rounded-full"></div>
              <div class="mb-8">
                <time class="text-sm text-gray-500">
                  {{ Carbon\Carbon::parse($project->start_date)->format('M d, Y') }}
                </time>
                <h3 class="font-medium text-ca-primary dark:text-white">Project Start</h3>
              </div>
              @if($project->end_date)
                <div class="absolute w-4 h-4 -left-[9px] bottom-0 bg-ca-highlight rounded-full"></div>
                <div>
                  <time class="text-sm text-gray-500">
                    {{ Carbon\Carbon::parse($project->end_date)->format('M d, Y') }}
                  </time>
                  <h3 class="font-medium text-ca-primary dark:text-white">Project Completion</h3>
                </div>
              @endif
            </div>
          </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-8">
          {{-- Project Stats --}}
          <div class="bg-gray-50 dark:bg-ca-secondary rounded-2xl p-8">
            <h3 class="text-2xl font-display text-ca-primary dark:text-white mb-6">
              Project Overview
            </h3>

            <div class="space-y-6">
              @if($project->people_reached)
                <div class="grid divide-y divide-gray-200 dark:divide-gray-300">
                  <span class="text-gray-600 dark:text-gray-400">People Reached</span>
                  <span class="text-xl font-bold text-ca-primary dark:text-white">
                    {{ number_format($project->people_reached) }}
                  </span>
                </div>
              @endif

              @if($project->budget)
                <div class="grid divide-y divide-gray-200 dark:divide-gray-300">
                  <span class="text-gray-600 dark:text-gray-400">Project Budget</span>
                  <span class="text-xl font-bold text-ca-primary dark:text-white">
                      ${{ number_format($project->budget) }}
                  </span>
                </div>
              @endif

              <div class="grid divide-y divide-gray-200 dark:divide-gray-300">
                <span class="text-gray-600 dark:text-gray-400">Duration</span>
                <span class="text-xl font-bold text-ca-primary dark:text-white">
                  {{ number_format(Carbon\Carbon::parse($project->start_date)->diffInMonths($project->end_date)) }} months
                </span>
              </div>

              @if(!empty($project->meta_data))
                @foreach($project->meta_data as $key => $value)
                  <div class="flex items-center justify-between">
                    <span class="text-gray-600 dark:text-gray-400">{{ $key }}</span>
                    <span class="text-xl font-bold text-ca-primary dark:text-white">
                      {{ $value }}
                    </span>
                  </div>
                @endforeach
              @endif
            </div>

            <div class="mt-8">
              <a href="#"
                 class="inline-flex items-start justify-center w-full px-6 py-3 bg-ca-highlight text-white rounded-xl hover:bg-ca-highlight/90 transition-colors duration-300">
                <x-heroicon-o-hand-raised class="w-5 h-5 mr-2" />
                Support This Project
              </a>
            </div>
          </div>

          {{-- Share Project --}}
          <x-social-share
            :url="url()->current()"
            :title="$project->title"
            :description="$project->description"
          />
        </div>
      </div>
    </x-content-container>
  </section>

  {{-- Project Gallery --}}
  @if($project->getMedia('project_gallery')->isNotEmpty())
    <x-project-gallery :media="$project->getMedia('project_gallery')" />
  @endif
</x-app-layout>
