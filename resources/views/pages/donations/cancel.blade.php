<x-app-layout>
  <x-content-container class="py-16">
    <div class="text-center">
      <h1 class="text-4xl font-bold text-ca-primary dark:text-white mb-6">Donation Cancelled</h1>
      <p class="text-lg text-gray-600 dark:text-gray-300 mb-8">
        Your donation of MWK {{ number_format($donation->amount, 2) }} was not completed. If this was a mistake, please try again.
      </p>
      <a href="{{ route('donation.form') }}"
         class="inline-flex items-center px-6 py-3 bg-ca-highlight text-white rounded-lg hover:bg-ca-primary transition-all duration-300">
        Try Again
      </a>
    </div>
  </x-content-container>
</x-app-layout>
