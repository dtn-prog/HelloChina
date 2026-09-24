@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Users</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Manage system users</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('users.export') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600">
                Export
            </a>
            @can('users.create')
            <a href="{{ route('users.create') }}" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                + Add User
            </a>
            @endcan
        </div>
    </div>

    <form method="GET" action="{{ route('users.index') }}" class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
        <div class="grid grid-cols-1 gap-3 md:grid-cols-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, email, phone..."
                   class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            <select name="status" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                <option value="">All statuses</option>
                @foreach(['active', 'inactive', 'suspended'] as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
            <select name="role" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                <option value="">All roles</option>
                @foreach($roles as $role)
                    <option value="{{ $role }}" @selected(request('role') === $role)>{{ $role }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                Filter
            </button>
        </div>
    </form>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-6 py-3">User</th>
                    <th class="px-6 py-3">Email</th>
                    <th class="px-6 py-3">Role</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">XP / Level</th>
                    <th class="px-6 py-3">Joined</th>
                    <th class="px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                            </div>
                            <a href="{{ route('users.show', $user) }}" class="hover:underline">{{ $user->name }}</a>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-500">{{ $user->email }}</td>
                    <td class="px-6 py-4">
                        <div class="flex flex-wrap gap-1">
                            @forelse($user->roles as $role)
                                <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded dark:bg-blue-900 dark:text-blue-300">
                                    {{ $role->name }}
                                </span>
                            @empty
                                <span class="text-xs text-gray-400">No Role</span>
                            @endforelse
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-medium rounded {{ $user->status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                            {{ $user->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-500">
                        @if($user->stats)
                            Lv {{ $user->stats->current_level }} · {{ number_format($user->stats->total_xp) }} XP
                        @else
                            —
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-500">{{ $user->created_at->diffForHumans() }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('users.show', $user) }}" class="text-gray-600 hover:text-gray-800 dark:text-gray-400">View</a>
                            @can('users.update')
                            <a href="{{ route('users.edit', $user) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
                            @endcan
                            @can('users.delete')
                            <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Delete this user?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                            </form>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">No users found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
