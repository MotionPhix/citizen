<x-app-layout>
  <x-content-container class="py-16">
    <div class="text-center">
      <h1 class="text-4xl font-bold text-ca-primary dark:text-white mb-6">Thank You!</h1>
      <p class="text-lg text-gray-600 dark:text-gray-300 mb-8">
        Your donation of MWK {{ number_format($donation->amount, 2) }} has been successfully processed. We appreciate your support!
      </p>
      <a href="{{ route('home') }}"
         class="inline-flex items-center px-6 py-3 bg-ca-highlight text-white rounded-lg hover:bg-ca-primary transition-all duration-300">
        Return Home
      </a>
    </div>
  </x-content-container>
</x-app-layout>
