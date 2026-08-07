@props(['label' => 'Оберіть', 'selectedCount' => 0])

<div x-data="{ 
    open: false, 
    search: '',
    filterItems() {
        let q = this.search.toLowerCase().trim();
        let items = this.$refs.itemsContainer.children;
        for (let item of items) {
            let text = item.textContent || '';
            if (text.toLowerCase().includes(q)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        }
    },
    toggleAll(state) {
    let items = this.$refs.itemsContainer.children;
    for (let item of items) {
        if (!state || item.style.display !== 'none') {
            let checkbox = item.querySelector('input[type=checkbox]');
            if (checkbox && checkbox.checked !== state) {
                checkbox.checked = state;
                checkbox.dispatchEvent(new Event('change', { bubbles: true }));
            }
        }
    }
},

selectAll() { this.toggleAll(true); },
deselectAll() { this.toggleAll(false); }
}" class="relative w-full">
    <button @click="open = !open" type="button" class="w-full bg-surface-900 border border-white/10 rounded-xl px-3 py-2 text-xs text-left text-white focus:ring-2 focus:ring-brand-500/50 focus:border-brand-500 transition-colors flex items-center justify-between">
        <span class="truncate">
            {{ $label }} 
            @if($selectedCount > 0)
                <span class="ml-1 bg-brand-500/20 text-brand-300 px-1.5 py-0.5 rounded text-[10px] font-bold">{{ $selectedCount }}</span>
            @endif
        </span>
        <svg class="w-3.5 h-3.5 text-gray-500 transition-transform duration-200 shrink-0" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
    </button>
    
    <div x-show="open" @click.away="open = false; search = ''; filterItems();" x-transition class="absolute z-30 mt-1 w-full bg-surface-900 border border-white/10 rounded-xl p-2 shadow-2xl max-h-60 flex flex-col" style="display: none;">
        <!-- Search field inside multi-select -->
        <div class="p-1 shrink-0">
            <input x-model="search" @input="filterItems" type="text" placeholder="Пошук..." class="w-full bg-surface-900 border border-white/10 rounded-lg px-2.5 py-1 text-[11px] text-white focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
        </div>
        <div class="px-2 py-1.5 flex items-center justify-between border-b border-white/10 text-[11px] bg-white/[0.02] shrink-0">
            <button type="button" @click="selectAll()" class="text-brand-400 hover:text-brand-300 transition-colors font-medium">
                Виділити все
            </button>
            <button type="button" @click="deselectAll()" class="text-gray-400 hover:text-gray-300 transition-colors">
                Зняти все
            </button>
        </div>
        <div x-ref="itemsContainer" class="overflow-y-auto p-1 space-y-1 flex-1">
            {{ $slot }}
        </div>
    </div>
</div>

