@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('monitoring.errors.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
            &larr; Back
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Error Detail</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">#{{ $error->id }}</p>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-xs text-gray-500 uppercase">Level</p>
                <span class="px-2 py-1 text-xs font-medium rounded
                    {{ in_array($error->level, ['error', 'critical', 'alert', 'emergency']) ? 'bg-red-100 text-red-800' : (in_array($error->level, ['warning']) ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                    {{ strtoupper($error->level) }}
                </span>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase">Time</p>
                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $error->created_at->format('Y-m-d H:i:s') }}</p>
            </div>
            <div class="md:col-span-2">
                <p class="text-xs text-gray-500 uppercase">Message</p>
                <p class="text-sm text-gray-700 dark:text-gray-300 font-medium">{{ $error->message }}</p>
            </div>
            @if($error->url ?? null)
            <div class="md:col-span-2">
                <p class="text-xs text-gray-500 uppercase">URL</p>
                <p class="text-sm font-mono text-gray-700 dark:text-gray-300">{{ $error->url }}</p>
            </div>
            @endif
            @if($error->file ?? null)
            <div class="md:col-span-2">
                <p class="text-xs text-gray-500 uppercase">File</p>
                <p class="text-sm font-mono text-gray-700 dark:text-gray-300">{{ $error->file }}:{{ $error->line ?? '' }}</p>
            </div>
            @endif
        </div>

        @if($error->trace ?? null)
        <div>
            <p class="text-xs text-gray-500 uppercase mb-2">Stack Trace</p>
            <pre class="bg-gray-50 dark:bg-gray-900 rounded p-4 text-xs text-gray-700 dark:text-gray-300 overflow-auto max-h-96">{{ $error->trace }}</pre>
        </div>
        @endif

        @if($error->context ?? null)
        <div>
            <p class="text-xs text-gray-500 uppercase mb-2">Context</p>
            <pre class="bg-gray-50 dark:bg-gray-900 rounded p-4 text-xs text-gray-700 dark:text-gray-300 overflow-auto">{{ json_encode($error->context, JSON_PRETTY_PRINT) }}</pre>
        </div>
        @endif

        <div class="pt-4 border-t dark:border-gray-700">
            <form method="POST" action="{{ route('monitoring.errors.destroy', $error) }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700"
                    onclick="return confirm('Delete this error log?')">
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
