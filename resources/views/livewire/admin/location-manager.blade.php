<x-ui.page-wrapper>
    <x-ui.flash />
<x-ui.toolbar :count="count($locations)" label="Всього" buttonLabel="Додати" />

    @if($isOpen)
    <x-ui.modal title="{{ $locationId ? 'Редагувати' : 'Додати' }} кабінет/локацію" maxWidth="md">
            <x-form.input label="Номер кабінету / Назва локації" model="room_number" type="text" />
        </x-ui.modal>
    @endif

    {{-- Desktop --}}
    <x-table.wrapper>
            <x-slot name="headers">
                    <x-table.th align="left" width="20">ID</x-table.th>
                    <x-table.th align="left">Кабінет / Локація</x-table.th>
                    <x-table.th align="right" width="32">Дії</x-table.th>
                </x-slot>
            
                @forelse($locations as $loc)
                <x-table.tr>
                    <x-table.td align="left" muted>#{{ $loc->id }}</x-table.td>
                    <x-table.td align="left" primary>{{ $loc->room_number }}</x-table.td>
                    <x-table.td align="right">
                        <x-ui.action-buttons id="{{ $loc->id }}" />
                    </x-table.td>
                </x-table.tr>
                @empty
                <x-table.empty colspan="3" />
                @endforelse
            
        </x-table.wrapper>

    {{-- Mobile --}}
    <x-table.mobile-list>
        @forelse($locations as $loc)
        <x-table.mobile-card>
            <x-ui.text-block title="{{ $loc->room_number }}" subtitle="ID: {{ $loc->id }}" />
            <x-ui.action-buttons id="{{ $loc->id }}" />
        </x-table.mobile-card>
        @empty
        <x-table.mobile-empty />
        @endforelse
    </x-table.mobile-list>
</x-ui.page-wrapper>
