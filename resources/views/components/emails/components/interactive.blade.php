@props([
    'type' => 'default', // button, card, cta, social, rating
    'href' => '#',
    'text' => '',
    'icon' => null,
    'color' => 'primary'
])

@php
  $styles = [
      'button' => 'display: inline-block; padding: 12px 28px; background-color: var(--primary); color: #ffffff; text-decoration: none; border-radius: 8px; font-weight: 500; transition: all 0.2s;',
      'card' => 'padding: 24px; background: #ffffff; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin: 16px 0;',
      'cta' => 'background: linear-gradient(135deg, var(--primary), var(--secondary)); padding: 32px; border-radius: 12px; color: #ffffff; text-align: center; margin: 24px 0;',
      'social' => 'display: inline-block; padding: 8px; margin: 0 4px; border-radius: 50%; background: var(--background);',
      'rating' => 'display: flex; justify-content: center; gap: 8px; margin: 16px 0;',
  ];
@endphp

@switch($type)
  @case('button')
    <a href="{{ $href }}" style="{{ $styles['button'] }}">
      @if($icon)
        <img src="{{ asset('images/email/icons/' . $icon) }}" alt="" style="width: 16px; height: 16px; vertical-align: middle; margin-right: 8px;">
      @endif
      {{ $text }}
    </a>
    @break

  @case('card')
    <div style="{{ $styles['card'] }}">
      {{ $slot }}
    </div>
    @break

  @case('cta')
    <div style="{{ $styles['cta'] }}">
      <h3 style="color: #ffffff; margin-bottom: 16px;">{{ $text }}</h3>
      {{ $slot }}
    </div>
    @break

  @case('social')
    <div style="{{ $styles['social'] }}">
      <a href="{{ $href }}" style="text-decoration: none;">
        <img src="{{ asset('images/email/icons/' . $icon) }}" alt="{{ $text }}" style="width: 24px; height: 24px;">
      </a>
    </div>
    @break

  @case('rating')
    <div style="{{ $styles['rating'] }}">
      @for($i = 1; $i <= 5; $i++)
        <a href="{{ $href }}?rating={{ $i }}" style="text-decoration: none;">
          ⭐
        </a>
      @endfor
    </div>
    @break
@endswitch
