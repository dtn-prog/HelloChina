@props(['data' => []])

<div class="bg-white dark:bg-gray-800 rounded-lg shadow">
    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Login History</h3>
    </div>
    <div class="p-4">
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div class="bg-green-50 dark:bg-green-900 rounded-lg p-3">
                <p class="text-sm text-green-600 dark:text-green-400">Successful Today</p>
                <p class="text-2xl font-bold text-green-700 dark:text-green-300">{{ $data['success_today'] ?? 0 }}</p>
            </div>
            <div class="bg-red-50 dark:bg-red-900 rounded-lg p-3">
                <p class="text-sm text-red-600 dark:text-red-400">Failed Today</p>
                <p class="text-2xl font-bold text-red-700 dark:text-red-300">{{ $data['failed_today'] ?? 0 }}</p>
            </div>
        </div>

        @if(!empty($data['suspicious_ips']))
        <div class="mb-4">
            <h4 class="text-sm font-medium text-red-600 dark:text-red-400 mb-2">⚠️ Suspicious IPs</h4>
            @foreach($data['suspicious_ips'] as $ip)
            <div class="flex items-center justify-between text-sm py-1">
                <span class="font-mono text-gray-700 dark:text-gray-300">{{ $ip->ip_address }}</span>
                <span class="text-red-600">{{ $ip->attempts }} attempts</span>
            </div>
            @endforeach
        </div>
        @endif

        <div>
            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Recent Logins</h4>
            <div class="space-y-2">
                @forelse($data['recent_logins'] ?? [] as $login)
                <div class="flex items-center justify-between text-sm py-2 border-b border-gray-100 dark:border-gray-700 last:border-0">
                    <div>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $login->user_name ?? 'Unknown' }}</span>
                        <span class="text-gray-500 dark:text-gray-400 ml-2">{{ $login->ip_address }}</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium 
                            {{ $login->status === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $login->status }}
                        </span>
                        <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($login->created_at)->diffForHumans() }}</span>
                    </div>
                </div>
                @empty
                <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-2">No login history</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
