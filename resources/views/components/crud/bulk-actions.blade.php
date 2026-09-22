@props([
    'items' => [],
    'resource' => null,
])

<div x-data="{ showBulkActions: false, selected: [] }" 
     @bulk-select.window="selected = $event.detail.selected; showBulkActions = selected.length > 0">
    
    {{-- Bulk Actions Bar --}}
    <div x-show="showBulkActions" x-transition
         class="fixed bottom-4 left-1/2 transform -translate-x-1/2 z-50">
        <div class="bg-gray-900 dark:bg-gray-700 rounded-lg shadow-lg px-4 py-3 flex items-center space-x-4">
            <span class="text-sm text-white" x-text="`${selected.length} selected`"></span>
            
            <div class="flex items-center space-x-2">
                @can($resource->permissionPrefix() . '.delete')
                <button type="button" 
                        class="px-3 py-1 bg-red-600 text-white text-sm rounded-md hover:bg-red-700"
                        @click="
                            if (confirm('Are you sure you want to delete ' + selected.length + ' items?')) {
                                fetch('{{ route($resource->routePrefix() . '.bulk-action') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({ action: 'delete', ids: selected })
                                }).then(() => window.location.reload());
                            }
                        ">
                    Delete Selected
                </button>
                @endcan

                @can($resource->permissionPrefix() . '.update')
                <button type="button" 
                        class="px-3 py-1 bg-green-600 text-white text-sm rounded-md hover:bg-green-700"
                        @click="
                            fetch('{{ route($resource->routePrefix() . '.bulk-action') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({ action: 'activate', ids: selected })
                            }).then(() => window.location.reload());
                        ">
                    Activate
                </button>
                <button type="button" 
                        class="px-3 py-1 bg-yellow-600 text-white text-sm rounded-md hover:bg-yellow-700"
                        @click="
                            fetch('{{ route($resource->routePrefix() . '.bulk-action') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({ action: 'deactivate', ids: selected })
                            }).then(() => window.location.reload());
                        ">
                    Deactivate
                </button>
                @endcan
            </div>

            <button type="button" 
                    class="text-gray-400 hover:text-white"
                    @click="showBulkActions = false; selected = []">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>
</div>
