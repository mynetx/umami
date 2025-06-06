<div class="card p-0 content">
    <div class="flex justify-between items-center p-2">
        <h2 class="mt-1 mb-0">
            @if($externalDashboardUrl)
                <a href="{{ $externalDashboardUrl }}" target="_blank" class="flex gap-1 items-baseline hover:text-blue-600 dark:hover:text-blue-400">
                    <span>{{ $title }}</span>
                    <span class="ml-1 text-xs text-gray-600 dark:text-dark-150">
                        @cp_svg('icons/light/external-link', 'h-4 w-4')
                    </span>
                </a>
            @else
                {{ $title }}
            @endif
        </h2>
        <div>
            @if(isset($stats['startAt']) && isset($stats['endAt']))
                <span class="text-xs text-gray-700 dark:text-dark-200">{{ $stats['startAt'] }} - {{ $stats['endAt'] }}</span>
            @endif
        </div>
    </div>

    @if($error)
        <div class="px-2 pb-2 text-red-500 dark:text-red-400">
            <p>{{ $error }}</p>
            <p class="text-xs mt-1 dark:text-dark-200">{{ __('mynetx-umami::umami.stats.errors.config') }}</p>
        </div>
    @elseif(empty($stats))
        <div class="px-2 pb-2 text-gray-700 dark:text-dark-200">
            <p>{{ __('mynetx-umami::umami.stats.errors.no_data') }}</p>
            <p class="text-xs mt-1 dark:text-dark-150">{{ __('mynetx-umami::umami.stats.errors.no_data_for_period') }}</p>
        </div>
    @else
        <div class="p-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 w-full">
                <div class="bg-gray-100 dark:bg-dark-700 p-3 rounded">
                    <div class="text-xs uppercase text-gray-700 dark:text-dark-200">{{ __('mynetx-umami::umami.stats.labels.page_views') }}</div>
                    <div class="text-2xl font-bold mt-1 dark:text-white">{{ $stats['pageviews']['value'] ?? 0 }}</div>
                </div>
                <div class="bg-gray-100 dark:bg-dark-700 p-3 rounded">
                    <div class="text-xs uppercase text-gray-700 dark:text-dark-200">{{ __('mynetx-umami::umami.stats.labels.unique_visitors') }}</div>
                    <div class="text-2xl font-bold mt-1 dark:text-white">{{ $stats['visitors']['value'] ?? 0 }}</div>
                </div>
                <div class="bg-gray-100 dark:bg-dark-700 p-3 rounded">
                    <div class="text-xs uppercase text-gray-700 dark:text-dark-200">{{ __('mynetx-umami::umami.stats.labels.visits') }}</div>
                    <div class="text-2xl font-bold mt-1 dark:text-white">{{ $stats['visits']['value'] ?? 0 }}</div>
                </div>
                <div class="bg-gray-100 dark:bg-dark-700 p-3 rounded">
                    <div class="text-xs uppercase text-gray-700 dark:text-dark-200">{{ __('mynetx-umami::umami.stats.labels.avg_visit_duration') }}</div>
                    <div class="text-2xl font-bold mt-1 dark:text-white">
                        @if(isset($stats['totaltime']['value']) && isset($stats['visits']['value']) && $stats['visits']['value'] > 0)
                            {{ gmdate("i:s", $stats['totaltime']['value'] / $stats['visits']['value']) }}
                        @else
                            00:00
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
