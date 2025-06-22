@props([
    'color' => 'gray'
])

@php
  $colorClasses = match($color) {
      'success' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
      'warning' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
      'gray' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
      'secondary' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
      default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
  };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {$colorClasses}"]) }}>
    {{ $slot }}
</span>
