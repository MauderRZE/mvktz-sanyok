<div>
<x-ui.page-wrapper>
    <x-ui.flash />
<x-ui.toolbar :count="$suppliers->total()" label="Всього" buttonLabel="Додати" />

    {{-- Filters Bar --}}
    <x-ui.card class="p-4 mb-4 space-y-4">
        <div class="flex flex-col lg:flex-row gap-4 items-center">
            <div class="flex-1 w-full flex gap-2">
                <div class="flex-1">
                    <x-form.search wire:model.live.debounce.300ms="search" placeholder="Пошук за назвою постачальника, кодом ЄДРПОУ/ІПН..." />
                </div>
                @if($search !== '' || !empty($filterType))
                    <button wire:click="resetFilters" class="px-4 py-2 bg-white/5 hover:bg-white/10 text-xs text-gray-400 hover:text-white rounded-xl border border-white/10 transition-colors shrink-0 flex items-center gap-1.5" title="Скинути всі фільтри">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        <span>Скинути</span>
                    </button>
                @endif
            </div>
            <div class="w-full lg:w-auto">
                <x-form.multi-select label="Тип постачальника" :selectedCount="count($filterType)">
                    <label class="flex items-center gap-2 text-xs font-semibold text-amber-400 cursor-pointer py-1">
                        <input type="checkbox" value="null" wire:model.live="filterType" class="rounded border-white/10 bg-surface-900 text-brand-500 focus:ring-0 focus:ring-offset-0">
                        <span>[Не вказано / Null]</span>
                    </label>
                    @foreach($supplierTypes as $type)
                        <label class="flex items-center gap-2 text-xs text-gray-300 hover:text-white cursor-pointer py-1">
                            <input type="checkbox" value="{{ $type->id }}" wire:model.live="filterType" class="rounded border-white/10 bg-surface-900 text-brand-500 focus:ring-0 focus:ring-offset-0">
                            <span>{{ $type->type_name }}</span>
                        </label>
                    @endforeach
                </x-form.multi-select>
            </div>
        </div>
    </x-ui.card>

    @if($isOpen)
    <x-ui.modal title="{{ $form->supplierId ? 'Редагувати' : 'Додати' }} постачальника" maxWidth="md">
            <x-form.input label="Назва постачальника" model="form.supplier_name" type="text" />
            <x-form.select label="Тип постачальника" model="form.supplier_type_id">
                <option value="">Оберіть тип</option>
                @foreach($supplierTypes as $type)
                    <option value="{{ $type->id }}">{{ $type->type_name }}</option>
                @endforeach
            </x-form.select>
            <x-form.input label="Код ЄДРПОУ / ІПН" model="form.tax_code" type="text" />
        </x-ui.modal>
    @endif

    {{-- Desktop --}}
    <x-table.wrapper>
            <x-slot name="headers">
                    <x-table.th align="left" width="20">ID</x-table.th>
                    <x-table.th align="left">Назва</x-table.th>
                    <x-table.th align="left">Тип</x-table.th>
                    <x-table.th align="left">Код ЄДРПОУ/ІПН</x-table.th>
                    <x-table.th align="right" width="32">Дії</x-table.th>
                </x-slot>
            
                @forelse($suppliers as $sup)
                <x-table.tr>
                    <x-table.td align="left" muted>#{{ $sup->id }}</x-table.td>
                    <x-table.td align="left" primary>{{ $sup->supplier_name }}</x-table.td>
                    <x-table.td align="left">{{ $sup->supplierType?->type_name ?? '-' }}</x-table.td>
                    <x-table.td align="left">{{ $sup->tax_code ?? '-' }}</x-table.td>
                    <x-table.td align="right">
                        <x-ui.action-buttons id="{{ $sup->id }}" />
                    </x-table.td>
                </x-table.tr>
                @empty
                <x-table.empty colspan="5" />
                @endforelse
            
        </x-table.wrapper>

    {{-- Mobile --}}
    <x-table.mobile-list>
        @forelse($suppliers as $sup)
        <x-table.mobile-card>
            <x-ui.text-block title="{{ $sup->supplier_name }}" subtitle="ID: {{ $sup->id }} | Тип: {{ $sup->supplierType?->type_name ?? '-' }} | Код: {{ $sup->tax_code ?? '-' }}" />
            <x-ui.action-buttons id="{{ $sup->id }}" />
        </x-table.mobile-card>
        @empty
        <x-table.mobile-empty />
        @endforelse
    </x-table.mobile-list>

    <div class="mt-4">
        {{ $suppliers->links() }}
    </div>
</x-ui.page-wrapper>
</div>
