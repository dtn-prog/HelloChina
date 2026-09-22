@extends('layouts.app')

@section('content')
<div class="mb-6">
    <a href="{{ route($resource->routePrefix() . '.index') }}" class="text-blue-600 hover:underline">
        ← Back to {{ $resource->title() }} List
    </a>
</div>

<div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">
        Create {{ $resource->title() }}
    </h1>

    <x-crud.form 
        :fields="$resource->fields()" 
        :action="route($resource->routePrefix() . '.store')"
        :resource="$resource" />
</div>
@endsection
