<x-email-layout>
  <div class="header">
    <img src="{{ asset('images/email/logo.png') }}" alt="Citizen Alliance Logo" class="logo">

    <h1>{{ $issue->title }}</h1>
    <p class="subtitle">{{ $issue->description }}</p>
    <p class="date">{{ $issue->published_at->format('F j, Y') }}</p>
  </div>

  @if($issue->featured_image)
    <div class="hero">
      <img src="{{ $issue->featured_image }}" alt="{{ $issue->title }}" class="hero-image">
    </div>
  @endif

  <div class="content">
    {{-- Featured Story --}}
    @if($issue->featured_story)
      <x-emails.components.interactive type="card">
        <h2>{{ $issue->featured_story->title }}</h2>
        <p>{{ $issue->featured_story->excerpt }}</p>
        <x-emails.components.interactive
          type="button"
          :href="$issue->featured_story->url"
          text="Read More"
          icon="arrow-right.png"
        />
      </x-emails.components.interactive>
    @endif

    {{-- Latest Updates --}}
    <h2>Latest Updates</h2>
    @foreach($issue->updates as $update)
      <x-emails.components.interactive type="card">
        <div style="display: flex; gap: 16px;">
          @if($update->image)
            <img src="{{ $update->image }}" alt="" style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;">
          @endif
          <div>
            <h3>{{ $update->title }}</h3>
            <p>{{ $update->excerpt }}</p>
            <x-emails.components.interactive
              type="button"
              :href="$update->url"
              text="Learn More"
            />
          </div>
        </div>
      </x-emails.components.interactive>
    @endforeach

    {{-- Upcoming Events --}}
    @if($issue->events->count() > 0)
      <h2>Upcoming Events</h2>
      @foreach($issue->events as $event)
        <x-emails.components.interactive type="card">
          <div style="display: flex; align-items: center; gap: 16px;">
            <div style="text-align: center; min-width: 60px;">
              <div style="font-size: 24px; font-weight: bold;">{{ $event->start_date->format('d') }}</div>
              <div style="font-size: 14px;">{{ $event->start_date->format('M') }}</div>
            </div>
            <div>
              <h3>{{ $event->title }}</h3>
              <p>{{ $event->location }}</p>
              <x-emails.components.interactive
                type="button"
                :href="$event->registration_url"
                text="Register Now"
              />
            </div>
          </div>
        </x-emails.components.interactive>
      @endforeach
    @endif

    {{-- Call to Action --}}
    <x-emails.components.interactive
      type="cta"
      text="Get Involved!"
    >
      <p style="color: #ffffff; margin-bottom: 16px;">
        Join our upcoming community project and make a difference.
      </p>
      <x-emails.components.interactive
        type="button"
        href="{{ route('volunteer.register') }}"
        text="Volunteer Now"
        color="secondary"
      />
    </x-emails.components.interactive>

    {{-- Feedback Section --}}
    <div style="text-align: center; margin: 32px 0;">
      <h3>How useful was this newsletter?</h3>
      <x-emails.components.interactive
        type="rating"
        :href="route('newsletter.feedback', ['issue' => $issue->id])"
      />
    </div>

    {{-- Social Links --}}
    <div style="text-align: center; margin: 32px 0;">
      <h3>Follow Us</h3>
      <div style="margin-top: 16px;">
        <x-emails.components.interactive
          type="social"
          href="https://facebook.com/citizenalliance"
          icon="facebook.png"
          text="Facebook"
        />
        <x-emails.components.interactive
          type="social"
          href="https://twitter.com/citizenalliance"
          icon="twitter.png"
          text="Twitter"
        />
        <x-emails.components.interactive
          type="social"
          href="https://instagram.com/citizenalliance"
          icon="instagram.png"
          text="Instagram"
        />
      </div>
    </div>

    {{-- Footer --}}
    <div class="footer">
      <p>
        You're receiving this email because you subscribed to our newsletter.
        <br>
        <a href="{{ route('newsletter.preferences', ['subscriber' => $subscriber->id, 'token' => $subscriber->token]) }}">
          Update your preferences
        </a>
        or
        <a href="{{ $unsubscribeUrl }}">unsubscribe</a>
      </p>
    </div>
  </div>
</x-email-layout>
