@props([
    'fields' => [],
    'item' => null,
    'action' => null,
    'method' => 'POST',
])

<form method="POST" action="{{ $action }}" enctype="multipart/form-data" x-data="{ loading: false }" 
      @submit="loading = true">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif

    <div class="grid grid-cols-12 gap-6">
        @foreach($fields as $field)
        <div class="col-span-12 {{ isset($field['col_span']) ? 'md:col-span-' . $field['col_span'] : '' }}">
            <label for="{{ $field['field'] }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                {{ $field['label'] }}
                @if(str_contains($field['rules'] ?? '', 'required'))
                    <span class="text-red-500">*</span>
                @endif
            </label>

            @if($field['type'] === 'text' || $field['type'] === 'number' || $field['type'] === 'email')
                <input type="{{ $field['type'] }}" 
                       id="{{ $field['field'] }}" 
                       name="{{ $field['field'] }}"
                       value="{{ old($field['field'], $item?->{$field['field']} ?? ($field['default'] ?? '')) }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                       @if($field['type'] === 'number') step="any" @endif
                       {{ str_contains($field['rules'] ?? '', 'required') ? 'required' : '' }}>
                @error($field['field'])
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror

            @elseif($field['type'] === 'textarea')
                <textarea id="{{ $field['field'] }}" 
                          name="{{ $field['field'] }}"
                          rows="4"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                          {{ str_contains($field['rules'] ?? '', 'required') ? 'required' : '' }}>{{ old($field['field'], $item?->{$field['field']} ?? '') }}</textarea>
                @error($field['field'])
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror

            @elseif($field['type'] === 'select')
                <select id="{{ $field['field'] }}" 
                        name="{{ $field['field'] }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        {{ str_contains($field['rules'] ?? '', 'required') ? 'required' : '' }}>
                    <option value="">-- Select --</option>
                    @foreach($field['options'] as $value => $label)
                        <option value="{{ $value }}" {{ old($field['field'], $item?->{$field['field']}) == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @error($field['field'])
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror

            @elseif($field['type'] === 'file')
                <input type="file" 
                       id="{{ $field['field'] }}" 
                       name="{{ $field['field'] }}"
                       accept="{{ $field['accept'] ?? '*' }}"
                       class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                @if($item?->{$field['field']})
                    <p class="mt-1 text-sm text-gray-500">Current: {{ $item->{$field['field']} }}</p>
                @endif
                @error($field['field'])
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror

            @elseif($field['type'] === 'toggle')
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="hidden" name="{{ $field['field'] }}" value="0">
                    <input type="checkbox" 
                           id="{{ $field['field'] }}" 
                           name="{{ $field['field'] }}"
                           value="1"
                           {{ old($field['field'], $item?->{$field['field']}) ? 'checked' : '' }}
                           class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                </label>

            @elseif($field['type'] === 'checkbox')
                <input type="checkbox" 
                       id="{{ $field['field'] }}" 
                       name="{{ $field['field'] }}"
                       value="1"
                       {{ old($field['field'], $item?->{$field['field']}) ? 'checked' : '' }}
                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">

            @elseif($field['type'] === 'richtext')
                <textarea id="{{ $field['field'] }}" 
                          name="{{ $field['field'] }}"
                          rows="6"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                          x-data="{}"
                          x-init="$nextTick(() => { if (typeof tinymce !== 'undefined') tinymce.init({ selector: '#{{ $field['field'] }}', plugins: 'link lists', toolbar: 'bold italic underline | bullist numlist | link' }); })">{{ old($field['field'], $item?->{$field['field']} ?? '') }}</textarea>
                @error($field['field'])
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            @endif
        </div>
        @endforeach
    </div>

    {{-- Actions --}}
    <div class="mt-6 flex items-center justify-end space-x-3">
        <a href="{{ route($resource->routePrefix() . '.index') }}" 
           class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
            Cancel
        </a>
        <button type="submit" 
                class="px-4 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                :disabled="loading">
            <span x-show="!loading">Save</span>
            <span x-show="loading">Saving...</span>
        </button>
    </div>
</form>
