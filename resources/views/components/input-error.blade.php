@props(['for'])

@error($for)
    <p {{ $attributes->merge(['class' => 'text-xs text-rose-500 font-semibold mt-1']) }}>{{ $message }}</p>
@enderror
