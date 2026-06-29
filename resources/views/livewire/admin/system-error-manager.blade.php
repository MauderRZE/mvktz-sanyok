<x-ui.page-wrapper>
    <x-ui.flash />
    <x-ui.toolbar :count="$errorsList->total()" label="Всього" buttonLabel="Додати" />

    @if($isOpen)
    <x-ui.modal title="{{ $editingId ? 'Редагувати помилку' : 'Додати помилку' }}" maxWidth="md">
        <form wire:submit.prevent="store" id="errorForm" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Тип сторінки</label>
                <select wire:model.defer="pageType" class="w-full bg-surface-900 border border-white/10 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                    <option value="">Оберіть сторінку</option>
                    @foreach($this->availablePages as $key => $name)
                        <option value="{{ $key }}">{{ $name }} ({{ $key }})</option>
                    @endforeach
                </select>
                @error('pageType') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Тип помилки</label>
                <select wire:model.defer="errorType" class="w-full bg-surface-900 border border-white/10 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                    <option value="">Оберіть тип помилки</option>
                    @foreach($this->laravelErrorTypes as $key => $name)
                        <option value="{{ $key }}">{{ $name }}</option>
                    @endforeach
                </select>
                @error('errorType') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Текст помилки</label>
                <textarea wire:model.defer="errorText" rows="4" class="w-full bg-surface-900 border border-white/10 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors"></textarea>
                @error('errorText') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            @if($editingId)
            <div class="flex items-center">
                <input type="checkbox" wire:model.defer="isResolved" id="isResolved" class="rounded border-white/10 bg-surface-900 text-brand-500 focus:ring-brand-500">
                <label for="isResolved" class="ml-2 text-sm text-gray-300">Виконано</label>
            </div>
            @endif
        </form>
    </x-ui.modal>
    @endif

    {{-- Desktop Table --}}
    <x-table.wrapper>
        <x-slot name="headers">
            <x-table.th align="left">Сторінка</x-table.th>
            <x-table.th align="left">Помилка</x-table.th>
            <x-table.th align="center">Виконано</x-table.th>
            <x-table.th align="right">Дії</x-table.th>
        </x-slot>
        
        @forelse($errorsList as $error)
        <x-table.tr x-data="{ expanded: false }">
            <x-table.td align="left">
                <div class="font-medium text-white">{{ collect($this->availablePages)->get($error->page_type, $error->page_type) }}</div>
                <div class="text-[10px] text-gray-500 mt-1 flex items-center gap-1" title="{{ $error->created_at->format('d.m.Y H:i:s') }}">
                    <svg class="w-3 h-3 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $error->created_at->format('d.m.Y H:i') }}
                </div>
            </x-table.td>
            <x-table.td align="left">
                <div class="font-medium text-white">{{ collect($this->laravelErrorTypes)->get($error->error_type, $error->error_type) }}</div>
                <div :class="expanded ? 'whitespace-pre-wrap font-mono mt-2 bg-surface-900/50 p-2 rounded border border-white/5' : 'line-clamp-2 mt-1 cursor-pointer'" class="text-xs text-gray-500 transition-all" @click="expanded = !expanded" title="Натисніть щоб розгорнути">
                    {{ $error->error_text }}
                </div>
            </x-table.td>
            <x-table.td align="center">
                <div class="flex items-center justify-center">
                    <input type="checkbox" wire:click="toggleResolved({{ $error->id }})" {{ $error->is_resolved ? 'checked' : '' }} class="w-5 h-5 rounded border-white/10 bg-surface-900 text-brand-500 focus:ring-brand-500 cursor-pointer transition-colors hover:border-brand-500">
                </div>
            </x-table.td>
            <x-table.td align="right">
                <div class="flex items-center justify-end gap-2">
                    <button @click="expanded = !expanded" class="p-1.5 text-gray-400 hover:text-white hover:bg-white/5 rounded-lg transition-colors" title="Розгорнути/Згорнути">
                        <svg x-show="!expanded" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        <svg x-cloak x-show="expanded" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                    </button>
                    <x-ui.action-buttons id="{{ $error->id }}" />
                </div>
            </x-table.td>
        </x-table.tr>
        @empty
        <x-table.empty colspan="4">Помилок поки немає</x-table.empty>
        @endforelse
    </x-table.wrapper>
    
    @if($errorsList->hasPages())
        <div class="mt-4">
            {{ $errorsList->links() }}
        </div>
    @endif
</x-ui.page-wrapper>
