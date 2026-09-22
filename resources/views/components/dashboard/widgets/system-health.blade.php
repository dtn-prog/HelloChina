@props(['data' => []])

<div class="bg-white dark:bg-gray-800 rounded-lg shadow">
    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">System Health</h3>
    </div>
    <div class="p-4">
        <div class="mb-4">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Overall Health</span>
                <span class="text-sm font-medium {{ ($data['summary']['percentage'] ?? 0) >= 80 ? 'text-green-600' : 'text-yellow-600' }}">
                    {{ $data['summary']['percentage'] ?? 0 }}%
                </span>
            </div>
            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                <div class="bg-{{ ($data['summary']['percentage'] ?? 0) >= 80 ? 'green' : 'yellow' }}-500 h-2 rounded-full" 
                     style="width: {{ $data['summary']['percentage'] ?? 0 }}%"></div>
            </div>
        </div>

        <div class="space-y-3">
            @foreach($data['checks'] ?? [] as $check)
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <span class="flex-shrink-0 w-2.5 h-2.5 rounded-full 
                        {{ $check['status'] === 'healthy' ? 'bg-green-500' : ($check['status'] === 'warning' ? 'bg-yellow-500' : 'bg-red-500') }}"></span>
                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ $check['name'] }}</span>
                </div>
                <span class="text-sm text-gray-500 dark:text-gray-400">{{ $check['message'] }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>
