<x-app-layout>
  <x-slot name="title">About Citizen Alliance</x-slot>
  <x-slot name="description">
    Discover our mission to empower citizens and drive positive change across Malawi through effective governance and
    community development initiatives.
  </x-slot>

  <div>
    <about-base
      :values='@json($values)'
      :team='@json($team)'
      :timeline='@json($timeline)'
      :subscriber-count='@json($subscriberCount)'>
      <template #impact>

        <!-- Impact Section -->
        <impact-section></impact-section>

      </template>
    </about-base>
  </div>
</x-app-layout>
