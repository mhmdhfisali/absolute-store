@props(['submit'])

<div {{ $attributes->merge(['class' => 'md:grid md:grid-cols-3 md:gap-6']) }}>
    <x-section-title>
        <x-slot name="title">{{ $title }}</x-slot>
        <x-slot name="description">{{ $description }}</x-slot>
    </x-section-title>

    <div class="mt-5 md:mt-0 md:col-span-2">
        <form wire:submit="{{ $submit }}">
            <div class="card p-6 {{ isset($actions) ? 'rounded-b-none' : '' }}">
                <div class="grid grid-cols-6 gap-6">
                    {{ $form }}
                </div>
            </div>

            @if (isset($actions))
                <div
                    class="flex items-center justify-end px-6 py-3 bg-slate-50 dark:bg-slate-900/80 border-x border-b border-slate-200 dark:border-slate-800 rounded-b-2xl">
                    {{ $actions }}
                </div>
            @endif
        </form>
    </div>
</div>
