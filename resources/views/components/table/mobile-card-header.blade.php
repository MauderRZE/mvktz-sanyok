@props(['align' => 'center'])

@php
$alignClass = $align === 'start' ? 'items-start' : 'items-center';
@endphp

<div {{ $attributes->merge(['class' => "flex {$alignClass} justify-between gap-4"]) }}>
    {{ $slot }}
</div>
