@props(['id' => null, 'maxWidth' => null])

<x-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
    <div class="px-6 py-5 bg-white dark:bg-[#0d1322]">
        <div
            class="text-base font-bold text-slate-900 dark:text-white pb-3 border-b border-slate-100 dark:border-slate-800">
            {{ $title }}
        </div>

        <div class="mt-4 text-xs text-slate-600 dark:text-slate-400">
            {{ $content }}
        </div>
    </div>

    <div
        class="flex flex-row justify-end px-6 py-3.5 bg-slate-50 dark:bg-slate-900/80 border-t border-slate-200 dark:border-slate-800 text-end gap-2">
        {{ $footer }}
    </div>
</x-modal>
