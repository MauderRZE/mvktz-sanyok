@props(['title', 'subtitle' => null, 'titleClass' => 'text-sm text-white font-medium', 'subtitleClass' => 'text-xs text-gray-500'])

<div {{ $attributes }}>
    <p class="{{ $titleClass }}">{{ $title }}</p>
    @if($subtitle)
        <p class="{{ $subtitleClass }} mt-0.5">{{ $subtitle }}</p>
    @endif
</div>
