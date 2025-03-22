@props(['member'])

{{--<div class="bg-white rounded-lg shadow-lg overflow-hidden hover-card">--}}
{{--  <div class="aspect-w-4 aspect-h-3">--}}
{{--    <img--}}
{{--      src="{{ asset($member['image']) }}"--}}
{{--       alt="{{ $member['name'] }}"--}}
{{--       class="w-full h-full object-cover">--}}
{{--  </div>--}}

{{--  <div class="p-6">--}}
{{--    <h3 class="text-xl font-semibold mb-2 font-display">{{ $member['name'] }}</h3>--}}
{{--    <p class="text-blue-600 mb-4">{{ $member['position'] }}</p>--}}
{{--    <p class="text-gray-600 mb-4">{{ $member['bio'] }}</p>--}}
{{--    <div class="flex space-x-4">--}}
{{--      <x-social-icons :links="$member['social']" />--}}
{{--    </div>--}}
{{--  </div>--}}
{{--</div>--}}

{{--<div class="flex flex-col rounded-xl bg-white border border-gray-200 dark:bg-neutral-900 dark:border-neutral-700">--}}
{{--  <img--}}
{{--    class="rounded-t-lg h-48 object-cover"--}}
{{--    src="https://images.unsplash.com/photo-1514222709107-a180c68d72b4?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=facearea&facepad=2&w=320&h=320&q=80"--}}
{{--    alt="{{ $member['name'] }}">--}}

{{--  <div class="px-4 md:px-6 pt-4 md:pt-6">--}}

{{--    <div class="grow">--}}
{{--      <h3 class="font-medium text-gray-800 dark:text-neutral-200">--}}
{{--        {{ $member['name'] }}--}}
{{--      </h3>--}}

{{--      <p class="text-xs uppercase text-gray-500 dark:text-neutral-500">--}}
{{--        {{ $member['position'] }}--}}
{{--      </p>--}}
{{--    </div>--}}

{{--  </div>--}}

{{--  <p class="px-4 md:px-6 mt-3 text-gray-500 dark:text-neutral-500">--}}
{{--    {{ $member['bio'] }}--}}
{{--  </p>--}}

{{--  <div class="flex-1"></div>--}}

{{--  <!-- Social Brands -->--}}
{{--  <div class="mt-3 space-x-1 px-4 md:px-6 pb-4 md:pb-6">--}}
{{--    <a class="inline-flex justify-center items-center size-8 text-sm font-semibold rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-neutral-400 dark:border-neutral-700 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700" href="#">--}}
{{--      <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">--}}
{{--        <path d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334 0-.14 0-.282-.006-.422A6.685 6.685 0 0 0 16 3.542a6.658 6.658 0 0 1-1.889.518 3.301 3.301 0 0 0 1.447-1.817 6.533 6.533 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.325 9.325 0 0 1-6.767-3.429 3.289 3.289 0 0 0 1.018 4.382A3.323 3.323 0 0 1 .64 6.575v.045a3.288 3.288 0 0 0 2.632 3.218 3.203 3.203 0 0 1-.865.115 3.23 3.23 0 0 1-.614-.057 3.283 3.283 0 0 0 3.067 2.277A6.588 6.588 0 0 1 .78 13.58a6.32 6.32 0 0 1-.78-.045A9.344 9.344 0 0 0 5.026 15z"/>--}}
{{--      </svg>--}}
{{--    </a>--}}
{{--    <a class="inline-flex justify-center items-center size-8 text-sm font-semibold rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-neutral-400 dark:border-neutral-700 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700" href="#">--}}
{{--      <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">--}}
{{--        <path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.012 8.012 0 0 0 16 8c0-4.42-3.58-8-8-8z"/>--}}
{{--      </svg>--}}
{{--    </a>--}}
{{--    <a class="inline-flex justify-center items-center size-8 text-sm font-semibold rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-neutral-400 dark:border-neutral-700 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700" href="#">--}}
{{--      <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">--}}
{{--        <path d="M3.362 10.11c0 .926-.756 1.681-1.681 1.681S0 11.036 0 10.111C0 9.186.756 8.43 1.68 8.43h1.682v1.68zm.846 0c0-.924.756-1.68 1.681-1.68s1.681.756 1.681 1.68v4.21c0 .924-.756 1.68-1.68 1.68a1.685 1.685 0 0 1-1.682-1.68v-4.21zM5.89 3.362c-.926 0-1.682-.756-1.682-1.681S4.964 0 5.89 0s1.68.756 1.68 1.68v1.682H5.89zm0 .846c.924 0 1.68.756 1.68 1.681S6.814 7.57 5.89 7.57H1.68C.757 7.57 0 6.814 0 5.89c0-.926.756-1.682 1.68-1.682h4.21zm6.749 1.682c0-.926.755-1.682 1.68-1.682.925 0 1.681.756 1.681 1.681s-.756 1.681-1.68 1.681h-1.681V5.89zm-.848 0c0 .924-.755 1.68-1.68 1.68A1.685 1.685 0 0 1 8.43 5.89V1.68C8.43.757 9.186 0 10.11 0c.926 0 1.681.756 1.681 1.68v4.21zm-1.681 6.748c.926 0 1.682.756 1.682 1.681S11.036 16 10.11 16s-1.681-.756-1.681-1.68v-1.682h1.68zm0-.847c-.924 0-1.68-.755-1.68-1.68 0-.925.756-1.681 1.68-1.681h4.21c.924 0 1.68.756 1.68 1.68 0 .926-.756 1.681-1.68 1.681h-4.21z"/>--}}
{{--      </svg>--}}
{{--    </a>--}}
{{--  </div>--}}
{{--  <!-- End Social Brands -->--}}
{{--</div>--}}
{{--<!-- End Col -->--}}

@props(['member'])

<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden">
  <div class="aspect-w-3 aspect-h-4">
    <img
      src="{{ $member['image'] }}"
      alt="{{ $member['name'] }}"
      class="w-full h-full object-cover"
    >
  </div>
  <div class="p-6">
    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-1">
      {{ $member['name'] }}
    </h3>
    <p class="text-ca-primary dark:text-ca-primary/90 font-medium mb-4">
      {{ $member['position'] }}
    </p>
    <p class="text-gray-600 dark:text-gray-300">
      {{ $member['bio'] }}
    </p>

    <div class="flex items-center space-x-4 mt-6">
      @if(isset($member['linkedin']))
        <a
          href="{{ $member['linkedin'] }}"
          target="_blank"
          rel="noopener noreferrer"
          class="text-gray-400 hover:text-ca-primary dark:hover:text-white transition-colors duration-300"
        >
          <span class="sr-only">LinkedIn</span>
          <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
            <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.784 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
          </svg>
        </a>
      @endif

      @if(isset($member['twitter']))
        <a
          href="{{ $member['twitter'] }}"
          target="_blank"
          rel="noopener noreferrer"
          class="text-gray-400 hover:text-ca-primary dark:hover:text-white transition-colors duration-300"
        >
          <span class="sr-only">Twitter</span>
          <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.061a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.937 4.937 0 004.604 3.417 9.868 9.868 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.054 0 13.999-7.496 13.999-13.986 0-.209 0-.42-.015-.63a9.936 9.936 0 002.46-2.548l-.047-.02z"/>
          </svg>
        </a>
      @endif
    </div>
  </div>
</div>
