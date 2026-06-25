<div class="space-y-6">
    <x-ui.flash />
<x-ui.toolbar :count="count($contracts)" label="Всього" buttonLabel="Додати" />

    @if($isOpen)
    <x-ui.modal title="{{ $contractId ? 'Редагувати' : 'Додати' }} договір" maxWidth="md">
            <div>
                    <x-form.input label="Номер договору" model="contract_number" type="text" />
                </div>
                <div>
                    <x-form.input label="Дата договору" model="contract_date" type="date" />
                </div>
                <div>
                    <x-form.select label="Постачальник" model="supplier_id">
                            <option value="">Оберіть постачальника...</option>
                        @foreach($suppliersList as $sup)
                            <option value="{{ $sup->id }}">{{ $sup->supplier_name }}</option>
                        @endforeach
                        </x-form.select>
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
            
                @forelse($contracts as $c)
                <x-table.tr>
                    <x-table.td align="left" muted>#{{ $c->id }}</x-table.td>
                    <x-table.td align="left" primary class="text-white font-medium">{{ $c->contract_number }}</x-table.td>
                    <x-table.td align="left" class="text-gray-300">{{ $c->contract_date }}</x-table.td>
                    <x-table.td align="left" class="text-gray-400">{{ $c->supplier->supplier_name ?? '-' }}</x-table.td>
                    <x-table.td align="right">
                        <x-ui.action-buttons id="{{ $c->id }}" />
                    </x-table.td>
                </x-table.tr>
                @empty
                <x-table.tr><x-table.td colspan="5" class="px-5 py-10 text-center text-gray-600 text-sm">Немає записів</x-table.td></x-table.tr>
                @endforelse
            
        </x-table.wrapper>

    {{-- Mobile --}}
    <x-table.mobile-list>
        @forelse($contracts as $c)
        <x-table.mobile-card layout="col">
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
        </x-table.mobile-card>
        @empty
        <div class="text-center py-10 text-gray-600 text-sm">Немає записів</div>
        @endforelse
    </x-table.mobile-list>
</div>
