@props(['data' => []])

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
    @foreach($data['metrics'] ?? [] as $metric)
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $metric['label'] }}</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($metric['value']) }}</p>
            </div>
            <div class="p-3 rounded-full bg-{{ $metric['color'] }}-100 dark:bg-{{ $metric['color'] }}-900">
                <svg class="w-6 h-6 text-{{ $metric['color'] }}-600 dark:text-{{ $metric['color'] }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
        </div>
        @if($metric['change'] != 0)
        <div class="mt-2">
            <span class="text-sm {{ $metric['change'] > 0 ? 'text-green-600' : 'text-red-600' }}">
                {{ $metric['change'] > 0 ? '+' : '' }}{{ $metric['change'] }}%
            </span>
            <span class="text-sm text-gray-500">vs yesterday</span>
        </div>
        @endif
    </div>
    @endforeach
</div>
