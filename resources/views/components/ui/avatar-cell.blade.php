@props(['name', 'title', 'subtitle' => null, 'size' => 'md'])

@php
$letter = mb_strtoupper(mb_substr($name ?? $title ?? 'U', 0, 1, 'UTF-8'), 'UTF-8');
$sizeClass = [
    'sm' => 'w-8 h-8 text-xs',
    'md' => 'w-8 h-8 text-xs',
    'lg' => 'w-10 h-10 text-sm',
][$size] ?? 'w-8 h-8 text-xs';
@endphp

<div {{ $attributes->merge(['class' => 'flex items-center gap-3']) }}>
    <div class="rounded-full bg-gradient-to-br from-brand-400 to-brand-600 flex items-center justify-center font-bold text-white shrink-0 {{ $sizeClass }}">
        {{ $letter }}
    </div>
    <div class="min-w-0 flex-1">
        <p class="text-sm text-white font-medium truncate">{{ $title }}</p>
        @if($subtitle)
            <p class="text-xs text-gray-500 truncate">{{ $subtitle }}</p>
        @endif
    </div>
</div>
