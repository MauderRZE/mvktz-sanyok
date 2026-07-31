<div>
<x-ui.page-wrapper>
    <x-ui.flash />
<x-ui.toolbar :count="count($contracts)" label="Всього" buttonLabel="Додати" />

    {{-- Filters Bar --}}
    <x-ui.card class="p-4 mb-4 space-y-4">
        <div class="flex flex-col lg:flex-row gap-4 items-center">
            <div class="flex-1 w-full flex gap-2">
                <div class="flex-1">
                    <x-form.search wire:model.live.debounce.300ms="search" placeholder="Пошук за номером договору, постачальником..." />
                </div>
                @if($search !== '' || !empty($filterSupplier))
                    <button wire:click="resetFilters" class="px-4 py-2 bg-white/5 hover:bg-white/10 text-xs text-gray-400 hover:text-white rounded-xl border border-white/10 transition-colors shrink-0 flex items-center gap-1.5" title="Скинути всі фільтри">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        <span>Скинути</span>
                    </button>
                @endif
            </div>
            <div class="w-full lg:w-auto">
                <x-form.multi-select label="Постачальники" :selectedCount="count($filterSupplier)">
                    <label class="flex items-center gap-2 text-xs font-semibold text-amber-400 cursor-pointer py-1">
                        <input type="checkbox" value="null" wire:model.live="filterSupplier" class="rounded border-white/10 bg-surface-900 text-brand-500 focus:ring-0 focus:ring-offset-0">
                        <span>[Не вказано / Null]</span>
                    </label>
                    @foreach($suppliersList as $sup)
                        <label class="flex items-center gap-2 text-xs text-gray-300 hover:text-white cursor-pointer py-1">
                            <input type="checkbox" value="{{ $sup->id }}" wire:model.live="filterSupplier" class="rounded border-white/10 bg-surface-900 text-brand-500 focus:ring-0 focus:ring-offset-0">
                            <span>{{ $sup->supplier_name }}</span>
                        </label>
                    @endforeach
                </x-form.multi-select>
            </div>
        </div>
    </x-ui.card>

    @if($isOpen)
    <x-ui.modal title="{{ $form->contractId ? 'Редагувати' : 'Додати' }} договір" maxWidth="md">
            <div>
                    <x-form.input label="Номер договору" model="form.contract_number" type="text" />
                </div>
                <div>
                    <x-form.input label="Дата договору" model="form.contract_date" type="date" />
                </div>
                <div>
                    <x-form.select label="Постачальник" model="form.supplier_id">
                            <option value="">Оберіть постачальника...</option>
                        @foreach($suppliersList as $sup)
                            <option value="{{ $sup->id }}">{{ $sup->supplier_name }}</option>
                        @endforeach
                        </x-form.select>
                </div>
                <div>
                    <x-form.input label="Посилання на договір" model="form.contract_link" type="url" />
                </div>
        </x-ui.modal>
    @endif

    {{-- Desktop --}}
    <x-table.wrapper>
            <x-slot name="headers">
                    <x-table.th align="left" width="20">ID</x-table.th>
                    <x-table.th align="left">Номер</x-table.th>
                    <x-table.th align="left">Дата</x-table.th>
                    <x-table.th align="left">Постачальник</x-table.th>
                    <x-table.th align="center">Посилання</x-table.th>
                    <x-table.th align="right" width="32">Дії</x-table.th>
                </x-slot>
            
                @forelse($contracts as $c)
                <x-table.tr>
                    <x-table.td align="left" muted>#{{ $c->id }}</x-table.td>
                    <x-table.td align="left" primary>{{ $c->contract_number }}</x-table.td>
                    <x-table.td align="left">{{ $c->contract_date }}</x-table.td>
                    <x-table.td align="left" class="text-gray-400">{{ $c->supplier->supplier_name ?? '-' }}</x-table.td>
                    <x-table.td align="center">
                        @if($c->contract_link)
                            <a href="{{ $c->contract_link }}" target="_blank" class="text-blue-500 hover:text-blue-700" title="Відкрити">
                                <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                            </a>
                        @else
                            -
                        @endif
                    </x-table.td>
                    <x-table.td align="right">
                        <x-ui.action-buttons id="{{ $c->id }}" />
                    </x-table.td>
                </x-table.tr>
                @empty
                <x-table.empty colspan="5" />
                @endforelse
            
        </x-table.wrapper>

    {{-- Mobile --}}
    <x-table.mobile-list>
        @forelse($contracts as $c)
        <x-table.mobile-card layout="col">
            <x-table.mobile-card-header>
                <x-ui.text-block title="Договір №{{ $c->contract_number }}" subtitle="ID: {{ $c->id }} | Дата: {{ $c->contract_date }}" />
                <x-ui.action-buttons id="{{ $c->id }}" />
            </x-table.mobile-card-header>
            <x-table.mobile-card-footer>
                Постачальник: {{ $c->supplier->supplier_name ?? '-' }}
                @if($c->contract_link)
                    | <a href="{{ $c->contract_link }}" target="_blank" class="text-blue-500 underline text-sm ml-2">Посилання</a>
                @endif
            </x-table.mobile-card-footer>
        </x-table.mobile-card>
        @empty
        <x-table.mobile-empty />
        @endforelse
    </x-table.mobile-list>
</x-ui.page-wrapper>
</div>
