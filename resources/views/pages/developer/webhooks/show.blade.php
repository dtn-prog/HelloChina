@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('developer.webhooks.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
            &larr; Back
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $webhook->name }}</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $webhook->url }}</p>
        </div>
        <span class="ml-auto px-2 py-1 text-xs font-medium rounded {{ $webhook->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
            {{ $webhook->is_active ? 'Active' : 'Inactive' }}
        </span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white mb-3">Details</h2>
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-gray-500 uppercase">Events</p>
                    <div class="flex flex-wrap gap-1 mt-1">
                        @foreach($webhook->events as $event)
                        <span class="px-2 py-0.5 text-xs bg-gray-100 text-gray-700 rounded dark:bg-gray-700 dark:text-gray-300">{{ $event }}</span>
                        @endforeach
                    </div>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase">Retry Count</p>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $webhook->retry_count }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase">Created</p>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $webhook->created_at->format('Y-m-d H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b dark:border-gray-700">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white">Recent Deliveries</h2>
        </div>
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-6 py-3">Event</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Response</th>
                    <th class="px-6 py-3">Time</th>
                </tr>
            </thead>
            <tbody>
                @forelse($deliveries as $delivery)
                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-6 py-4 text-gray-900 dark:text-white">{{ $delivery->event }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded {{ $delivery->status === 'success' ? 'bg-green-100 text-green-800' : ($delivery->status === 'failed' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ $delivery->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-500">{{ $delivery->response_code ?? '-' }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $delivery->created_at->diffForHumans() }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">No deliveries yet</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $deliveries->links() }}</div>
    </div>
</div>
@endsection
