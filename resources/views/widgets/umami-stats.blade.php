@php
    $umamiIsV6 = version_compare(\Statamic\Statamic::version(), '6.0.0', '>=');
@endphp

@if ($umamiIsV6)
    {{-- Statamic 6: native Control Panel panel (gray panel + header + inset card) --}}
    <div class="@container/panel relative bg-gray-150 dark:bg-gray-950/35 w-full rounded-2xl p-1.75 [&:has(>[data-ui-panel-header])]:pt-0 h-full flex flex-col" data-ui-panel>
        <header class="px-4.5 py-3 flex items-center justify-between min-h-10" data-ui-panel-header>
            <div class="font-medium antialiased flex items-center gap-2 text-sm tracking-tight text-gray-700 dark:text-white" data-ui-heading>
                @if ($externalDashboardUrl)
                    <a href="{{ $externalDashboardUrl }}" target="_blank" class="flex items-center gap-1 hover:text-blue-600 dark:hover:text-blue-400">
                        <span>{{ $title }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-3.5 w-3.5 opacity-60">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                        </svg>
                    </a>
                @else
                    {{ $title }}
                @endif
            </div>
            @if (isset($stats['startAt'], $stats['endAt']))
                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $stats['startAt'] }} &ndash; {{ $stats['endAt'] }}</span>
            @endif
        </header>

        <div class="bg-white dark:bg-gray-850 rounded-xl ring ring-gray-200 dark:ring-x-0 dark:ring-b-0 dark:ring-gray-700/80 shadow-ui-md px-4 sm:px-4.5 py-5 flex-1" data-ui-card>
            @if ($error)
                <div class="text-red-600 dark:text-red-400">
                    <p>{{ $error }}</p>
                    <p class="text-xs mt-1 text-gray-500 dark:text-gray-400">{{ __('mynetx-umami::umami.stats.errors.config') }}</p>
                </div>
            @elseif (empty($stats))
                <div class="text-gray-600 dark:text-gray-400">
                    <p>{{ __('mynetx-umami::umami.stats.errors.no_data') }}</p>
                    <p class="text-xs mt-1 text-gray-500 dark:text-gray-400">{{ __('mynetx-umami::umami.stats.errors.no_data_for_period') }}</p>
                </div>
            @else
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 w-full">
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-3">
                        <div class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">{{ __('mynetx-umami::umami.stats.labels.page_views') }}</div>
                        <div class="text-2xl font-bold mt-1 text-gray-900 dark:text-white">{{ $stats['pageviews']['value'] ?? 0 }}</div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-3">
                        <div class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">{{ __('mynetx-umami::umami.stats.labels.unique_visitors') }}</div>
                        <div class="text-2xl font-bold mt-1 text-gray-900 dark:text-white">{{ $stats['visitors']['value'] ?? 0 }}</div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-3">
                        <div class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">{{ __('mynetx-umami::umami.stats.labels.visits') }}</div>
                        <div class="text-2xl font-bold mt-1 text-gray-900 dark:text-white">{{ $stats['visits']['value'] ?? 0 }}</div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-3">
                        <div class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">{{ __('mynetx-umami::umami.stats.labels.avg_visit_duration') }}</div>
                        <div class="text-2xl font-bold mt-1 text-gray-900 dark:text-white">
                            @if (isset($stats['totaltime']['value'], $stats['visits']['value']) && $stats['visits']['value'] > 0)
                                {{ gmdate("i:s", $stats['totaltime']['value'] / $stats['visits']['value']) }}
                            @else
                                00:00
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@else
    {{-- Statamic 5: classic Control Panel card --}}
    <div class="card p-0 content">
        <div class="flex justify-between items-center" style="padding: 1.5rem 2rem 0.5rem 2rem;">
            <h2 class="mt-1 mb-0">
                @if ($externalDashboardUrl)
                    <a href="{{ $externalDashboardUrl }}" target="_blank" class="flex gap-1 items-baseline hover:text-blue-600 dark:hover:text-blue-400">
                        <span>{{ $title }}</span>
                        <span class="ml-1 text-xs text-gray-600 dark:text-dark-150">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                            </svg>
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
            <div class="text-red-500 dark:text-red-400" style="padding: 0 2rem 1.5rem 2rem;">
                <p>{{ $error }}</p>
                <p class="text-xs mt-1 dark:text-dark-200">{{ __('mynetx-umami::umami.stats.errors.config') }}</p>
            </div>
        @elseif(empty($stats))
            <div class="text-gray-700 dark:text-dark-200" style="padding: 0 2rem 1.5rem 2rem;">
                <p>{{ __('mynetx-umami::umami.stats.errors.no_data') }}</p>
                <p class="text-xs mt-1 dark:text-dark-150">{{ __('mynetx-umami::umami.stats.errors.no_data_for_period') }}</p>
            </div>
        @else
            <div style="padding: 0 2rem 1.5rem 2rem;">
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
@endif
