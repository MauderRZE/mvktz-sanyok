<div>
<x-ui.page-wrapper>
    <x-ui.flash />
<x-ui.toolbar :count="count($suppliers)" label="Всього" buttonLabel="Додати" />

    @if($isOpen)
    <x-ui.modal title="{{ $supplierId ? 'Редагувати' : 'Додати' }} постачальника" maxWidth="md">
            <x-form.input label="Назва постачальника" model="supplier_name" type="text" />
            <x-form.select label="Тип постачальника" model="supplier_type_id">
                <option value="">Оберіть тип</option>
                @foreach($supplierTypes as $type)
                    <option value="{{ $type->id }}">{{ $type->type_name }}</option>
                @endforeach
            </x-form.select>
            <x-form.input label="Код ЄДРПОУ / ІПН" model="tax_code" type="text" />
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
</x-ui.page-wrapper>
</div>
