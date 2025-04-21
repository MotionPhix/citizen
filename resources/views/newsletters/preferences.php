<x-guest-layout>
  <div class="max-w-2xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow rounded-lg">
      <div class="px-4 py-5 sm:p-6">
        <h2 class="text-lg font-medium text-gray-900">Newsletter Preferences</h2>
        <p class="mt-1 text-sm text-gray-500">Customize how you receive our newsletter.</p>

        @if (session('success'))
        <div class="mt-4 p-4 bg-green-50 rounded-md">
          <p class="text-sm text-green-700">{{ session('success') }}</p>
        </div>
        @endif

        <form action="{{ route('newsletter.preferences.update', [$subscriber, $token]) }}" method="POST" class="mt-6 space-y-6">
          @csrf
          @method('PUT')

          <div>
            <label class="block text-sm font-medium text-gray-700">Email Frequency</label>
            <select name="frequency" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
              @foreach(\App\Models\Subscriber::FREQUENCIES as $value => $label)
              <option value="{{ $value }}" {{ ($subscriber->preferences['frequency'] ?? 'weekly') === $value ? 'selected' : '' }}>
                {{ $label }}
              </option>
              @endforeach
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Content Categories</label>
            <div class="mt-2 space-y-2">
              @foreach(\App\Models\Subscriber::CATEGORIES as $value => $label)
              <div class="flex items-center">
                <input type="checkbox"
                       name="categories[]"
                       value="{{ $value }}"
                       {{ in_array($value, $subscriber->preferences['categories'] ?? []) ? 'checked' : '' }}
                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                <label class="ml-2 text-sm text-gray-700">{{ $label }}</label>
              </div>
              @endforeach
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Time Zone</label>
            <select name="timezone" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
              @foreach(\App\Models\Subscriber::TIMEZONES as $value => $label)
              <option value="{{ $value }}" {{ ($subscriber->preferences['timezone'] ?? 'UTC') === $value ? 'selected' : '' }}>
                {{ $label }}
              </option>
              @endforeach
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Preferred Time</label>
            <input type="time"
                   name="time_of_day"
                   value="{{ $subscriber->preferences['time_of_day'] ?? '08:00' }}"
                   class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Email Format</label>
            <div class="mt-2 space-x-4">
              @foreach(['html' => 'HTML (Rich Text)', 'text' => 'Plain Text'] as $value => $label)
              <div class="inline-flex items-center">
                <input type="radio"
                       name="format"
                       value="{{ $value }}"
                       {{ ($subscriber->preferences['format'] ?? 'html') === $value ? 'checked' : '' }}
                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                <label class="ml-2 text-sm text-gray-700">{{ $label }}</label>
              </div>
              @endforeach
            </div>
          </div>

          <div>
            <div class="flex items-center">
              <input type="checkbox"
                     name="digest"
                     value="1"
                     {{ ($subscriber->preferences['digest'] ?? true) ? 'checked' : '' }}
              class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
              <label class="ml-2 text-sm text-gray-700">
                Combine multiple updates into a single digest
              </label>
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Additional Notifications</label>
            <div class="space-y-2">
              <div class="flex items-center">
                <input type="checkbox"
                       name="notifications[browser]"
                       value="1"
                       {{ ($subscriber->preferences['notifications']['browser'] ?? false) ? 'checked' : '' }}
                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                <label class="ml-2 text-sm text-gray-700">
                  Browser notifications
                </label>
              </div>
              <div class="flex items-center">
                <input type="checkbox"
                       name="notifications[mobile]"
                       value="1"
                       {{ ($subscriber->preferences['notifications']['mobile'] ?? false) ? 'checked' : '' }}
                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                <label class="ml-2 text-sm text-gray-700">
                  Mobile notifications
                </label>
              </div>
            </div>
          </div>

          <div class="flex items-center justify-between pt-4">
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
              Save Preferences
            </button>

            <a href="{{ route('newsletter.unsubscribe', [$subscriber, $token]) }}" class="text-sm text-gray-500 hover:text-gray-700">
              Unsubscribe
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>
</x-guest-layout>
