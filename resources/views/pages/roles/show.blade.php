@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('roles.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
            &larr; Back
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Role: {{ $role->name }}</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $role->permissions->count() }} permissions assigned</p>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Permissions</h2>
        @if($role->permissions->count())
        <div class="flex flex-wrap gap-2">
            @foreach($role->permissions as $permission)
            <span class="px-3 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full dark:bg-blue-900 dark:text-blue-300">
                {{ $permission->name }}
            </span>
            @endforeach
        </div>
        @else
        <p class="text-gray-500">No permissions assigned to this role.</p>
        @endif
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Users with this Role</h2>
        <p class="text-sm text-gray-500">{{ $role->users_count ?? 0 }} users have this role.</p>
    </div>
</div>
@endsection
