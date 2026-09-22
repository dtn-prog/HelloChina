@extends('layouts.app')

@section('content')
<div class="mb-6">
    <a href="{{ route($resource->routePrefix() . '.index') }}" class="text-blue-600 hover:underline">
        ← Back to {{ $resource->title() }} List
    </a>
</div>

<div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            {{ $resource->title() }} Details
        </h1>
        <div class="flex items-center space-x-2">
            @can($resource->permissionPrefix() . '.update')
            <a href="{{ route($resource->routePrefix() . '.edit', $item) }}" 
               class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Edit
            </a>
            @endcan
            @can($resource->permissionPrefix() . '.delete')
            <form method="POST" action="{{ route($resource->routePrefix() . '.destroy', $item) }}" 
                  onsubmit="return confirm('Are you sure?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                    Delete
                </button>
            </form>
            @endcan
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($resource->columns() as $column)
        <div>
            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $column['label'] }}</dt>
            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                @if($column['type'] === 'image')
                    @if($item->{$column['field']})
                        <img src="{{ asset('storage/' . $item->{$column['field']}) }}" 
                             alt="{{ $column['label'] }}" class="w-20 h-20 rounded object-cover">
                    @else
                        <span class="text-gray-400">No image</span>
                    @endif
                @elseif($column['type'] === 'badge')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        {{ $item->{$column['field']} === 'active' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ ucfirst($item->{$column['field']}) }}
                    </span>
                @elseif($column['type'] === 'currency')
                    {{ number_format($item->{$column['field']}, 0, ',', '.') }}₫
                @elseif($column['type'] === 'datetime')
                    {{ $item->{$column['field']}?->format('d/m/Y H:i') }}
                @else
                    {{ $item->{$column['field']} ?? '-' }}
                @endif
            </dd>
        </div>
        @endforeach
    </div>

    {{-- Timestamps --}}
    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Created At</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $item->created_at?->format('d/m/Y H:i:s') }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Updated At</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $item->updated_at?->format('d/m/Y H:i:s') }}</dd>
            </div>
        </div>
    </div>
</div>
@endsection
