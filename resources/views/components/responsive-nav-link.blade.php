@props(['active'])

@php
    $classes =
        $active ?? false
            ? 'block w-full px-3 py-2 rounded-xl text-xs font-bold bg-indigo-600 text-white transition'
            : 'block w-full px-3 py-2 rounded-xl text-xs font-medium text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white transition';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
