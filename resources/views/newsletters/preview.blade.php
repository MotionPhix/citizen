<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $issue->title }} - Preview</title>

  @include('emails.components.styles')

  @if($previewMode)
    <style>
      .preview-banner {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        background: var(--primary);
        color: white;
        text-align: center;
        padding: var(--space-2);
        z-index: 1000;
      }

      .preview-controls {
        position: fixed;
        bottom: var(--space-5);
        right: var(--space-5);
        background: var(--background-alt);
        padding: var(--space-4);
        border-radius: var(--radius-lg);
        box-shadow: 0 2px 4px var(--shadow);
        z-index: 1000;
      }

      body { margin-top: 40px; }
    </style>
  @endif
</head>
<body>
@if($previewMode)
  <div class="preview-banner">
    Preview Mode - This is how your newsletter will appear to subscribers
  </div>
@endif

<div style="max-width: 600px; margin: 0 auto; padding: var(--space-6);">
  {{-- Newsletter Header --}}
  <x-emails.components.interactive type="card">
    <h1 style="margin-top: 0; color: var(--text);">{{ $issue->title }}</h1>
    @if($issue->description)
      <p style="color: var(--text-light);">{{ $issue->description }}</p>
    @endif
  </x-emails.components.interactive>

  {{-- Featured Image --}}
  @if($issue->getFirstMedia('featured_images'))
    <div style="margin: var(--space-6) 0;">
      <img src="{{ $issue->getFirstMedia('featured_images')->getUrl() }}"
           alt="{{ $issue->title }}"
           style="width: 100%; height: auto; border-radius: var(--radius-lg);">
    </div>
  @endif

  {{-- Featured Stories --}}
  @if($issue->stories->isNotEmpty())
    <h2 style="color: var(--text); margin-top: var(--space-8);">Featured Stories</h2>
    @foreach($issue->stories->sortBy('order') as $story)
      <x-emails.components.interactive type="card">
        <h3 style="margin-top: 0; color: var(--text);">{{ $story->title }}</h3>
        <p style="color: var(--text-light);">{{ $story->excerpt }}</p>
        @if($story->url)
          <x-emails.components.interactive
            type="button"
            :href="$story->url"
            text="Read More"
          />
        @endif
      </x-emails.components.interactive>
    @endforeach
  @endif

  {{-- Latest Updates --}}
  @if($issue->updates->isNotEmpty())
    <h2 style="color: var(--text); margin-top: var(--space-8);">Latest Updates</h2>
    @foreach($issue->updates->sortBy('order') as $update)
      <x-emails.components.interactive type="card">
                    <span style="display: inline-block; padding: var(--space-1) var(--space-2); background: var(--{{ $update->category }}); color: white; border-radius: var(--radius-full); font-size: 0.875rem;">
                        {{ ucfirst($update->category) }}
                    </span>
        <h3 style="margin-top: var(--space-2); color: var(--text);">{{ $update->title }}</h3>
        <p style="color: var(--text-light);">{{ $update->excerpt }}</p>
        @if($update->url)
          <x-emails.components.interactive
            type="button"
            :href="$update->url"
            text="Learn More"
          />
        @endif
      </x-emails.components.interactive>
    @endforeach
  @endif

  {{-- Upcoming Events --}}
  @if($issue->events->isNotEmpty())
    <h2 style="color: var(--text); margin-top: var(--space-8);">Upcoming Events</h2>
    @foreach($issue->events->sortBy('start_date') as $event)
      <x-emails.components.interactive type="card">
        <div style="display: flex; gap: var(--space-4);">
          <div style="text-align: center; min-width: 60px;">
            <div style="font-size: 24px; font-weight: var(--font-weight-bold); color: var(--primary);">
              {{ $event->start_date->format('d') }}
            </div>
            <div style="font-size: 14px; color: var(--text-light);">
              {{ $event->start_date->format('M') }}
            </div>
          </div>
          <div>
            <h3 style="margin-top: 0; color: var(--text);">{{ $event->title }}</h3>
            <p style="color: var(--text-light);">{{ $event->description }}</p>
            <p style="color: var(--text-light);">
              <strong>Location:</strong> {{ $event->location }}
            </p>
            @if($event->capacity)
              <p style="color: var(--text-light);">
                <strong>Capacity:</strong> {{ $event->capacity }} attendees
              </p>
            @endif
            @if($event->registration_url)
              <x-emails.components.interactive
                type="button"
                :href="$event->registration_url"
                text="Register Now"
              />
            @endif
          </div>
        </div>
      </x-emails.components.interactive>
    @endforeach
  @endif

  {{-- Footer --}}
  <x-emails.components.interactive type="cta" text="Stay Connected">
    <div style="display: flex; justify-content: center; gap: var(--space-4);">
      <x-emails.components.interactive
        type="social"
        href="#"
        text="Twitter"
        icon="twitter.png"
      />
      <x-emails.components.interactive
        type="social"
        href="#"
        text="Facebook"
        icon="facebook.png"
      />
      <x-emails.components.interactive
        type="social"
        href="#"
        text="LinkedIn"
        icon="linkedin.png"
      />
    </div>
  </x-emails.components.interactive>
</div>

@if($previewMode)
  <div class="preview-controls">
    <x-emails.components.interactive
      type="button"
      :href="url()->previous()"
      text="Back to Admin"
    />
    <button onclick="window.print()"
            style="display: inline-block; padding: var(--space-3) var(--space-4); background: var(--primary); color: white; border: none; border-radius: var(--radius-md); cursor: pointer; margin-left: var(--space-2);">
      Print Preview
    </button>
  </div>
@endif
</body>
</html>
