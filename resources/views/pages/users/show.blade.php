@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('users.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
            &larr; Back
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 space-y-3">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white">Profile Info</h2>
            <div>
                <p class="text-xs text-gray-500 uppercase">Name</p>
                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase">Email</p>
                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->email }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase">Phone</p>
                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->phone ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase">Status</p>
                <span class="px-2 py-1 text-xs font-medium rounded {{ $user->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $user->status }}
                </span>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase">Joined</p>
                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->created_at->format('Y-m-d H:i') }}</p>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white mb-3">Roles</h2>
            @if($user->roles->count())
            <div class="flex flex-wrap gap-2">
                @foreach($user->roles as $role)
                <span class="px-3 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full dark:bg-blue-900 dark:text-blue-300">
                    {{ $role->name }}
                </span>
                @endforeach
            </div>
            @else
            <p class="text-sm text-gray-500">No roles assigned</p>
            @endif
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white mb-3">Login History</h2>
            @if(isset($user->loginHistory) && $user->loginHistory->count())
            <ul class="space-y-2">
                @foreach($user->loginHistory->take(5) as $history)
                <li class="text-xs text-gray-500">{{ $history->created_at->diffForHumans() }} - {{ $history->ip_address ?? '-' }}</li>
                @endforeach
            </ul>
            @else
            <p class="text-sm text-gray-500">No login history available</p>
            @endif
        </div>
    </div>
</div>
@endsection
