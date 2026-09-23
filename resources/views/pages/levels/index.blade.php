@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Levels</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Cumulative XP required to reach each level (required_xp = level × 100)</p>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-6 py-3">Level</th>
                    <th class="px-6 py-3">Required XP</th>
                </tr>
            </thead>
            <tbody>
                @forelse($levels as $level)
                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $level->level }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ number_format($level->required_xp) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" class="px-6 py-8 text-center text-gray-500">No levels seeded yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">
            {{ $levels->links() }}
        </div>
    </div>
</div>
@endsection
