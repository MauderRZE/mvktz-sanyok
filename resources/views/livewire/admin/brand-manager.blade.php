<x-ui.page-wrapper>
    <x-ui.flash />
    <x-ui.toolbar :count="count($brands)" label="Всього брендів" buttonLabel="Додати бренд" />

    @if($isOpen)
        <x-ui.modal title="{{ $brandId ? 'Редагувати' : 'Додати' }} бренд" maxWidth="md">
            <x-form.input label="Назва бренду" model="brandtz_name" type="text" />
        </x-ui.modal>
    @endif

    {{-- Desktop --}}
    <x-table.wrapper>
        <x-slot name="headers">
            <x-table.th align="left" width="20">ID</x-table.th>
            <x-table.th align="left">Назва бренду</x-table.th>
            <x-table.th align="right" width="32">Дії</x-table.th>
        </x-slot>
    
        @forelse($brands as $brand)
            <x-table.tr>
                <x-table.td align="left" muted>#{{ $brand->id }}</x-table.td>
                <x-table.td align="left" primary>{{ $brand->brandtz_name }}</x-table.td>
                <x-table.td align="right">
                    <x-ui.action-buttons id="{{ $brand->id }}" />
                </x-table.td>
            </x-table.tr>
        @empty
            <x-table.empty colspan="3" />
        @endforelse
    </x-table.wrapper>

    {{-- Mobile --}}
    <x-table.mobile-list>
        @forelse($brands as $brand)
            <x-table.mobile-card>
                <x-ui.text-block title="{{ $brand->brandtz_name }}" subtitle="ID: {{ $brand->id }}" />
                <x-ui.action-buttons id="{{ $brand->id }}" />
            </x-table.mobile-card>
        @empty
            <x-table.mobile-empty />
        @endforelse
    </x-table.mobile-list>
</x-ui.page-wrapper>
