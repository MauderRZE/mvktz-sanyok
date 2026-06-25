@props(['variant' => 'primary', 'type' => 'button'])

@php
$baseClasses = 'inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200';
$variantClasses = [
    'primary' => 'bg-gradient-to-r from-brand-600 to-brand-500 hover:from-brand-500 hover:to-brand-400 text-white shadow-lg shadow-brand-500/20 hover:shadow-brand-500/30',
    'secondary' => 'text-gray-400 hover:text-white bg-white/5 hover:bg-white/10',
][$variant] ?? '';
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $baseClasses . ' ' . $variantClasses]) }}>
    {{ $slot }}
</button>
