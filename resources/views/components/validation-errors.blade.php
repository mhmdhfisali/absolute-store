@if ($errors->any())
    <div
        {{ $attributes->merge(['class' => 'p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-500 text-xs font-semibold space-y-1']) }}>
        <div class="font-bold">{{ __('Whoops! Terjadi kesalahan.') }}</div>

        <ul class="list-disc list-inside space-y-0.5 text-[11px]">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
