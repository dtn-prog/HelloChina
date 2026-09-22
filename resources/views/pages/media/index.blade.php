@extends('layouts.app')

@section('content')
<div class="space-y-6" x-data="mediaManager()">
    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Media Library</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Manage your files and images</p>
        </div>
        <div class="flex items-center space-x-3">
            <button @click="showFolderModal = true" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                + New Folder
            </button>
            <label class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 cursor-pointer">
                + Upload Files
                <input type="file" multiple accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.csv,.zip" class="hidden" @change="uploadFiles($event)">
            </label>
        </div>
    </div>

    {{-- Upload Progress --}}
    <div x-show="uploading" x-transition class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Uploading...</span>
            <span class="text-sm text-gray-500" x-text="uploadProgress + '%'"></span>
        </div>
        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
            <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" :style="'width: ' + uploadProgress + '%'"></div>
        </div>
    </div>

    {{-- Success/Error Messages --}}
    <div x-show="message" x-transition class="p-4 rounded-lg" :class="messageType === 'success' ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-red-50 text-red-700 border border-red-200'">
        <p class="text-sm" x-text="message"></p>
    </div>

    {{-- Folders --}}
    @if(isset($folders) && count($folders) > 0)
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
        <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Folders</h3>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
            @foreach($folders as $folder)
            <div class="flex items-center space-x-2 p-3 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer" onclick="window.location.href='{{ route('media.index', ['folder' => $folder->id]) }}'">
                <svg class="w-8 h-8 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"></path>
                </svg>
                <span class="text-sm text-gray-700 dark:text-gray-300 truncate">{{ $folder->name }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Media Grid --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Files</h3>
            <div class="flex items-center space-x-2">
                <button @click="viewMode = 'grid'" :class="viewMode === 'grid' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700'" class="p-2 rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                </button>
                <button @click="viewMode = 'list'" :class="viewMode === 'list' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700'" class="p-2 rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                </button>
            </div>
        </div>

        @if(isset($items) && count($items) > 0)
            {{-- Grid View --}}
            <div x-show="viewMode === 'grid'" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach($items as $item)
                <div class="group relative border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden hover:shadow-lg transition-shadow cursor-pointer" @click="openPreview({{ $item->toJson() }})">
                    {{-- Preview --}}
                    <div class="aspect-square bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                        @if(str_starts_with($item->mime_type ?? '', 'image/'))
                            <img src="{{ $item->url }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                        @elseif(($item->mime_type ?? '') === 'application/pdf')
                            <svg class="w-12 h-12 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path d="M4 18h12a2 2 0 002-2V6l-6-4H4a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        @elseif(in_array($item->mime_type ?? '', ['application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']))
                            <svg class="w-12 h-12 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path d="M4 18h12a2 2 0 002-2V6l-6-4H4a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        @elseif(in_array($item->mime_type ?? '', ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']))
                            <svg class="w-12 h-12 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path d="M4 18h12a2 2 0 002-2V6l-6-4H4a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        @else
                            <svg class="w-12 h-12 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path d="M4 18h12a2 2 0 002-2V6l-6-4H4a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        @endif
                    </div>
                    {{-- Info --}}
                    <div class="p-2">
                        <p class="text-xs text-gray-700 dark:text-gray-300 truncate" title="{{ $item->name }}">{{ $item->name }}</p>
                        <p class="text-xs text-gray-400">{{ $item->size ? round($item->size / 1024, 1) . ' KB' : '' }}</p>
                    </div>
                    {{-- Actions Overlay --}}
                    <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity flex space-x-1">
                        <a href="{{ $item->url }}" target="_blank" class="p-1.5 bg-white dark:bg-gray-800 rounded-full shadow hover:bg-gray-100" @click.stop>
                            <svg class="w-3.5 h-3.5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        </a>
                        <button class="p-1.5 bg-white dark:bg-gray-800 rounded-full shadow hover:bg-gray-100" @click.stop="copyUrl('{{ $item->url }}')">
                            <svg class="w-3.5 h-3.5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                        </button>
                        <button class="p-1.5 bg-white dark:bg-gray-800 rounded-full shadow hover:bg-red-100" @click.stop="deleteMedia({{ $item->id }})">
                            <svg class="w-3.5 h-3.5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- List View --}}
            <div x-show="viewMode === 'list'" class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="px-4 py-3">Preview</th>
                            <th class="px-4 py-3">Name</th>
                            <th class="px-4 py-3">Type</th>
                            <th class="px-4 py-3">Size</th>
                            <th class="px-4 py-3">Created</th>
                            <th class="px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                        <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-4 py-3">
                                @if(str_starts_with($item->mime_type ?? '', 'image/'))
                                    <img src="{{ $item->url }}" class="w-10 h-10 rounded object-cover">
                                @else
                                    <div class="w-10 h-10 rounded bg-gray-100 dark:bg-gray-600 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path d="M4 18h12a2 2 0 002-2V6l-6-4H4a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $item->name }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $item->mime_type }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $item->size ? round($item->size / 1024, 1) . ' KB' : '-' }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $item->created_at?->diffForHumans() }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ $item->url }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                    </a>
                                    <button class="text-gray-600 hover:text-gray-800" @click="copyUrl('{{ $item->url }}')">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                                    </button>
                                    <button class="text-red-600 hover:text-red-800" @click="deleteMedia({{ $item->id }})">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">No files uploaded yet</p>
                <label class="mt-4 inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 cursor-pointer">
                    + Upload Files
                    <input type="file" multiple class="hidden" @change="uploadFiles($event)">
                </label>
            </div>
        @endif
    </div>

    {{-- Preview Modal --}}
    <div x-show="showPreview" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="showPreview = false"></div>
            <div class="inline-block w-full max-w-4xl p-6 my-8 overflow-hidden text-left align-bottom transition-all transform bg-white dark:bg-gray-800 shadow-xl rounded-2xl">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white" x-text="previewItem?.name || 'Preview'"></h3>
                    <button @click="showPreview = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <div class="flex justify-center items-center bg-gray-100 dark:bg-gray-900 rounded-lg min-h-[300px] max-h-[500px] overflow-hidden">
                    <template x-if="previewItem?.mime_type?.startsWith('image/')">
                        <img :src="previewItem?.url" :alt="previewItem?.name" class="max-w-full max-h-[500px] object-contain">
                    </template>
                    <template x-if="previewItem?.mime_type === 'application/pdf'">
                        <iframe :src="previewItem?.url" class="w-full h-[500px]"></iframe>
                    </template>
                    <template x-if="!previewItem?.mime_type?.startsWith('image/') && previewItem?.mime_type !== 'application/pdf'">
                        <div class="text-center p-8">
                            <svg class="mx-auto h-16 w-16 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path d="M4 18h12a2 2 0 002-2V6l-6-4H4a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <p class="mt-4 text-sm text-gray-500">Preview not available for this file type</p>
                        </div>
                    </template>
                </div>
                <div class="mt-4 flex items-center justify-between">
                    <div class="text-sm text-gray-500">
                        <span x-text="previewItem?.mime_type"></span> &middot; <span x-text="previewItem?.size ? (previewItem.size / 1024).toFixed(1) + ' KB' : ''"></span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <a :href="previewItem?.url" target="_blank" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Open in new tab</a>
                        <button @click="copyUrl(previewItem?.url)" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">Copy URL</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- New Folder Modal --}}
    <div x-show="showFolderModal" x-transition class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75" @click="showFolderModal = false"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Create New Folder</h3>
                <form @submit.prevent="createFolder()">
                    <input type="text" x-model="folderName" placeholder="Folder name" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white" required>
                    <div class="flex justify-end space-x-2 mt-4">
                        <button type="button" @click="showFolderModal = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Cancel</button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function mediaManager() {
    return {
        viewMode: 'grid',
        uploading: false,
        uploadProgress: 0,
        message: '',
        messageType: 'success',
        showPreview: false,
        previewItem: null,
        showFolderModal: false,
        folderName: '',

        openPreview(item) {
            this.previewItem = item;
            this.showPreview = true;
        },

        copyUrl(url) {
            navigator.clipboard.writeText(url);
            this.showMessage('URL copied to clipboard!', 'success');
        },

        showMessage(msg, type = 'success') {
            this.message = msg;
            this.messageType = type;
            setTimeout(() => this.message = '', 3000);
        },

        async uploadFiles(event) {
            const files = event.target.files;
            if (!files.length) return;

            this.uploading = true;
            this.uploadProgress = 0;

            for (let i = 0; i < files.length; i++) {
                const formData = new FormData();
                formData.append('file', files[i]);
                formData.append('folder_id', '{{ request("folder") }}');

                try {
                    const response = await fetch('{{ route("media.upload") }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        },
                    });

                    if (response.ok) {
                        this.uploadProgress = Math.round(((i + 1) / files.length) * 100);
                    } else {
                        const data = await response.json();
                        this.showMessage(data.message || 'Upload failed', 'error');
                    }
                } catch (e) {
                    this.showMessage('Upload failed: ' + e.message, 'error');
                }
            }

            this.uploading = false;
            if (this.uploadProgress === 100) {
                window.location.reload();
            }
        },

        async deleteMedia(id) {
            if (!confirm('Are you sure you want to delete this file?')) return;

            try {
                const response = await fetch(`/media/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                });

                if (response.ok) {
                    window.location.reload();
                } else {
                    this.showMessage('Delete failed', 'error');
                }
            } catch (e) {
                this.showMessage('Delete failed: ' + e.message, 'error');
            }
        },

        async createFolder() {
            if (!this.folderName.trim()) return;

            try {
                const response = await fetch('{{ route("media.folders.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ name: this.folderName }),
                });

                if (response.ok) {
                    window.location.reload();
                } else {
                    const data = await response.json();
                    this.showMessage(data.message || 'Failed to create folder', 'error');
                }
            } catch (e) {
                this.showMessage('Failed to create folder: ' + e.message, 'error');
            }

            this.showFolderModal = false;
            this.folderName = '';
        }
    };
}
</script>
@endpush
