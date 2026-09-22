@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ url()->previous() }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
            &larr; Back
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Audit Log Detail</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">#{{ $activity->id }}</p>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-xs text-gray-500 uppercase">Event</p>
                <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded dark:bg-blue-900 dark:text-blue-300">{{ $activity->event }}</span>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase">Log Name</p>
                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $activity->log_name }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase">Performed By</p>
                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $activity->causer->name ?? 'System' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase">Time</p>
                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $activity->created_at->format('Y-m-d H:i:s') }}</p>
            </div>
            <div class="md:col-span-2">
                <p class="text-xs text-gray-500 uppercase">Description</p>
                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $activity->description }}</p>
            </div>
        </div>

        @if($activity->properties && $activity->properties->count())
        <div>
            <p class="text-xs text-gray-500 uppercase mb-2">Properties</p>
            <pre class="bg-gray-50 dark:bg-gray-900 rounded p-4 text-xs text-gray-700 dark:text-gray-300 overflow-auto">{{ json_encode($activity->properties, JSON_PRETTY_PRINT) }}</pre>
        </div>
        @endif
    </div>
</div>
@endsection
