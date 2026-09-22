@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Error Logs</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">View application errors</p>
        </div>
        <button class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">Clear All</button>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-6 py-3">Level</th>
                    <th class="px-6 py-3">Message</th>
                    <th class="px-6 py-3">File</th>
                    <th class="px-6 py-3">Time</th>
                    <th class="px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($errors as $error)
                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded">{{ $error->level }}</span>
                    </td>
                    <td class="px-6 py-4 text-gray-900 dark:text-white max-w-md truncate">{{ $error->message }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ basename($error->file ?? '') }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $error->created_at->diffForHumans() }}</td>
                    <td class="px-6 py-4">
                        <button class="text-blue-600 hover:text-blue-800">View</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">No errors logged</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
