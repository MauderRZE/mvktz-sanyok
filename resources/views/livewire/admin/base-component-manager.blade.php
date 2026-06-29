<x-ui.page-wrapper>
    <x-ui.flash />
<x-ui.toolbar :count="count($components)" label="Всього" buttonLabel="Додати" />

    @if($isOpen)
    <x-ui.modal title="{{ $componentId ? 'Редагувати' : 'Додати' }} компонент" maxWidth="md">
            <x-form.input label="Назва компонента" model="component_name" type="text" />
        </x-ui.modal>
    @endif

    {{-- Desktop --}}
    <x-table.wrapper>
            <x-slot name="headers">
                    <x-table.th align="left" width="20">ID</x-table.th>
                    <x-table.th align="left">Назва</x-table.th>
                    <x-table.th align="right" width="32">Дії</x-table.th>
                </x-slot>
            
                @forelse($components as $comp)
                <x-table.tr>
                    <x-table.td align="left" muted>#{{ $comp->id }}</x-table.td>
                    <x-table.td align="left" primary>{{ $comp->component_name }}</x-table.td>
                    <x-table.td align="right">
                        <x-ui.action-buttons id="{{ $comp->id }}" />
                    </x-table.td>
                </x-table.tr>
                @empty
                <x-table.empty colspan="3" />
                @endforelse
            
        </x-table.wrapper>

    {{-- Mobile --}}
    <x-table.mobile-list>
        @forelse($components as $comp)
        <x-table.mobile-card>
            <x-ui.text-block title="{{ $comp->component_name }}" subtitle="ID: {{ $comp->id }}" />
            <x-ui.action-buttons id="{{ $comp->id }}" />
        </x-table.mobile-card>
        @empty
        <x-table.mobile-empty />
        @endforelse
    </x-table.mobile-list>
</x-ui.page-wrapper>
