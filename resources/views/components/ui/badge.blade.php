@props(['status', 'dot' => true])

@php
$variants = [
    'В експлуатації' => 'bg-emerald-500/10 text-emerald-400',
    'Працює' => 'bg-emerald-500/10 text-emerald-400',
    'Виконано' => 'bg-emerald-500/10 text-emerald-400',
    'Активна' => 'bg-emerald-500/10 text-emerald-400',
    'На складі' => 'bg-amber-500/10 text-amber-400',
    'В роботі' => 'bg-amber-500/10 text-amber-400',
    'Призупинена' => 'bg-amber-500/10 text-amber-400',
    'В ремонті' => 'bg-amber-500/10 text-amber-400',
    'Списано' => 'bg-red-500/10 text-red-400',
    'Відкрито' => 'bg-red-500/10 text-red-400',
    'Знято' => 'bg-gray-500/10 text-gray-400',
    'default' => 'bg-brand-500/10 text-brand-300'
];
$dotColors = [
    'В експлуатації' => 'bg-emerald-400',
    'На складі' => 'bg-amber-400',
    'Списано' => 'bg-red-400',
];

$badgeClass = $variants[$status] ?? $variants['default'];
$dotClass = $dotColors[$status] ?? '';
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium " . $badgeClass]) }}>
    @if($dot && $dotClass)
        <span class="w-1.5 h-1.5 rounded-full {{ $dotClass }}"></span>
    @endif
    {{ $slot->isEmpty() ? $status : $slot }}
</span>
