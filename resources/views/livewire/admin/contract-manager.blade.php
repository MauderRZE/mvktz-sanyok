<div class="space-y-6">
    <x-ui.flash />
<x-ui.toolbar :count="count($contracts)" label="Всього" buttonLabel="Додати" />

    @if($isOpen)
    <x-ui.modal title="{{ $contractId ? 'Редагувати' : 'Додати' }} договір" maxWidth="md">
            <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Номер договору</label>
                    <input type="text" wire:model="contract_number" class="w-full px-4 py-2.5 bg-surface-900/60 border border-white/10 rounded-xl text-white text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                    @error('contract_number') <span class="text-xs text-red-400 mt-1">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Дата договору</label>
                    <input type="date" wire:model="contract_date" class="w-full px-4 py-2.5 bg-surface-900/60 border border-white/10 rounded-xl text-white text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                    @error('contract_date') <span class="text-xs text-red-400 mt-1">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Постачальник</label>
                    <select wire:model="supplier_id" class="w-full px-4 py-2.5 bg-surface-900/60 border border-white/10 rounded-xl text-white text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                        <option value="">Оберіть постачальника...</option>
                        @foreach($suppliersList as $sup)
                            <option value="{{ $sup->id }}">{{ $sup->supplier_name }}</option>
                        @endforeach
                    </select>
                    @error('supplier_id') <span class="text-xs text-red-400 mt-1">{{ $message }}</span>@enderror
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
                    <x-table.th align="right" width="32">Дії</x-table.th>
                </x-slot>
            <tbody class="divide-y divide-white/5">
                @forelse($contracts as $c)
                <tr class="hover:bg-white/[0.02] transition-colors">
                    <x-table.td align="left" class="text-gray-500">#{{ $c->id }}</x-table.td>
                    <x-table.td align="left" primary class="text-white font-medium">{{ $c->contract_number }}</x-table.td>
                    <x-table.td align="left" class="text-gray-300">{{ $c->contract_date }}</x-table.td>
                    <x-table.td align="left" class="text-gray-400">{{ $c->supplier->supplier_name ?? '-' }}</x-table.td>
                    <x-table.td align="right">
                        <x-ui.action-buttons id="{{ $c->id }}" />
                    </x-table.td>
                </tr>
                @empty
                <tr><x-table.td colspan="5" class="px-5 py-10 text-center text-gray-600 text-sm">Немає записів</x-table.td></tr>
                @endforelse
            </tbody>
        </x-table.wrapper>

    {{-- Mobile --}}
    <x-table.mobile-list>
        @forelse($contracts as $c)
        <div class="bg-surface-800/50 border border-white/5 rounded-xl p-4 flex flex-col gap-2">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-white font-medium">Договір №{{ $c->contract_number }}</p>
                    <p class="text-xs text-gray-500">ID: {{ $c->id }} | Дата: {{ $c->contract_date }}</p>
                </div>
                <x-ui.action-buttons id="{{ $c->id }}" />
            </div>
            <div class="text-xs text-gray-400 border-t border-white/5 pt-2">
                Постачальник: {{ $c->supplier->supplier_name ?? '-' }}
            </div>
        </div>
        @empty
        <div class="text-center py-10 text-gray-600 text-sm">Немає записів</div>
        @endforelse
    </x-table.mobile-list>
</div>
