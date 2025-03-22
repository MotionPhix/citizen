@props(['class' => ''])

<div class="{{ $class }}">
  <form action="{{ route('donation.process') }}" method="POST" class="space-y-6">
    @csrf
    <div>
      <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
      <input type="text" name="name" id="name" required
             class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300">
    </div>
    <div>
      <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
      <input type="email" name="email" id="email" required
             class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300">
    </div>
    <div>
      <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Amount (MWK)</label>
      <input type="number" name="amount" id="amount" min="1" step="0.01" required
             class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300">
    </div>
    <div>
      <button
        type="submit"
        class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
        Donate Now
      </button>
    </div>
  </form>
</div>
