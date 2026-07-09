@props(['title', 'subtitle' => null, 'titleClass' => 'text-sm text-white font-medium', 'subtitleClass' => 'text-xs text-gray-500'])

<div {{ $attributes }}>
    <p class="{{ $titleClass }}">{{ html_entity_decode($title, ENT_QUOTES, 'UTF-8') }}</p>
    @if($subtitle)
        <p class="{{ $subtitleClass }} mt-0.5">{{ html_entity_decode($subtitle, ENT_QUOTES, 'UTF-8') }}</p>
    @endif
</div>
