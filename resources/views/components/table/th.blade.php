@props(['width' => null, 'align' => 'left'])

@php
$alignClass = [
    'left' => 'text-left',
    'center' => 'text-center',
    'right' => 'text-right',
][$align] ?? 'text-left';

$widthClass = $width ? 'w-' . $width : '';
@endphp

<th {{ $attributes->merge(['class' => "px-5 py-3 {$alignClass} {$widthClass} text-xs font-semibold text-gray-500 uppercase tracking-wider"]) }}>
    {{ $slot }}
</th>
