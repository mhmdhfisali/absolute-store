<button
    {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center px-4 py-2 bg-rose-600 hover:bg-rose-500 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-wider shadow-sm transition active:scale-95 focus:outline-none disabled:opacity-50 cursor-pointer']) }}>
    {{ $slot }}
</button>
