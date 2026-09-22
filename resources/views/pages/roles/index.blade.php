@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Roles & Permissions</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Manage roles and permissions</p>
        </div>
        <a href="#" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">+ Add Role</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($roles as $role)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $role->name }}</h3>
                <div class="flex items-center gap-2">
                    <button class="text-blue-600 hover:text-blue-800">Edit</button>
                    <button class="text-red-600 hover:text-red-800">Delete</button>
                </div>
            </div>
            <div class="flex flex-wrap gap-2">
                @foreach($role->permissions as $permission)
                <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-700 rounded dark:bg-gray-700 dark:text-gray-300">
                    {{ $permission->name }}
                </span>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
