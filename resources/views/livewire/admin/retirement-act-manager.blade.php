<div>
<x-ui.page-wrapper>
    <x-ui.flash />
    <x-ui.toolbar :count="count($acts)" label="Всього актів списання" buttonLabel="Додати акт" />

    {{-- Filters Bar --}}
    <x-ui.card class="p-4 mb-4">
        <div class="flex gap-2">
            <div class="flex-1">
                <x-form.search wire:model.live.debounce.300ms="search" placeholder="Пошук за номером акту, причиною..." />
            </div>
            @if($search !== '')
                <button wire:click="resetFilters" class="px-4 py-2 bg-white/5 hover:bg-white/10 text-xs text-gray-400 hover:text-white rounded-xl border border-white/10 transition-colors shrink-0 flex items-center gap-1.5" title="Скинути пошук">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    <span>Скинути</span>
                </button>
            @endif
        </div>
    </x-ui.card>

    @if($isOpen)
        <x-ui.modal title="{{ $form->actId ? 'Редагувати' : 'Додати' }} акт списання" maxWidth="md">
            <div class="space-y-4">
                <x-form.input label="Номер акту" model="form.act_number" type="text" />
                <x-form.input label="Дата акту" model="form.act_date" type="date" />
                <x-form.textarea label="Причина" model="form.reason" rows="2" />
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
</div>
