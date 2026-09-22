@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Settings</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">System configuration</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <form method="POST" action="{{ route('settings.update') }}">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Site Name</label>
                    <input type="text" name="site_name" value="{{ setting('site_name', 'My App') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Site Description</label>
                    <textarea name="site_description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ setting('site_description', '') }}</textarea>
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="maintenance_mode" id="maintenance_mode" {{ setting('maintenance_mode', false) ? 'checked' : '' }} class="w-4 h-4 text-blue-600 rounded">
                    <label for="maintenance_mode" class="text-sm font-medium text-gray-700 dark:text-gray-300">Maintenance Mode</label>
                </div>
                <div>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">Save Settings</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
