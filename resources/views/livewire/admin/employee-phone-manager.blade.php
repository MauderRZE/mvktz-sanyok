<div>
<x-ui.page-wrapper>
    <x-ui.flash />
    <x-ui.toolbar :count="count($phones)" label="Всього телефонів" buttonLabel="Додати телефон" />

    {{-- Filters Bar --}}
    <x-ui.card class="p-4 mb-4 space-y-4">
        <div class="flex flex-col lg:flex-row gap-4 items-center">
            <div class="flex-1 w-full flex gap-2">
                <div class="flex-1">
                    <x-form.search wire:model.live.debounce.300ms="search" placeholder="Пошук за номером телефону, співробітником..." />
                </div>
                @if($search !== '' || !empty($filterPhoneType) || !empty($filterEmployee))
                    <button wire:click="resetFilters" class="px-4 py-2 bg-white/5 hover:bg-white/10 text-xs text-gray-400 hover:text-white rounded-xl border border-white/10 transition-colors shrink-0 flex items-center gap-1.5" title="Скинути всі фільтри">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        <span>Скинути</span>
                    </button>
                @endif
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 w-full lg:w-auto">
                <x-form.multi-select label="Тип телефону" :selectedCount="count($filterPhoneType)">
                    @foreach(['Робочий', 'Особистий', 'Додатковий'] as $type)
                        <label class="flex items-center gap-2 text-xs text-gray-300 hover:text-white cursor-pointer py-1">
                            <input type="checkbox" value="{{ $type }}" wire:model.live="filterPhoneType" class="rounded border-white/10 bg-surface-900 text-brand-500 focus:ring-0 focus:ring-offset-0">
                            <span>{{ $type }}</span>
                        </label>
                    @endforeach
                </x-form.multi-select>

                <x-form.multi-select label="Співробітник" :selectedCount="count($filterEmployee)">
                    @foreach($employees as $emp)
                        <label class="flex items-center gap-2 text-xs text-gray-300 hover:text-white cursor-pointer py-1">
                            <input type="checkbox" value="{{ $emp->id }}" wire:model.live="filterEmployee" class="rounded border-white/10 bg-surface-900 text-brand-500 focus:ring-0 focus:ring-offset-0">
                            <span>{{ $emp->last_name }} {{ $emp->first_name }}</span>
                        </label>
                    @endforeach
                </x-form.multi-select>
            </div>
        </div>
    </x-ui.card>

    @if($isOpen)
    <x-ui.modal title="{{ $form->phoneId ? 'Редагувати' : 'Додати' }} телефон" maxWidth="md">
        <x-form.select label="Співробітник" model="form.employee_id" :options="$employees->mapWithKeys(function($item) {
            return [$item->id => $item->last_name . ' ' . $item->first_name];
        })->toArray()" />
        
        <x-form.input label="Номер телефону" model="form.phone_number" type="text" />
        
        <x-form.select label="Тип телефону" model="form.phone_type" :options="['Робочий' => 'Робочий', 'Особистий' => 'Особистий', 'Додатковий' => 'Додатковий']" />
    </x-ui.modal>
    @endif

    {{-- Desktop --}}
    <x-table.wrapper>
        <x-slot name="headers">
            <x-table.th align="left" width="20">ID</x-table.th>
            <x-table.th align="left">Співробітник</x-table.th>
            <x-table.th align="left">Номер телефону</x-table.th>
            <x-table.th align="left">Тип</x-table.th>
            <x-table.th align="right" width="32">Дії</x-table.th>
        </x-slot>

        @forelse($phones as $phone)
        <x-table.tr>
            <x-table.td align="left" muted>#{{ $phone->id }}</x-table.td>
            <x-table.td align="left" primary>{{ $phone->employee->last_name ?? 'N/A' }} {{ $phone->employee->first_name ?? '' }}</x-table.td>
            <x-table.td align="left" primary>{{ $phone->phone_number }}</x-table.td>
            <x-table.td align="left">{{ $phone->phone_type }}</x-table.td>
            <x-table.td align="right">
                <x-ui.action-buttons id="{{ $phone->id }}" />
            </x-table.td>
        </x-table.tr>
        @empty
        <x-table.empty colspan="5" />
        @endforelse
    </x-table.wrapper>

    {{-- Mobile --}}
    <x-table.mobile-list>
        @forelse($phones as $phone)
        <x-table.mobile-card>
            <x-ui.text-block 
                title="{{ $phone->phone_number }}" 
                subtitle="{{ $phone->employee->last_name ?? 'N/A' }} ({{ $phone->phone_type }})" 
            />
            <x-ui.action-buttons id="{{ $phone->id }}" />
        </x-table.mobile-card>
        @empty
        <x-table.mobile-empty />
        @endforelse
    </x-table.mobile-list>
</x-ui.page-wrapper>
</div>
