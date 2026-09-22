@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">API Keys</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Manage API keys for external access</p>
        </div>
        <a href="#" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">+ Create API Key</a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-6 py-3">Name</th>
                    <th class="px-6 py-3">Key</th>
                    <th class="px-6 py-3">Last Used</th>
                    <th class="px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($apiKeys as $key)
                <tr class="border-b dark:border-gray-700">
                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $key->name }}</td>
                    <td class="px-6 py-4 font-mono text-sm text-gray-500">{{ substr($key->key, 0, 8) }}...</td>
                    <td class="px-6 py-4 text-gray-500">{{ $key->last_used_at?->diffForHumans() ?? 'Never' }}</td>
                    <td class="px-6 py-4">
                        <button class="text-red-600 hover:text-red-800">Revoke</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">No API keys</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
