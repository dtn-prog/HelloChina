@props([
    'columns' => [],
    'items' => null,
    'resource' => null,
    'actions' => true,
    'selectable' => true,
])

<div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
    {{-- Header --}}
    <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                @if($selectable)
                <div x-data="{ selectedAll: false, selected: [] }">
                    <input type="checkbox" x-model="selectedAll" @change="
                        selected = selectedAll ? document.querySelectorAll('.row-checkbox').length : [];
                        $dispatch('bulk-select', { selected })
                    " class="rounded border-gray-300">
                </div>
                @endif
                <span class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $items->total() }} {{ strtolower(Str::plural($resource->title() ?? 'items')) }}
                </span>
            </div>
            <div class="flex items-center space-x-2">
                @if(method_exists($items, 'links'))
                    {{ $items->links() }}
                @endif
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    @if($selectable)
                    <th class="px-4 py-3 text-left">
                        <input type="checkbox" class="rounded border-gray-300">
                    </th>
                    @endif
                    @foreach($columns as $column)
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        @if(in_array($column['field'], $resource->sortable() ?? []))
                        <button type="button" class="flex items-center space-x-1 hover:text-gray-700"
                                onclick="window.location.href='{{ request()->fullUrlWithQuery(['sort' => $column['field'], 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}'">
                            <span>{{ $column['label'] }}</span>
                            @if(request('sort') === $column['field'])
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                </svg>
                            @endif
                        </button>
                        @else
                            {{ $column['label'] }}
                        @endif
                    </th>
                    @endforeach
                    @if($actions)
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Actions
                    </th>
                    @endif
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($items as $item)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                    @if($selectable)
                    <td class="px-4 py-3">
                        <input type="checkbox" name="ids[]" value="{{ $item->id }}" 
                               class="row-checkbox rounded border-gray-300">
                    </td>
                    @endif
                    @foreach($columns as $column)
                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                        @if($column['type'] === 'image')
                            @if($item->{$column['field']})
                                <img src="{{ asset('storage/' . $item->{$column['field']}) }}" 
                                     alt="{{ $column['label'] }}" class="w-10 h-10 rounded object-cover">
                            @else
                                <span class="text-gray-400">-</span>
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
                        @elseif(str_contains($column['field'], '.'))
                            {{-- Nested field --}}
                            @php
                                $parts = explode('.', $column['field']);
                                $value = $item;
                                foreach ($parts as $part) {
                                    $value = is_object($value) ? $value->{$part} ?? null : ($value[$part] ?? null);
                                }
                            @endphp
                            {{ $value }}
                        @else
                            {{ $item->{$column['field']} }}
                        @endif
                    </td>
                    @endforeach
                    @if($actions)
                    <td class="px-4 py-3 text-right text-sm font-medium">
                        <div class="flex items-center justify-end space-x-2">
                            @can($resource->permissionPrefix() . '.view')
                            <a href="{{ route($resource->routePrefix() . '.show', $item) }}" 
                               class="text-blue-600 hover:text-blue-900 dark:text-blue-400">
                                View
                            </a>
                            @endcan
                            @can($resource->permissionPrefix() . '.update')
                            <a href="{{ route($resource->routePrefix() . '.edit', $item) }}" 
                               class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">
                                Edit
                            </a>
                            @endcan
                            @can($resource->permissionPrefix() . '.delete')
                            <form method="POST" action="{{ route($resource->routePrefix() . '.destroy', $item) }}" 
                                  onsubmit="return confirm('Are you sure?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400">
                                    Delete
                                </button>
                            </form>
                            @endcan
                        </div>
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="{{ count($columns) + ($actions ? 1 : 0) + ($selectable ? 1 : 0) }}" 
                        class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                        No data found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Footer --}}
    @if(method_exists($items, 'links'))
    <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
        {{ $items->links() }}
    </div>
    @endif
</div>
