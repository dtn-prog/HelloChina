@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('developer.api-keys.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
            &larr; Back
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">API Key: {{ $apiKey->name }}</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Created {{ $apiKey->created_at->format('Y-m-d H:i') }}</p>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-xs text-gray-500 uppercase">Name</p>
                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $apiKey->name }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase">Key (masked)</p>
                <p class="text-sm font-mono text-gray-700 dark:text-gray-300">{{ substr($apiKey->token ?? $apiKey->key ?? '', 0, 12) }}...</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase">Owner</p>
                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $apiKey->user->name ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase">Last Used</p>
                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $apiKey->last_used_at?->diffForHumans() ?? 'Never' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase">Expires At</p>
                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $apiKey->expires_at ? $apiKey->expires_at->format('Y-m-d') : 'Never' }}</p>
            </div>
        </div>

        @if($apiKey->abilities ?? null)
        <div>
            <p class="text-xs text-gray-500 uppercase mb-2">Abilities</p>
            <div class="flex flex-wrap gap-1">
                @foreach((array)$apiKey->abilities as $ability)
                <span class="px-2 py-0.5 text-xs bg-gray-100 text-gray-700 rounded dark:bg-gray-700 dark:text-gray-300">{{ $ability }}</span>
                @endforeach
            </div>
        </div>
        @endif

        <div class="flex gap-2 pt-4 border-t dark:border-gray-700">
            <form method="POST" action="{{ route('developer.api-keys.destroy', $apiKey) }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700"
                    onclick="return confirm('Are you sure you want to delete this API key?')">
                    Delete Key
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
