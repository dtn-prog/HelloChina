@props([
    'id' => 'modal',
    'title' => '',
    'size' => 'md', // sm, md, lg, xl
    'show' => false,
])

<div x-data="{ open: {{ $show ? 'true' : 'false' }} }" 
     x-show="open" 
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto"
     aria-labelledby="modal-title" 
     role="dialog" 
     aria-modal="true"
     @open-modal.window="open = true"
     @close-modal.window="open = false">
    
    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
         x-show="open" 
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="open = false">
    </div>

    {{-- Modal --}}
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-{{ $size === 'sm' ? 'sm' : ($size === 'md' ? 'md' : ($size === 'lg' ? 'lg' : 'xl')) }}"
                 x-show="open"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                {{-- Header --}}
                <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white" id="modal-title">
                        {{ $title }}
                    </h3>
                    <button type="button" 
                            class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300"
                            @click="open = false">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                {{-- Body --}}
                <div class="px-4 py-4">
                    {{ $slot }}
                </div>

                {{-- Footer --}}
                @if(isset($footer))
                <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700 flex items-center justify-end space-x-3">
                    {{ $footer }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
