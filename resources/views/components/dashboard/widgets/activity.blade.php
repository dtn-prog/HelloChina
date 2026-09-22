@props(['data' => []])

<div class="bg-white dark:bg-gray-800 rounded-lg shadow">
    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Activity</h3>
    </div>
    <div class="p-4">
        @forelse($data['activities'] ?? [] as $activity)
        <div class="flex items-start space-x-3 py-3 border-b border-gray-100 dark:border-gray-700 last:border-0">
            <div class="flex-shrink-0">
                @if($activity['causer_avatar'])
                    <img src="{{ $activity['causer_avatar'] }}" alt="{{ $activity['causer'] }}" class="w-8 h-8 rounded-full">
                @else
                    <div class="w-8 h-8 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ substr($activity['causer'], 0, 1) }}</span>
                    </div>
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm text-gray-900 dark:text-white">
                    <span class="font-medium">{{ $activity['causer'] }}</span>
                    {{ $activity['description'] }}
                </p>
                <div class="flex items-center space-x-2 mt-1">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                        {{ $activity['event'] ?? 'N/A' }}
                    </span>
                    @if($activity['subject_type'])
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                        {{ $activity['subject_type'] }}
                    </span>
                    @endif
                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $activity['time'] }}</span>
                </div>
            </div>
        </div>
        @empty
        <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">No recent activity</p>
        @endforelse
    </div>
    @if(($data['total'] ?? 0) > 10)
    <div class="p-4 border-t border-gray-200 dark:border-gray-700">
        <a href="{{ route('audit.index') }}" class="text-sm text-blue-600 hover:text-blue-500">View all activity →</a>
    </div>
    @endif
</div>
