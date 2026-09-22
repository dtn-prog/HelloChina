@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('audit.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
            &larr; Back
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">User Activity History</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Activity logs for this user</p>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-6 py-3">Event</th>
                    <th class="px-6 py-3">Description</th>
                    <th class="px-6 py-3">Subject</th>
                    <th class="px-6 py-3">Time</th>
                </tr>
            </thead>
            <tbody>
                @forelse($activities as $activity)
                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded dark:bg-blue-900 dark:text-blue-300">{{ $activity->event }}</span>
                    </td>
                    <td class="px-6 py-4 text-gray-500">{{ $activity->description }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $activity->subject_type ? class_basename($activity->subject_type) . ' #' . $activity->subject_id : '-' }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $activity->created_at->diffForHumans() }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">No activity found for this user</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
