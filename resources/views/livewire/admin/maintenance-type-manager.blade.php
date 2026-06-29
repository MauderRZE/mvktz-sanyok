<x-ui.page-wrapper>
    <x-ui.flash />
<x-ui.toolbar :count="count($types)" label="Всього" buttonLabel="Додати" />

    @if($isOpen)
    <x-ui.modal title="{{ $typeId ? 'Редагувати' : 'Додати' }} тип обслуговування" maxWidth="md">
            <x-form.input label="Назва типу робіт" model="type_name" type="text" />
        </x-ui.modal>
    @endif

    {{-- Desktop --}}
    <x-table.wrapper>
            <x-slot name="headers">
                    <x-table.th align="left" width="20">ID</x-table.th>
                    <x-table.th align="left">Назва типу обслуговування</x-table.th>
                    <x-table.th align="right" width="32">Дії</x-table.th>
                </x-slot>
            
                @forelse($types as $type)
                <x-table.tr>
                    <x-table.td align="left" muted>#{{ $type->id }}</x-table.td>
                    <x-table.td align="left" primary>{{ $type->type_name }}</x-table.td>
                    <x-table.td align="right">
                        <x-ui.action-buttons id="{{ $type->id }}" />
                    </x-table.td>
                </x-table.tr>
                @empty
                <x-table.empty colspan="3" />
                @endforelse
            
        </x-table.wrapper>

    {{-- Mobile --}}
    <x-table.mobile-list>
        @forelse($types as $type)
        <x-table.mobile-card>
            <x-ui.text-block title="{{ $type->type_name }}" subtitle="ID: {{ $type->id }}" />
            <x-ui.action-buttons id="{{ $type->id }}" />
        </x-table.mobile-card>
        @empty
        <x-table.mobile-empty />
        @endforelse
    </x-table.mobile-list>
</x-ui.page-wrapper>
