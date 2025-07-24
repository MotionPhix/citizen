<div class="space-y-6">
    <!-- Content Header -->
    <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
        <div class="flex items-center gap-3 mb-2">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                {{ match($record->type) {
                    'story' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                    'event' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                    'update' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                    'announcement' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                    default => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200'
                } }}">
                {{ ucfirst($record->type) }}
            </span>

            @if($record->is_featured)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200">
                    ⭐ Featured
                </span>
            @endif

            @if($record->category)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                    {{ ucfirst($record->category) }}
                </span>
            @endif
        </div>

        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            {{ $record->title }}
        </h1>

        @if($record->excerpt)
            <p class="mt-2 text-gray-600 dark:text-gray-400">
                {{ $record->excerpt }}
            </p>
        @endif
    </div>

    <!-- Featured Image -->
    @if($record->getFirstMediaUrl('images'))
        <div class="rounded-lg overflow-hidden">
            <img src="{{ $record->getFirstMediaUrl('images') }}"
                 alt="{{ $record->title }}"
                 class="w-full h-64 object-cover">
        </div>
    @endif

    <!-- Type-specific Information -->
    @if($record->type === 'event' && $record->metadata)
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
            <h3 class="font-semibold text-green-900 dark:text-green-100 mb-2">Event Details</h3>
            <div class="space-y-2 text-sm">
                @if(isset($record->metadata['location']))
                    <div class="flex items-center gap-2">
                        <span class="font-medium">📍 Location:</span>
                        <span>{{ $record->metadata['location'] }}</span>
                    </div>
                @endif

                @if(isset($record->metadata['start_date']))
                    <div class="flex items-center gap-2">
                        <span class="font-medium">📅 Start:</span>
                        <span>{{ \Carbon\Carbon::parse($record->metadata['start_date'])->format('M j, Y g:i A') }}</span>
                    </div>
                @endif

                @if(isset($record->metadata['end_date']))
                    <div class="flex items-center gap-2">
                        <span class="font-medium">🏁 End:</span>
                        <span>{{ \Carbon\Carbon::parse($record->metadata['end_date'])->format('M j, Y g:i A') }}</span>
                    </div>
                @endif

                @if(isset($record->metadata['capacity']))
                    <div class="flex items-center gap-2">
                        <span class="font-medium">👥 Capacity:</span>
                        <span>{{ $record->metadata['capacity'] }} people</span>
                    </div>
                @endif

                @if(isset($record->metadata['registration_url']))
                    <div class="flex items-center gap-2">
                        <span class="font-medium">🔗 Registration:</span>
                        <a href="{{ $record->metadata['registration_url'] }}"
                           target="_blank"
                           class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200">
                            Register Here
                        </a>
                    </div>
                @endif
            </div>
        </div>
    @endif

    @if($record->type === 'story' && $record->metadata)
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
            <h3 class="font-semibold text-blue-900 dark:text-blue-100 mb-2">Story Details</h3>
            <div class="space-y-2 text-sm">
                @if(isset($record->metadata['author']))
                    <div class="flex items-center gap-2">
                        <span class="font-medium">✍️ Author:</span>
                        <span>{{ $record->metadata['author'] }}</span>
                    </div>
                @endif

                @if(isset($record->metadata['read_time']))
                    <div class="flex items-center gap-2">
                        <span class="font-medium">⏱️ Read Time:</span>
                        <span>{{ $record->metadata['read_time'] }} minutes</span>
                    </div>
                @endif

                @if(isset($record->metadata['source']))
                    <div class="flex items-center gap-2">
                        <span class="font-medium">📰 Source:</span>
                        <span>{{ ucfirst($record->metadata['source']) }}</span>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Content -->
    @if($record->content)
        <div class="prose prose-gray dark:prose-invert max-w-none">
            {!! $record->content !!}
        </div>
    @endif

    <!-- External Link -->
    @if($record->url)
        <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
            <a href="{{ $record->url }}"
               target="_blank"
               class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200 font-medium">
                🔗 Read More
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                </svg>
            </a>
        </div>
    @endif

    <!-- Metadata -->
    <div class="border-t border-gray-200 dark:border-gray-700 pt-4 text-sm text-gray-500 dark:text-gray-400">
        <div class="flex justify-between items-center">
            <div>
                Order: {{ $record->order }} •
                Published: {{ $record->published_at?->format('M j, Y g:i A') }}
            </div>
            <div>
                Created: {{ $record->created_at->diffForHumans() }}
            </div>
        </div>
    </div>
</div>
