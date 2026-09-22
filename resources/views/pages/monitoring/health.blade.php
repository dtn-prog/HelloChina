@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Health Check</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">System health status</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="flex items-center gap-3">
                <div class="w-3 h-3 rounded-full bg-green-500"></div>
                <div>
                    <p class="font-medium text-gray-900 dark:text-white">Database</p>
                    <p class="text-sm text-gray-500">Connected</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="flex items-center gap-3">
                <div class="w-3 h-3 rounded-full bg-green-500"></div>
                <div>
                    <p class="font-medium text-gray-900 dark:text-white">Cache</p>
                    <p class="text-sm text-gray-500">Working</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="flex items-center gap-3">
                <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                <div>
                    <p class="font-medium text-gray-900 dark:text-white">Queue</p>
                    <p class="text-sm text-gray-500">0 pending jobs</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="flex items-center gap-3">
                <div class="w-3 h-3 rounded-full bg-green-500"></div>
                <div>
                    <p class="font-medium text-gray-900 dark:text-white">Storage</p>
                    <p class="text-sm text-gray-500">{{ round(disk_free_space(storage_path()) / 1073741824, 2) }} GB free</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="flex items-center gap-3">
                <div class="w-3 h-3 rounded-full bg-green-500"></div>
                <div>
                    <p class="font-medium text-gray-900 dark:text-white">Memory</p>
                    <p class="text-sm text-gray-500">{{ round(memory_get_usage(true) / 1048576, 2) }} MB used</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
