<x-guest-layout>
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
        <img class="object-cover w-full" src="{{ $project->featured_image }}" alt="{{ $project->title }}">
        {{
            $project->getFirstMedia('project_image')
              ->img('hero', [
                'alt' => $project->title
              ])
        }}
      @endif
      <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/50 to-transparent"></div>
    </div>

    <x-content-container class="relative">
      <div class="max-w-6xl">
        @if($project->tags->isNotEmpty())
          <div class="flex flex-wrap gap-2 mb-6 animate-fade-in" style="animation-delay: 0.2s">
            @foreach($project->tags as $tag)
              <span class="px-4 py-1 text-sm bg-white/20 text-white rounded-full backdrop-blur-sm">
                {{ $tag->name }}
              </span>
            @endforeach
          </div>
        @endif

        <h1 class="text-5xl md:text-6xl font-display text-white mb-6 animate-fade-in" style="animation-delay: 0.4s">
          {{ $project->title }}
        </h1>

        <div class="flex flex-wrap gap-6 text-gray-200 animate-fade-in" style="animation-delay: 0.6s">
          @if($project->start_date)
            <div class="flex items-center">
              <x-heroicon-o-calendar class="w-5 h-5 mr-2" />
              <span>{{ $project->start_date->format('M d, Y') }}</span>
              @if($project->end_date)
                <span class="mx-2">-</span>
                <span>{{ $project->end_date->format('M d, Y') }}</span>
              @endif
            </div>
          @endif

          @if($project->status)
            <div class="flex items-center">
              <x-heroicon-o-check-circle class="w-5 h-5 mr-2" />
              <span>{{ $project->status }}</span>
            </div>
          @endif

          @if($project->funded_by)
            <div class="flex items-center">
              <x-heroicon-o-building-office class="w-5 h-5 mr-2" />
              <span>Funded by {{ $project->funded_by }}</span>
            </div>
          @endif
        </div>
      </div>
    </x-content-container>
  </section>

  {{-- Project Overview --}}
  <section class="py-16 bg-white dark:bg-ca-primary">
    <x-content-container>
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        {{-- Main Content --}}
        <div class="lg:col-span-2">
          <div class="prose dark:prose-invert max-w-none">
            {!! $project->content !!}
          </div>

          {{-- Key Achievements --}}
          @if($project->key_achievements)
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
{{--                      {!! $achievement !!}--}}
                    </p>
                  </div>
                @endforeach
              </div>
            </div>
          @endif
        </div>

        {{-- Sidebar --}}
        <div>
          {{-- Project Stats --}}
          <div class="bg-gray-50 dark:bg-ca-secondary rounded-2xl p-8 mb-8">
            <h3 class="text-2xl font-display text-ca-primary dark:text-white mb-6">
              Project Overview
            </h3>

            <div class="space-y-6">
              @if($project->people_reached)
                <div class="flex items-center justify-between">
                  <span class="text-gray-600 dark:text-gray-400">People Reached</span>
                  <span class="text-xl font-bold text-ca-primary dark:text-white">
                    {{ number_format($project->people_reached) }}
                  </span>
                </div>
              @endif

              @if($project->budget)
                <div class="flex items-center justify-between">
                  <span class="text-gray-600 dark:text-gray-400">Project Budget</span>
                  <span class="text-xl font-bold text-ca-primary dark:text-white">
                    ${{ number_format($project->budget) }}
                  </span>
                </div>
              @endif

              @if($project->meta_data)
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
              <a href="{{ route('contact.index') }}"
                 class="inline-flex items-center justify-center w-full px-6 py-3 bg-ca-highlight text-white rounded-xl hover:bg-ca-highlight/90 transition-colors duration-300">
                <x-heroicon-o-chat-bubble-left-right class="w-5 h-5 mr-2" />
                Get Involved
              </a>
            </div>
          </div>

          {{-- Share Project --}}
          <div class="bg-gray-50 dark:bg-ca-secondary rounded-2xl p-8">
            <h3 class="text-xl font-display text-ca-primary dark:text-white mb-6">
              Share Project
            </h3>
            <div class="flex gap-4">
              <a href="https://twitter.com/intent/tweet?url={{ url()->current() }}&text={{ urlencode($project->title) }}"
                 target="_blank"
                 class="flex-1 flex h-full w-full items-center justify-center p-3 bg-[#1DA1F2] text-white rounded-xl hover:opacity-90 transition-opacity duration-300">
                <x-heroicon-o-link class="w-5 h-5" />
              </a>

              <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}"
                 target="_blank"
                 class="flex-1 flex items-center justify-center p-3 bg-[#4267B2] text-white rounded-xl hover:opacity-90 transition-opacity duration-300">
                <x-heroicon-o-share class="w-5 h-5" />
              </a>

              <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ url()->current() }}&title={{ urlencode($project->title) }}"
                 target="_blank"
                 class="flex-1 flex items-center justify-center p-3 bg-[#0077B5] text-white rounded-xl hover:opacity-90 transition-opacity duration-300">
                <x-heroicon-o-link class="w-5 h-5" />
              </a>
            </div>
          </div>
        </div>
      </div>
    </x-content-container>
  </section>

  {{-- Project Gallery --}}
  @if($project->hasMedia('project_gallery'))
    <section class="py-16 bg-gray-50 dark:bg-ca-primary/5">
      <x-content-container>
        <h2 class="text-3xl font-display text-ca-primary dark:text-white mb-8">
          Project Gallery
        </h2>

        <div x-data="{ selected: null }">
          <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($project->getMedia('project_gallery') as $index => $media)
              <button
                @click="selected = {{ $index }}"
                class="relative aspect-square group overflow-hidden rounded-xl">
                {{ $media->img('preview', [
                    'class' => 'w-full h-full object-cover group-hover:scale-110 transition-transform duration-500',
                    'alt' => $media->name ?? $project->title
                ]) }}
                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                  <x-heroicon-o-plus-circle class="w-8 h-8 text-white" />
                </div>
              </button>
            @endforeach
          </div>

          {{-- Gallery Modal --}}
          <div x-show="selected !== null"
               x-transition
               class="fixed inset-0 z-50 flex items-center justify-center bg-black/90"
               @keydown.escape.window="selected = null"
               @click.self="selected = null">
            <button @click="selected = null" class="absolute top-4 right-4 text-white">
              <x-heroicon-o-x-mark class="w-8 h-8" />
            </button>

            <div class="relative max-w-4xl mx-auto">
              <button
                @click="selected = selected > 0 ? selected - 1 : {{ $project->getMedia('project_gallery')->count() - 1 }}"
                class="absolute left-4 top-1/2 -translate-y-1/2 text-white">
                <x-heroicon-o-chevron-left class="w-8 h-8" />
              </button>

              {{-- Store URLs in a JavaScript array --}}
              <div x-data="{
            urls: [
                @foreach($project->getMedia('project_gallery') as $media)
                    '{{ $media->getUrl() }}',
                @endforeach
            ]
        }">
                <img
                  :src="urls[selected]"
                  class="max-h-[80vh] w-auto mx-auto"
                  :alt="`${@js($project->title)} - Image ${selected + 1}`"
                  loading="lazy">
              </div>

              <button
                @click="selected = selected < {{ $project->getMedia('project_gallery')->count() - 1 }} ? selected + 1 : 0"
                class="absolute right-4 top-1/2 -translate-y-1/2 text-white">
                <x-heroicon-o-chevron-right class="w-8 h-8" />
              </button>
            </div>
          </div>
        </div>
      </x-content-container>
    </section>
  @endif

  {{-- Related Projects --}}
  {{-- You might want to pass related projects from the controller --}}
  @if(isset($relatedProjects) && $relatedProjects->isNotEmpty())
    <section class="py-16 bg-white dark:bg-ca-primary">
      <x-content-container>
        <h2 class="text-3xl font-display text-ca-primary dark:text-white mb-8">
          Related Projects
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          @foreach($relatedProjects as $relatedProject)
            <a href="{{ route('projects.show', $relatedProject->slug) }}"
               class="group bg-gray-50 dark:bg-ca-secondary rounded-2xl overflow-hidden hover:shadow-card-hover transition-all duration-300">
              @if($relatedProject->hasMedia('project_image'))
                <div class="aspect-[16/10] overflow-hidden">
                  {{ $relatedProject->getFirstMedia('project_image')
                      ->img('preview', [
                          'class' => 'w-full h-full object-cover group-hover:scale-110 transition-transform duration-500',
                          'alt' => $relatedProject->title
                      ]) }}
                </div>
              @endif

              <div class="p-6">
                <h3 class="text-xl font-display text-ca-primary dark:text-white group-hover:text-ca-highlight transition-colors duration-300">
                  {{ $relatedProject->title }}
                </h3>
                <p class="mt-2 text-gray-600 dark:text-gray-400 line-clamp-2">
                  {{ $relatedProject->description }}
                </p>
              </div>
            </a>
          @endforeach
        </div>
      </x-content-container>
    </section>
  @endif
</x-guest-layout>
