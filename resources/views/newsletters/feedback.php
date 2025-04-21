<x-guest-layout>
  <div class="max-w-2xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow rounded-lg">
      <div class="px-4 py-5 sm:p-6">
        <h2 class="text-lg font-medium text-gray-900">Newsletter Feedback</h2>
        <p class="mt-1 text-sm text-gray-500">Tell us what you think about "{{ $issue->title }}"</p>

        @if (session('success'))
        <div class="mt-4 p-4 bg-green-50 rounded-md">
          <p class="text-sm text-green-700">{{ session('success') }}</p>
        </div>
        @endif

        <form action="{{ route('newsletter.feedback.store', [$issue, $subscriber, $token]) }}" method="POST" class="mt-6 space-y-6">
          @csrf

          <div>
            <label class="block text-sm font-medium text-gray-700">Rating</label>
            <div class="mt-2 flex items-center space-x-2">
              @for ($i = 1; $i <= 5; $i++)
              <button type="button"
                      onclick="setRating({{ $i }})"
                      class="rating-star text-2xl text-gray-400 hover:text-yellow-400 focus:outline-none">
                ★
              </button>
              @endfor
              <input type="hidden" name="rating" id="rating" required>
            </div>
          </div>

          <div>
            <label for="comment" class="block text-sm font-medium text-gray-700">Comments (Optional)</label>
            <div class="mt-1">
                            <textarea id="comment"
                                      name="comment"
                                      rows="4"
                                      class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                      placeholder="Tell us what you liked or how we can improve..."></textarea>
            </div>
          </div>

          <div class="flex justify-end pt-4">
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
              Submit Feedback
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    function setRating(value) {
      document.getElementById('rating').value = value;
      const stars = document.querySelectorAll('.rating-star');
      stars.forEach((star, index) => {
        star.classList.toggle('text-yellow-400', index < value);
        star.classList.toggle('text-gray-400', index >= value);
      });
    }
  </script>
</x-guest-layout>
