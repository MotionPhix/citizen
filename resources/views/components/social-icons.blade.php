@props(['links' => [], 'class' => ''])

<div {{ $attributes->merge(['class' => 'flex space-x-4 ' . $class]) }}>
  @if(isset($links['facebook']))
    <a href="{{ $links['facebook'] }}"
       target="_blank"
       class="text-gray-400 hover:text-blue-600 transition-colors duration-300">
      <span class="sr-only">Facebook</span>
      <x-icons.facebook class="h-5 w-5" />
    </a>
  @endif

  @if(isset($links['twitter']))
    <a href="{{ $links['twitter'] }}"
       target="_blank"
       class="text-gray-400 hover:text-blue-400 transition-colors duration-300">
      <span class="sr-only">Twitter</span>
      <x-icons.twitter class="h-5 w-5" />
    </a>
  @endif

  @if(isset($links['linkedin']))
    <a href="{{ $links['linkedin'] }}"
       target="_blank"
       class="text-gray-400 hover:text-blue-700 transition-colors duration-300">
      <span class="sr-only">LinkedIn</span>
      <x-icons.linkedin class="h-5 w-5" />
    </a>
  @endif

  @if(isset($links['instagram']))
    <a href="{{ $links['instagram'] }}"
       target="_blank"
       class="text-gray-400 hover:text-pink-600 transition-colors duration-300">
      <span class="sr-only">Instagram</span>
      <x-icons.instagram class="h-5 w-5" />
    </a>
  @endif
</div>
