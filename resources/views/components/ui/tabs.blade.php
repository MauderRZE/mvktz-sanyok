@props(['active' => null])

<div x-data="{ activeTab: '{{ $active }}' }">
    <div class="border-b border-white/10 mb-4">
        <nav class="-mb-px flex space-x-6" aria-label="Tabs">
            {{ $nav }}
        </nav>
    </div>

    <div class="mt-4">
        {{ $slot }}
    </div>
</div>
