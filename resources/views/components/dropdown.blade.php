@props([
    'align' => 'right',
    'width' => '48',
    'contentClasses' => 'p-1.5 bg-white dark:bg-[#0d1322] border border-slate-200 dark:border-slate-800',
    'dropdownClasses' => '',
])

@php
    $alignmentClasses = match ($align) {
        'left' => 'origin-top-left start-0',
        'top' => 'origin-top',
        'none', 'false' => '',
        default => 'origin-top-right end-0',
    };

    $width = match ($width) {
        '48' => 'w-48',
        '60' => 'w-60',
        default => 'w-48',
    };
@endphp

<div class="relative" x-data="{ open: false }" @click.away="open = false" @close.stop="open = false">
    <div @click="open = ! open">
        {{ $trigger }}
    </div>

    <div x-show="open" x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-100" x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute z-50 mt-2 {{ $width }} rounded-2xl shadow-xl {{ $alignmentClasses }} {{ $dropdownClasses }}"
        style="display: none;" @click="open = false">
        <div class="rounded-2xl {{ $contentClasses }}">
            {{ $content }}
        </div>
    </div>
</div>
