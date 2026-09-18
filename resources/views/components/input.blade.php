@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'form-input-theme text-xs font-medium rounded-xl']) !!}>
