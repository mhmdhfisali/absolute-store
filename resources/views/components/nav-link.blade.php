@props(['active'])

@php
    $classes =
        $active ?? false
            ? 'inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-bold bg-indigo-600 text-white shadow-sm transition'
            : 'inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-medium text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 transition';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
