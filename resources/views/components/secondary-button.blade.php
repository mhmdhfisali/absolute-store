<button
    {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 rounded-xl font-bold text-xs text-slate-700 dark:text-slate-300 uppercase tracking-wider shadow-sm transition active:scale-95 focus:outline-none disabled:opacity-25 cursor-pointer']) }}>
    {{ $slot }}
</button>
