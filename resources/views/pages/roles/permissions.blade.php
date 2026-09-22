@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">All Permissions</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $permissions->flatten()->count() }} permissions across {{ $permissions->count() }} groups</p>
    </div>

    @foreach($permissions as $group => $groupPermissions)
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h2 class="text-base font-semibold text-gray-900 dark:text-white mb-3 capitalize">{{ $group }}</h2>
        <div class="flex flex-wrap gap-2">
            @foreach($groupPermissions as $permission)
            <span class="px-3 py-1 text-xs font-medium bg-gray-100 text-gray-700 rounded-full dark:bg-gray-700 dark:text-gray-300">
                {{ $permission->name }}
            </span>
            @endforeach
        </div>
    </div>
    @endforeach
</div>
@endsection
