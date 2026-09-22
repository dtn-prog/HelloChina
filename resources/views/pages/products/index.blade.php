@extends('layouts.app')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $resource->title() }} Management</h1>
    @can($resource->permissionPrefix() . '.create')
    <a href="{{ route($resource->routePrefix() . '.create') }}" 
       class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
        + Create {{ $resource->title() }}
    </a>
    @endcan
</div>

{{-- Filters --}}
<x-crud.filter-bar :filters="$resource->filters()" />

{{-- Data Table --}}
<x-crud.data-table 
    :columns="$resource->columns()" 
    :items="$items" 
    :resource="$resource"
    :selectable="true" />

{{-- Bulk Actions --}}
<x-crud.bulk-actions :resource="$resource" />
@endsection
