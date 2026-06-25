@props(['name', 'label', 'icon' => null])

<button 
    @click="activeTab = '{{ $name }}'"
    :class="{'border-brand-500 text-brand-400': activeTab === '{{ $name }}', 'border-transparent text-gray-400 hover:text-gray-200 hover:border-gray-300': activeTab !== '{{ $name }}'}"
    class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm flex items-center gap-2 transition-colors"
>
    @if($icon)
        {!! $icon !!}
    @endif
    {{ $label }}
</button>
