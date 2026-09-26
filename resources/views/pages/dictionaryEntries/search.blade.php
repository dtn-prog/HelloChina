@extends('layouts.app')

@section('content')
    <div class="space-y-4" x-data="dictionarySearch()">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Chinese-Vietnamese Dictionary</h1>

        <form @submit.prevent="search" class="flex flex-col gap-3 sm:flex-row">
            <label class="sr-only" for="dictionary-type">Search language</label>
            <select id="dictionary-type" x-model="type" class="h-11 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                <option value="all">All languages</option>
                <option value="chinese">Chinese</option>
                <option value="pinyin">Pinyin</option>
                <option value="vietnamese">Vietnamese</option>
            </select>
            <label class="sr-only" for="dictionary-keyword">Search term</label>
            <input id="dictionary-keyword" x-model="keyword" type="search" maxlength="100" required placeholder="中文, pinyin, tiếng Việt"
                class="h-11 min-w-0 flex-1 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-900 placeholder:text-gray-400 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
            <button type="submit" :disabled="loading" class="h-11 rounded-lg bg-blue-600 px-5 text-sm font-medium text-white hover:bg-blue-700 disabled:opacity-60">
                <span x-text="loading ? 'Searching...' : 'Search'"></span>
            </button>
        </form>

        <p x-show="message" x-text="message" role="status" class="text-sm text-gray-600 dark:text-gray-300"></p>
        <div id="dictionary-results" class="divide-y divide-gray-200 rounded-lg border border-gray-200 bg-white dark:divide-gray-700 dark:border-gray-700 dark:bg-gray-800"></div>
    </div>
@endsection

@push('scripts')
<script>
    function dictionarySearch() {
        return {
            keyword: '',
            type: 'all',
            loading: false,
            message: '',

            async search() {
                this.loading = true;
                this.message = '';
                const results = document.getElementById('dictionary-results');
                results.replaceChildren();

                const url = new URL('/app/dictionary/search', window.location.origin);
                url.searchParams.set('keyword', this.keyword.trim());
                url.searchParams.set('type', this.type);

                try {
                    const response = await fetch(url, {
                        headers: { Accept: 'application/json' },
                    });
                    const payload = await response.json();

                    if (!response.ok) {
                        throw new Error(payload.message || 'Search failed.');
                    }

                    const entries = payload.data || [];
                    this.message = `${entries.length} result${entries.length === 1 ? '' : 's'}`;

                    entries.forEach((entry) => {
                        const row = document.createElement('article');
                        row.className = 'space-y-1 p-4';

                        const characters = document.createElement('h2');
                        characters.className = 'text-xl font-semibold text-gray-900 dark:text-white';
                        characters.textContent = [entry.simplified, entry.traditional]
                            .filter((value, index, values) => value && values.indexOf(value) === index)
                            .join(' / ') || 'No Chinese characters';
                        row.append(characters);

                        const pinyin = document.createElement('p');
                        pinyin.className = 'text-sm text-gray-600 dark:text-gray-300';
                        pinyin.textContent = `Pinyin: ${entry.pinyin_accented || entry.pinyin || entry.pinyin_clean || '—'}`;
                        row.append(pinyin);

                        const vietnamese = document.createElement('p');
                        vietnamese.className = 'whitespace-pre-line text-sm text-gray-800 dark:text-gray-100';
                        vietnamese.textContent = `Vietnamese: ${entry.vietnamese || '—'}`;
                        row.append(vietnamese);

                        if (entry.audio_url) {
                            const audio = document.createElement('audio');
                            audio.controls = true;
                            audio.preload = 'none';
                            audio.src = entry.audio_url;
                            audio.className = 'mt-2 h-9 max-w-full';
                            row.append(audio);
                        }

                        results.append(row);
                    });
                } catch (error) {
                    this.message = error.message;
                } finally {
                    this.loading = false;
                }
            },
        };
    }
</script>
@endpush
