@props(['align' => 'left', 'primary' => false, 'muted' => false])

@php
$alignClass = [
    'left' => 'text-left',
    'center' => 'text-center',
    'right' => 'text-right',
 ][$align] ?? 'text-left';

if ($primary) {
    $textClass = 'text-white font-medium';
} elseif ($muted) {
    $textClass = 'text-gray-500';
} else {
    $textClass = 'text-gray-300';
}
@endphp

<td {{ $attributes->merge(['class' => "px-5 py-3 text-sm {$alignClass} {$textClass}"]) }}>
    {{ $slot }}
</td>
