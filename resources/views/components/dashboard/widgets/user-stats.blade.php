@props(['data' => []])

<div class="bg-white dark:bg-gray-800 rounded-lg shadow">
    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">User Statistics</h3>
    </div>
    <div class="p-4">
        <div class="mb-6">
            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Registration Trend (Last 7 Days)</h4>
            <div class="flex items-end space-x-2 h-32">
                @php
                    $trend = $data['registration_trend'] ?? [];
                    $counts = array_map(fn($d) => (int)($d['count'] ?? 0), $trend);
                    $maxCount = max(array_merge($counts, [1]));
                @endphp
                @foreach($trend as $day)
                <div class="flex-1 flex flex-col items-center">
                    @php $height = $maxCount > 0 ? round(((int)($day['count'] ?? 0) / $maxCount) * 100) : 0; @endphp
                    <div class="w-full bg-blue-500 dark:bg-blue-400 rounded-t"
                         style="height: {{ max(4, $height) }}%"></div>
                    <span class="text-xs text-gray-500 mt-1">{{ substr($day['date'] ?? '', -2) }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">By Status</h4>
                <div class="space-y-2">
                    @foreach($data['status_distribution'] ?? [] as $status => $count)
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400 capitalize">{{ $status }}</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $count }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            <div>
                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">By Role</h4>
                <div class="space-y-2">
                    @foreach($data['role_distribution'] ?? [] as $role => $count)
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">{{ $role }}</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $count }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
