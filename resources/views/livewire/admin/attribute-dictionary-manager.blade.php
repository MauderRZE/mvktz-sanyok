<div>
<x-ui.page-wrapper>
    <x-ui.flash />
    <x-ui.toolbar :count="count($dictAttributes)" label="Всього атрибутів" buttonLabel="Додати атрибут" />

    @if($isOpen)
    <x-ui.modal title="{{ $attributeId ? 'Редагувати' : 'Додати' }} атрибут" maxWidth="md">
        <x-form.input label="Назва характеристики" model="name" type="text" />
    </x-ui.modal>
    @endif

    {{-- Desktop --}}
    <x-table.wrapper>
        <x-slot name="headers">
            <x-table.th align="left" width="20">ID</x-table.th>
            <x-table.th align="left">Назва</x-table.th>
            <x-table.th align="right" width="32">Дії</x-table.th>
        </x-slot>

        @forelse($dictAttributes as $attr)
        <x-table.tr>
            <x-table.td align="left" muted>#{{ $attr->id }}</x-table.td>
            <x-table.td align="left" primary>{{ $attr->name }}</x-table.td>
            <x-table.td align="right">
                <x-ui.action-buttons id="{{ $attr->id }}" />
            </x-table.td>
        </x-table.tr>
        @empty
        <x-table.empty colspan="3" />
        @endforelse
    </x-table.wrapper>

    {{-- Mobile --}}
    <x-table.mobile-list>
        @forelse($dictAttributes as $attr)
        <x-table.mobile-card>
            <x-ui.text-block title="{{ $attr->name }}" subtitle="ID: {{ $attr->id }}" />
            <x-ui.action-buttons id="{{ $attr->id }}" />
        </x-table.mobile-card>
        @empty
        <x-table.mobile-empty />
        @endforelse
    </x-table.mobile-list>
</x-ui.page-wrapper>
</div>
