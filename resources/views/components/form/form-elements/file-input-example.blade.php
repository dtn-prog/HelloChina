<x-common.component-card title="File Input">
    <div x-data="{ preview: null, fileName: '' }">
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
            Upload file
        </label>
        <div class="relative">
            <input type="file"
                @change="
                    const file = $event.target.files[0];
                    if (file) {
                        fileName = file.name;
                        if (file.type.startsWith('image/')) {
                            const reader = new FileReader();
                            reader.onload = (e) => preview = e.target.result;
                            reader.readAsDataURL(file);
                        } else {
                            preview = 'file';
                        }
                    } else {
                        preview = null;
                        fileName = '';
                    }
                "
                class="focus:border-ring-brand-300 shadow-theme-xs focus:file:ring-brand-300 h-11 w-full overflow-hidden rounded-lg border border-gray-300 bg-transparent text-sm text-gray-500 transition-colors file:mr-5 file:border-collapse file:cursor-pointer file:rounded-l-lg file:border-0 file:border-r file:border-solid file:border-gray-200 file:bg-gray-50 file:py-3 file:pr-3 file:pl-3.5 file:text-sm file:text-gray-700 placeholder:text-gray-400 hover:file:bg-gray-100 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400 dark:text-white/90 dark:file:border-gray-800 dark:file:bg-white/[0.03] dark:file:text-gray-400 dark:placeholder:text-gray-400" />
        </div>

        {{-- Preview --}}
        <div x-show="preview" x-transition class="mt-4">
            {{-- Image Preview --}}
            <template x-if="preview && preview !== 'file'">
                <div class="relative inline-block">
                    <img :src="preview" class="w-32 h-32 object-cover rounded-lg border border-gray-200 dark:border-gray-700">
                    <button @click="preview = null; fileName = ''" type="button" class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                    <p class="mt-1 text-xs text-gray-500" x-text="fileName"></p>
                </div>
            </template>
            {{-- File Icon Preview --}}
            <template x-if="preview === 'file'">
                <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="w-12 h-12 flex items-center justify-center bg-blue-100 dark:bg-blue-900 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20"><path d="M4 18h12a2 2 0 002-2V6l-6-4H4a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 truncate" x-text="fileName"></p>
                        <p class="text-xs text-gray-500">File selected</p>
                    </div>
                    <button @click="preview = null; fileName = ''" type="button" class="text-red-500 hover:text-red-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </template>
        </div>
    </div>
</x-common.component-card>
