@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Queue Monitor</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Monitor queued jobs</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <p class="text-sm text-gray-500">Pending Jobs</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $pendingJobs ?? 0 }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <p class="text-sm text-gray-500">Failed Jobs</p>
            <p class="text-2xl font-bold text-red-600">{{ $failedJobs ?? 0 }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <p class="text-sm text-gray-500">Processed Jobs</p>
            <p class="text-2xl font-bold text-green-600">{{ $processedJobs ?? 0 }}</p>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-6 py-3">Job</th>
                    <th class="px-6 py-3">Queue</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Created</th>
                    <th class="px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">No jobs to display</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
