<div
  v-cloak
  v-scope="counterAnimation({ end: {{ $end }}, duration: {{ $duration ?? 2000 }} })"
  @vue:mounted="start">
  <span>{{ formattedValue }}</span>
</div>
