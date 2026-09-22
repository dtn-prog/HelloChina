<x-common.component-card title="Dropzone">
    <div 
        x-data="{
            isDragging: false,
            files: [],
            previews: [],
            handleDrop(e) {
                this.isDragging = false;
                const droppedFiles = Array.from(e.dataTransfer.files);
                this.handleFiles(droppedFiles);
            },
            handleFiles(selectedFiles) {
                const validTypes = ['image/png', 'image/jpeg', 'image/webp', 'image/svg+xml', 'application/pdf'];
                const validFiles = selectedFiles.filter(file => validTypes.includes(file.type));
                
                if (validFiles.length > 0) {
                    this.files = [...this.files, ...validFiles];
                    
                    validFiles.forEach(file => {
                        if (file.type.startsWith('image/')) {
                            const reader = new FileReader();
                            reader.onload = (e) => {
                                this.previews.push(e.target.result);
                            };
                            reader.readAsDataURL(file);
                        } else {
                            this.previews.push(null);
                        }
                    });
                }
            },
            removeFile(index) {
                this.files.splice(index, 1);
                this.previews.splice(index, 1);
            },
            formatSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }
        }"
        class="transition border border-gray-300 border-dashed cursor-pointer dark:hover:border-brand-500 dark:border-gray-700 rounded-xl hover:border-brand-500"
    >
        <div 
            @drop.prevent="handleDrop($event)"
            @dragover.prevent="isDragging = true"
            @dragleave.prevent="isDragging = false"
            @click="$refs.fileInput.click()"
            :class="isDragging 
                ? 'border-brand-500 bg-gray-100 dark:bg-gray-800' 
                : 'border-gray-300 bg-gray-50 dark:border-gray-700 dark:bg-gray-900'"
            class="dropzone rounded-xl border-dashed border-gray-300 p-7 lg:p-10 transition-colors cursor-pointer"
        >
            <input 
                x-ref="fileInput"
                type="file" 
                @change="handleFiles(Array.from($event.target.files)); $event.target.value = ''"
                accept="image/png,image/jpeg,image/webp,image/svg+xml,application/pdf"
                multiple
                class="hidden"
                @click.stop
            />

            <div class="flex flex-col items-center m-0">
                <div class="mb-[22px] flex justify-center">
                    <div class="flex h-[68px] w-[68px] items-center justify-center rounded-full bg-gray-200 text-gray-700 dark:bg-gray-800 dark:text-gray-400">
                        <svg class="fill-current" width="29" height="28" viewBox="0 0 29 28" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M14.5019 3.91699C14.2852 3.91699 14.0899 4.00891 13.953 4.15589L8.57363 9.53186C8.28065 9.82466 8.2805 10.2995 8.5733 10.5925C8.8661 10.8855 9.34097 10.8857 9.63396 10.5929L13.7519 6.47752V18.667C13.7519 19.0812 14.0877 19.417 14.5019 19.417C14.9161 19.417 15.2519 19.0812 15.2519 18.667V6.48234L19.3653 10.5929C19.6583 10.8857 20.1332 10.8855 20.426 10.5925C20.7188 10.2995 20.7186 9.82463 20.4256 9.53184L15.0838 4.19378C14.9463 4.02488 14.7367 3.91699 14.5019 3.91699ZM5.91626 18.667C5.91626 18.2528 5.58047 17.917 5.16626 17.917C4.75205 17.917 4.41626 18.2528 4.41626 18.667V21.8337C4.41626 23.0763 5.42362 24.0837 6.66626 24.0837H22.3339C23.5766 24.0837 24.5839 23.0763 24.5839 21.8337V18.667C24.5839 18.2528 24.2482 17.917 23.8339 17.917C23.4197 17.917 23.0839 18.2528 23.0839 18.667V21.8337C23.0839 22.2479 22.7482 22.5837 22.3339 22.5837H6.66626C6.25205 22.5837 5.91626 22.2479 5.91626 21.8337V18.667Z" />
                        </svg>
                    </div>
                </div>

                <h4 class="mb-3 font-semibold text-gray-800 text-theme-xl dark:text-white/90">
                    <span x-show="!isDragging">Drag & Drop Files Here</span>
                    <span x-show="isDragging" x-cloak>Drop Files Here</span>
                </h4>

                <span class="text-center mb-5 block w-full max-w-[290px] text-sm text-gray-700 dark:text-gray-400">
                    Drag and drop images, PDF, or click to browse
                </span>

                <span class="font-medium underline text-theme-sm text-brand-500">
                    Browse Files
                </span>
            </div>
        </div>

        {{-- File Preview Grid --}}
        <div x-show="files.length > 0" class="p-4 border-t border-gray-200 dark:border-gray-700" x-cloak>
            <div class="flex items-center justify-between mb-3">
                <h5 class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                    Selected Files (<span x-text="files.length"></span>)
                </h5>
                <button @click="files = []; previews = []" type="button" class="text-xs text-red-500 hover:text-red-700">
                    Clear all
                </button>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                <template x-for="(file, index) in files" :key="index">
                    <div class="relative group border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                        {{-- Image Preview --}}
                        <template x-if="previews[index]">
                            <img :src="previews[index]" class="w-full h-24 object-cover">
                        </template>
                        {{-- File Icon --}}
                        <template x-if="!previews[index]">
                            <div class="w-full h-24 flex items-center justify-center bg-gray-100 dark:bg-gray-800">
                                <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path d="M4 18h12a2 2 0 002-2V6l-6-4H4a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        </template>
                        {{-- File Info --}}
                        <div class="p-2">
                            <p class="text-xs text-gray-700 dark:text-gray-300 truncate" x-text="file.name"></p>
                            <p class="text-xs text-gray-500" x-text="formatSize(file.size)"></p>
                        </div>
                        {{-- Remove Button --}}
                        <button @click.stop="removeFile(index)" type="button" class="absolute top-1 right-1 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity hover:bg-red-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                </template>
            </div>
        </div>
    </div>
</x-common.component-card>
