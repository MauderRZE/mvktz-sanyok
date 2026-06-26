@props(['label' => 'Оберіть', 'selectedCount' => 0])

<div x-data="{ open: false }" class="relative w-full">
    <button @click="open = !open" type="button" class="w-full bg-surface-900 border border-white/10 rounded-xl px-3 py-2 text-xs text-left text-white focus:ring-2 focus:ring-brand-500/50 focus:border-brand-500 transition-colors flex items-center justify-between">
        <span class="truncate">
            {{ $label }} 
            @if($selectedCount > 0)
                <span class="ml-1 bg-brand-500/20 text-brand-300 px-1.5 py-0.5 rounded text-[10px] font-bold">{{ $selectedCount }}</span>
            @endif
        </span>
        <svg class="w-3.5 h-3.5 text-gray-500 transition-transform duration-200 shrink-0" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
    </button>
    
    <div x-show="open" @click.away="open = false" x-transition class="absolute z-30 mt-1 w-full bg-surface-950 border border-white/10 rounded-xl p-2.5 shadow-2xl max-h-60 overflow-y-auto space-y-1.5" style="display: none;">
        {{ $slot }}
    </div>
</div>
