@props(['active' => false])

@php
$classes = $active
    ? 'block px-4 py-2 text-blue-600 bg-blue-50 font-medium'
    : 'block px-4 py-2 text-gray-700 hover:bg-gray-50 transition-colors duration-300';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
