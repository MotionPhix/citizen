<x-mail::message>
  # Manage Your Newsletter Preferences

  Hello {{ $subscriber->name ?? 'there' }},

  You can customize how you receive our newsletters by clicking the button below:

  <x-mail::button :url="route('newsletter.preferences', [$subscriber, $token])">
    Update Preferences
  </x-mail::button>

  Your current preferences:
  - Frequency: {{ ucfirst($subscriber->preferences['frequency'] ?? 'weekly') }}
  - Format: {{ ucfirst($subscriber->preferences['format'] ?? 'html') }}
  - Time of day: {{ $subscriber->preferences['time_of_day'] ?? '08:00' }} ({{ $subscriber->preferences['timezone'] ?? 'UTC' }})
  - Categories: {{ implode(', ', array_map('ucfirst', $subscriber->preferences['categories'] ?? ['news', 'announcements'])) }}

  If you didn't request this email, you can safely ignore it.

  Thanks,<br>
  {{ config('app.name') }}

  <x-slot:footer>
    If you no longer wish to receive these emails, you can <a href="{{ route('newsletter.unsubscribe', [$subscriber, $token]) }}">unsubscribe here</a>.
  </x-slot:footer>
</x-mail::message>
