<x-ui.page-wrapper>
    <x-ui.flash />
    <x-ui.toolbar :count="count($acts)" label="Всього актів списання" buttonLabel="Додати акт" />

    @if($isOpen)
        <x-ui.modal title="{{ $actId ? 'Редагувати' : 'Додати' }} акт списання" maxWidth="md">
            <div class="space-y-4">
                <x-form.input label="Номер акту" model="act_number" type="text" />
                <x-form.input label="Дата акту" model="act_date" type="date" />
                <x-form.textarea label="Причина" model="reason" rows="2" />
            </div>
        </x-ui.modal>
    @endif

    {{-- Desktop --}}
    <x-table.wrapper>
        <x-slot name="headers">
            <x-table.th align="left" width="20">ID</x-table.th>
            <x-table.th align="left">Номер акту</x-table.th>
            <x-table.th align="left">Дата акту</x-table.th>
            <x-table.th align="left">Причина</x-table.th>
            <x-table.th align="right" width="32">Дії</x-table.th>
        </x-slot>
    
        @forelse($acts as $act)
            <x-table.tr>
                <x-table.td align="left" muted>#{{ $act->id }}</x-table.td>
                <x-table.td align="left" primary>{{ $act->act_number }}</x-table.td>
                <x-table.td align="left">{{ $act->act_date }}</x-table.td>
                <x-table.td align="left" class="max-w-[200px] truncate" title="{{ $act->reason }}">{{ $act->reason ?? '—' }}</x-table.td>
                <x-table.td align="right">
                    <x-ui.action-buttons id="{{ $act->id }}" />
                </x-table.td>
            </x-table.tr>
        @empty
            <x-table.empty colspan="5" />
        @endforelse
    </x-table.wrapper>

    {{-- Mobile --}}
    <x-table.mobile-list>
        @forelse($acts as $act)
            <x-table.mobile-card>
                <x-ui.text-block title="Акт №{{ $act->act_number }}" subtitle="Дата: {{ $act->act_date }} | ID: {{ $act->id }}" />
                <x-ui.action-buttons id="{{ $act->id }}" />
            </x-table.mobile-card>
        @empty
            <x-table.mobile-empty />
        @endforelse
    </x-table.mobile-list>
</x-ui.page-wrapper>
