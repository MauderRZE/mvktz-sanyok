@props(['align' => 'left', 'primary' => false])

@php
$alignClass = [
    'left' => 'text-left',
    'center' => 'text-center',
    'right' => 'text-right',
][$align] ?? 'text-left';

$textClass = $primary ? 'text-white font-medium' : 'text-gray-300';
@endphp

<td {{ $attributes->merge(['class' => "px-5 py-3 text-sm {$alignClass} {$textClass}"]) }}>
    {{ $slot }}
</td>
