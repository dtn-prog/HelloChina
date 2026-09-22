@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Active Sessions</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Manage your active login sessions</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-6 py-3">Device / Browser</th>
                    <th class="px-6 py-3">IP Address</th>
                    <th class="px-6 py-3">Last Activity</th>
                    <th class="px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sessions as $session)
                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                        {{ $session->user_agent ?? 'Unknown Device' }}
                    </td>
                    <td class="px-6 py-4 text-gray-500">{{ $session->ip_address ?? '-' }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $session->last_activity ? \Carbon\Carbon::createFromTimestamp($session->last_activity)->diffForHumans() : '-' }}</td>
                    <td class="px-6 py-4">
                        <form method="POST" action="{{ route('auth.sessions.revoke', $session->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Revoke</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">No active sessions found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
