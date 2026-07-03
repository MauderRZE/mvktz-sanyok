<div>
<x-ui.page-wrapper>
    <x-ui.flash />
    <x-ui.toolbar :count="count($organizations)" label="Всього організацій" buttonLabel="Додати організацію" />

    @if($isOpen)
        <x-ui.modal title="{{ $orgId ? 'Редагувати' : 'Додати' }} організацію" maxWidth="md">
            <div class="space-y-4">
                <x-form.input label="Назва організації" model="org_name" type="text" />
                <x-form.input label="Тип організації" model="org_type" type="text" />
            </div>
        </x-ui.modal>
    @endif

    {{-- Desktop --}}
    <x-table.wrapper>
        <x-slot name="headers">
            <x-table.th align="left" width="20">ID</x-table.th>
            <x-table.th align="left">Назва організації</x-table.th>
            <x-table.th align="left">Тип організації</x-table.th>
            <x-table.th align="right" width="32">Дії</x-table.th>
        </x-slot>
    
        @forelse($organizations as $org)
            <x-table.tr>
                <x-table.td align="left" muted>#{{ $org->id }}</x-table.td>
                <x-table.td align="left" primary>{{ $org->org_name }}</x-table.td>
                <x-table.td align="left">{{ $org->org_type }}</x-table.td>
                <x-table.td align="right">
                    <x-ui.action-buttons id="{{ $org->id }}" />
                </x-table.td>
            </x-table.tr>
        @empty
            <x-table.empty colspan="4" />
        @endforelse
    </x-table.wrapper>

    {{-- Mobile --}}
    <x-table.mobile-list>
        @forelse($organizations as $org)
            <x-table.mobile-card>
                <x-ui.text-block title="{{ $org->org_name }}" subtitle="Тип: {{ $org->org_type }} | ID: {{ $org->id }}" />
                <x-ui.action-buttons id="{{ $org->id }}" />
            </x-table.mobile-card>
        @empty
            <x-table.mobile-empty />
        @endforelse
    </x-table.mobile-list>
</x-ui.page-wrapper>
</div>
