@props([
    'filters' => [],
])

<form method="GET" action="{{ request()->url() }}" class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        @foreach($filters as $filter)
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                {{ $filter['label'] }}
            </label>
            
            @if($filter['type'] === 'select')
                <select name="{{ $filter['field'] }}" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">All</option>
                    @foreach($filter['options'] as $value => $label)
                        <option value="{{ $value }}" {{ request($filter['field']) == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>

            @elseif($filter['type'] === 'date')
                <input type="date" 
                       name="{{ $filter['field'] }}"
                       value="{{ request($filter['field']) }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">

            @elseif($filter['type'] === 'date_range')
                <div class="flex space-x-2">
                    <input type="date" 
                           name="{{ $filter['field'] }}_from"
                           value="{{ request($filter['field'] . '_from') }}"
                           placeholder="From"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <input type="date" 
                           name="{{ $filter['field'] }}_to"
                           value="{{ request($filter['field'] . '_to') }}"
                           placeholder="To"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

            @elseif($filter['type'] === 'boolean')
                <select name="{{ $filter['field'] }}" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">All</option>
                    <option value="1" {{ request($filter['field']) === '1' ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ request($filter['field']) === '0' ? 'selected' : '' }}>No</option>
                </select>

            @else
                <input type="text" 
                       name="{{ $filter['field'] }}"
                       value="{{ request($filter['field']) }}"
                       placeholder="{{ $filter['label'] }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            @endif
        </div>
        @endforeach

        {{-- Search --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
            <input type="text" 
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Search..."
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
        </div>
    </div>

    {{-- Actions --}}
    <div class="mt-4 flex items-center justify-end space-x-3">
        @if(request()->hasAny(array_column($filters, 'field')) || request('search'))
        <a href="{{ request()->url() }}" 
           class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
            Clear Filters
        </a>
        @endif
        <button type="submit" 
                class="px-4 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700">
            Apply Filters
        </button>
    </div>
</form>
