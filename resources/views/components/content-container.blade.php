@props(['class' => ''])

<section {{ $attributes->merge(['class' => 'max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 ' . $class]) }}>
  {{ $slot }}
</section>
