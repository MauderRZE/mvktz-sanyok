<x-ui.page-wrapper>
    <x-ui.flash />
    <x-ui.toolbar :count="count($departments)" label="Всього відділів" buttonLabel="Додати відділ" />

    @if($isOpen)
        <x-ui.modal title="{{ $departmentId ? 'Редагувати' : 'Додати' }} відділ" maxWidth="md">
            <x-form.input label="Назва відділу" model="name" type="text" />
        </x-ui.modal>
    @endif

    {{-- Desktop --}}
    <x-table.wrapper>
        <x-slot name="headers">
            <x-table.th align="left" width="20">ID</x-table.th>
            <x-table.th align="left">Назва відділу</x-table.th>
            <x-table.th align="right" width="32">Дії</x-table.th>
        </x-slot>
    
        @forelse($departments as $dept)
            <x-table.tr>
                <x-table.td align="left" muted>#{{ $dept->id }}</x-table.td>
                <x-table.td align="left" primary>{{ $dept->name }}</x-table.td>
                <x-table.td align="right">
                    <x-ui.action-buttons id="{{ $dept->id }}" />
                </x-table.td>
            </x-table.tr>
        @empty
            <x-table.empty colspan="3" />
        @endforelse
    </x-table.wrapper>

    {{-- Mobile --}}
    <x-table.mobile-list>
        @forelse($departments as $dept)
            <x-table.mobile-card>
                <x-ui.text-block title="{{ $dept->name }}" subtitle="ID: {{ $dept->id }}" />
                <x-ui.action-buttons id="{{ $dept->id }}" />
            </x-table.mobile-card>
        @empty
            <x-table.mobile-empty />
        @endforelse
    </x-table.mobile-list>
</x-ui.page-wrapper>
