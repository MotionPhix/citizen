<x-app-layout>
  <x-slot name="title">
    Our Impact Projects
  </x-slot>

  <x-slot name="description">
    Discover how Citizen Alliance is driving sustainable change across Malawi through innovative community development
    projects.
  </x-slot>

  <div id="blade_app">
    <project-hero />

    <project-impact-stats
      v-if="impactStats.length"
      :stats='@json($impactStats)'
    />

    <projects-grid
      v-if="featuredProjects.length"
      :projects='@json($featuredProjects)'
      :featured="true"
    />

    <projects-grid
      :projects='@json($projects)'
    />

    <project-call-to-action />
  </div>
</x-app-layout>
