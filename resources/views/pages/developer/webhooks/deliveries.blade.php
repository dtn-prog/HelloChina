@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('developer.webhooks.show', $webhook) }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
            &larr; Back to Webhook
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Deliveries: {{ $webhook->name }}</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">All delivery history for this webhook</p>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-6 py-3">Event</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Response Code</th>
                    <th class="px-6 py-3">Duration</th>
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
                    <td class="px-6 py-4 text-gray-500">{{ isset($delivery->duration_ms) ? $delivery->duration_ms . 'ms' : '-' }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $delivery->created_at->diffForHumans() }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">No deliveries found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $deliveries->links() }}</div>
    </div>
</div>
@endsection
