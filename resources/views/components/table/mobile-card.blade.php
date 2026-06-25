@props(['layout' => 'between'])

@php
$layoutClass = [
    'between' => 'flex items-center justify-between',
    'col' => 'flex flex-col gap-2',
    'y-2' => 'space-y-2',
    'y-3' => 'space-y-3',
    'gap-3' => 'flex items-center gap-3',
    'none' => '',
][$layout] ?? 'flex items-center justify-between';
@endphp

<div {{ $attributes->merge(['class' => "bg-surface-800/50 border border-white/5 rounded-xl p-4 {$layoutClass}"]) }}>
    {{ $slot }}
</div>
