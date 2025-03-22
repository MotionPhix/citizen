@props(['active' => false])

@php
$classes = $active
    ? 'text-blue-600 font-medium'
    : 'text-gray-700 hover:text-blue-600 transition-colors duration-300';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
