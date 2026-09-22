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
            Edit {{ $resource->title() }}
        </h1>
        <div class="flex items-center space-x-2">
            @can($resource->permissionPrefix() . '.view')
            <a href="{{ route($resource->routePrefix() . '.show', $item) }}" 
               class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                View
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

    <x-crud.form 
        :fields="$resource->fields()" 
        :item="$item"
        :action="route($resource->routePrefix() . '.update', $item)"
        method="PUT"
        :resource="$resource" />
</div>
@endsection
