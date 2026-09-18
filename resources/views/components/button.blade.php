<button
    {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-500 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-wider shadow-sm transition active:scale-95 focus:outline-none disabled:opacity-50 cursor-pointer']) }}>
    {{ $slot }}
</button>
