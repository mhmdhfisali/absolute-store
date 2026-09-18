<a
    {{ $attributes->merge(['class' => 'block w-full px-4 py-2 text-start text-xs font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-indigo-600 dark:hover:text-white focus:outline-none transition rounded-lg']) }}>
    {{ $slot }}
</a>
