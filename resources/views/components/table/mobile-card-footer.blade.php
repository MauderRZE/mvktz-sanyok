@props(['flex' => false])

@php
$baseClass = 'border-t border-white/5 pt-2 text-xs text-gray-400';
if ($flex) {
    $baseClass .= ' flex items-center justify-between gap-4';
}
@endphp

<div {{ $attributes->merge(['class' => $baseClass]) }}>
    {{ $slot }}
</div>
