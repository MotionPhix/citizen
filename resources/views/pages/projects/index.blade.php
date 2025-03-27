<x-app-layout>
  <x-slot name="title">
    Our Impact Projects
  </x-slot>

  <x-slot name="description">
    Discover how Citizen Alliance is driving sustainable change across Malawi through innovative community development
    projects.
  </x-slot>

  <div id="blade_app">
    <project-hero></project-hero>

    @if($impactStats->isNotEmpty())

      <project-impact-stats
        :stats="{{ Js::from($impactStats) }}">
      </project-impact-stats>

    @endif

    @if($featuredProjects->isNotEmpty())

      <projects-grid
        :projects="{{ Js::from($featuredProjects) }}"
        :featured="true">
      </projects-grid>

    @endif

    <projects-grid
      :projects="{{ Js::from($projects) }}">
    </projects-grid>

    <project-call-to-action></project-call-to-action>
  </div>
</x-app-layout>
