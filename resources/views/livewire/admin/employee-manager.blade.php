<div>
<x-ui.page-wrapper>
    <x-ui.flash />
<x-ui.toolbar :count="count($employees)" label="Всього" buttonLabel="Додати" />

    @if($isOpen)
    <x-ui.modal title="{{ $employeeId ? 'Редагувати' : 'Додати' }} співробітника" maxWidth="lg">
            <div>
                    <x-form.input label="Прізвище" model="last_name" type="text" />
                </div>
                <div>
                    <x-form.input label="Ім'я" model="first_name" type="text" />
                </div>
                <div>
                    <x-form.input label="По-батькові" model="middle_name" type="text" />
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <x-form.input label="Посада" model="position" type="text" />
                    </div>
                    <div>
                        <x-form.input label="Відділ" model="department" type="text" />
                    </div>
                </div>
        </x-ui.modal>
    @endif

    {{-- Desktop Table --}}
    <x-table.wrapper>
            <x-slot name="headers">
                    <x-table.th align="left">ID</x-table.th>
                    <x-table.th align="left">ПІБ</x-table.th>
                    <x-table.th align="left">Посада</x-table.th>
                    <x-table.th align="left">Відділ</x-table.th>
                    <x-table.th align="right">Дії</x-table.th>
                </x-slot>
            
                @forelse($employees as $employee)
                <x-table.tr>
                    <x-table.td align="left" muted>#{{ $employee->id }}</x-table.td>
                    <x-table.td align="left">
                    <x-ui.avatar-cell :name="$employee->last_name" :title="$employee->last_name . ' ' . $employee->first_name" :subtitle="$employee->middle_name" />
                </x-table.td>
                    <x-table.td align="left">{{ $employee->position ?? '—' }}</x-table.td>
                    <x-table.td align="left"><span class="px-2.5 py-1 rounded-lg bg-white/5 text-gray-400 text-xs font-medium">{{ $employee->department ?? '—' }}</span></x-table.td>
                    <x-table.td align="right">
                        <x-ui.action-buttons id="{{ $employee->id }}" />
                    </x-table.td>
                </x-table.tr>
                @empty
                <x-table.empty colspan="5" />
                @endforelse
            
        </x-table.wrapper>

    {{-- Mobile Cards --}}
    <x-table.mobile-list>
        @forelse($employees as $employee)
        <x-table.mobile-card layout="gap-3">
            <x-ui.avatar-cell class="flex-1" size="lg" :name="$employee->last_name" :title="$employee->last_name . ' ' . $employee->first_name . ' ' . $employee->middle_name" :subtitle="($employee->position ?? '—') . ' · ' . ($employee->department ?? '—')" />
            <x-ui.action-buttons id="{{ $employee->id }}" />
        </x-table.mobile-card>
        @empty
        <x-table.mobile-empty />
        @endforelse
    </x-table.mobile-list>
</x-ui.page-wrapper>
</div>
