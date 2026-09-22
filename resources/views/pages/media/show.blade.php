@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('media.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
            &larr; Back to Media
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $media->name }}</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Uploaded {{ $media->created_at?->format('Y-m-d H:i') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2 bg-white dark:bg-gray-800 rounded-lg shadow p-6 flex items-center justify-center min-h-[300px]">
            @if(str_starts_with($media->mime_type ?? '', 'image/'))
                <img src="{{ $media->url }}" alt="{{ $media->name }}" class="max-w-full max-h-[500px] object-contain rounded">
            @else
                <div class="text-center p-8">
                    <svg class="mx-auto h-20 w-20 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path d="M4 18h12a2 2 0 002-2V6l-6-4H4a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <p class="mt-4 text-sm text-gray-500">{{ $media->mime_type }}</p>
                </div>
            @endif
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 space-y-4">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white">File Information</h2>
            <div>
                <p class="text-xs text-gray-500 uppercase">File Name</p>
                <p class="text-sm font-medium text-gray-900 dark:text-white break-all">{{ $media->name }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase">MIME Type</p>
                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $media->mime_type ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase">File Size</p>
                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $media->size ? round($media->size / 1024, 1) . ' KB' : '-' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase">URL</p>
                <input type="text" readonly value="{{ $media->url }}" class="mt-1 w-full px-3 py-1.5 text-xs bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded text-gray-700 dark:text-gray-300 select-all">
            </div>

            <div class="pt-4 border-t dark:border-gray-700 flex gap-2">
                <a href="{{ $media->url }}" target="_blank" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">Open</a>
                <form method="POST" action="{{ route('media.destroy', $media) }}" onsubmit="return confirm('Are you sure?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
