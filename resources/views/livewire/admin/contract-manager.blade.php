<div>
<x-ui.page-wrapper>
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
                    <x-table.td align="left" primary>{{ $c->contract_number }}</x-table.td>
                    <x-table.td align="left">{{ $c->contract_date }}</x-table.td>
                    <x-table.td align="left" class="text-gray-400">{{ $c->supplier->supplier_name ?? '-' }}</x-table.td>
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
            </x-table.mobile-card-footer>
        </x-table.mobile-card>
        @empty
        <x-table.mobile-empty />
        @endforelse
    </x-table.mobile-list>
</x-ui.page-wrapper>
</div>
