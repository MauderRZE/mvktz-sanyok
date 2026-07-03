<div>
<x-ui.page-wrapper>
    <x-ui.flash />
    <x-ui.toolbar :count="count($phones)" label="Всього телефонів" buttonLabel="Додати телефон" />

    @if($isOpen)
    <x-ui.modal title="{{ $phoneId ? 'Редагувати' : 'Додати' }} телефон" maxWidth="md">
        <x-form.select label="Співробітник" model="employee_id" :options="$employees->mapWithKeys(function($item) {
            return [$item->id => $item->last_name . ' ' . $item->first_name];
        })->toArray()" />
        
        <x-form.input label="Номер телефону" model="phone_number" type="text" />
        
        <x-form.select label="Тип телефону" model="phone_type" :options="['Робочий' => 'Робочий', 'Особистий' => 'Особистий', 'Додатковий' => 'Додатковий']" />
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
