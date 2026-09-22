@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Cron Monitor</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Monitor scheduled tasks</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-6 py-3">Command</th>
                    <th class="px-6 py-3">Schedule</th>
                    <th class="px-6 py-3">Last Run</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">No scheduled tasks configured</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
