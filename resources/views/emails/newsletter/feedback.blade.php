<x-mail::message>
  # We Value Your Feedback

  Hello {{ $subscriber->name ?? 'there' }},

  Thank you for reading our latest newsletter: **{{ $issue->title }}**

  We'd love to hear your thoughts on this issue. Your feedback helps us improve our content and deliver more value to you.

  <x-mail::button :url="route('newsletter.feedback', [$issue, $subscriber, $token])">
    Share Your Feedback
  </x-mail::button>

  It'll only take a minute, and your input is invaluable to us.

  Thanks,<br>
  {{ config('app.name') }}

  <x-slot:footer>
    You received this email because you're subscribed to our newsletter. You can <a href="{{ route('newsletter.preferences', [$subscriber, $token]) }}">manage your preferences</a> or <a href="{{ route('newsletter.unsubscribe', [$subscriber, $token]) }}">unsubscribe</a>.
  </x-slot:footer>
</x-mail::message>
