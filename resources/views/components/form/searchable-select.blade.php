@props(['label', 'model', 'options' => [], 'placeholder' => 'Оберіть'])

<div wire:key="{{ $model }}" x-data="{
    open: false,
    search: '',
    value: @entangle($model),
    options: @js($options),
    get selectedLabel() {
        let opt = this.options.find(o => o.value == this.value);
        return opt ? opt.label : '{{ $placeholder }}';
    },
    get filteredOptions() {
        if (!this.search) return this.options;
        return this.options.filter(o => o.label.toLowerCase().includes(this.search.toLowerCase()));
    },
    selectOption(val) {
        this.value = val;
        this.open = false;
        this.search = '';
    }
}" class="relative w-full">
    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">{{ $label }}</label>
    
    <!-- Trigger Button -->
    <button @click="open = !open" type="button" class="w-full h-[42px] px-4 bg-surface-900/60 border border-white/10 rounded-xl text-left text-white text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors flex items-center justify-between">
        <span x-text="selectedLabel" class="truncate"></span>
        <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
    </button>

    <!-- Dropdown Content -->
    <div x-show="open" @click.away="open = false; search = '';" x-transition class="absolute z-50 mt-1 w-full bg-surface-950 border border-white/10 rounded-xl p-2 shadow-2xl space-y-2 max-h-60 flex flex-col" style="display: none;">
        <!-- Search Input -->
        <div class="relative shrink-0">
            <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input x-model="search" type="text" placeholder="Пошук..." class="w-full bg-surface-900 border border-white/10 rounded-lg pl-9 pr-3 py-1.5 text-xs text-white focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
        </div>

        <!-- Options List -->
        <div class="overflow-y-auto max-h-40 flex-1 space-y-0.5">
            <!-- Reset/Null option -->
            <button @click="selectOption(null)" type="button" class="w-full text-left px-3 py-1.5 text-xs text-gray-400 hover:bg-surface-800 hover:text-white rounded transition-colors">
                {{ $placeholder }}
            </button>
            <template x-for="opt in filteredOptions" :key="opt.value">
                <button @click="selectOption(opt.value)" type="button" class="w-full text-left px-3 py-1.5 text-xs text-white hover:bg-brand-500 hover:text-white rounded transition-colors flex items-center justify-between" :class="{ 'bg-brand-500/20 text-brand-300': value == opt.value }">
                    <span x-text="opt.label"></span>
                    <svg x-show="value == opt.value" class="w-3.5 h-3.5 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </button>
            </template>
            <div x-show="filteredOptions.length === 0" class="text-center py-2 text-xs text-gray-500">
                Нічого не знайдено
            </div>
        </div>
    </div>
    @error($model) <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span> @enderror
</div>
