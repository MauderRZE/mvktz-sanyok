<div>
<x-ui.page-wrapper>
    <x-ui.flash />
    <x-ui.toolbar :count="$employees->total()" label="Всього" buttonLabel="Додати" />

    {{-- Filters Bar --}}
    <x-ui.card class="p-4 space-y-4">
        <div class="flex flex-col md:flex-row gap-4 items-center">
            <div class="flex-1 w-full flex gap-2">
                <div class="flex-1 relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Пошук за ПІБ чи посадою..." class="w-full bg-surface-900 border border-white/10 rounded-xl pl-10 pr-4 py-2 text-sm text-white focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                </div>
                @if($search !== '' || !empty($filterDepartment))
                    <button wire:click="resetFilters" class="px-4 py-2 bg-white/5 hover:bg-white/10 text-xs text-gray-400 hover:text-white rounded-xl border border-white/10 transition-colors shrink-0 flex items-center gap-1.5" title="Скинути всі фільтри">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        <span class="hidden sm:inline">Скинути</span>
                    </button>
                @endif
            </div>
            <div class="grid grid-cols-1 gap-3 w-full md:w-64">
                <x-form.multi-select label="Відділи" :selectedCount="count($filterDepartment)">
                    @foreach($departmentsList as $dep)
                        <label class="flex items-center gap-2 text-xs text-gray-300 hover:text-white cursor-pointer py-1">
                            <input type="checkbox" value="{{ $dep->id }}" wire:model.live="filterDepartment" class="rounded border-white/10 bg-surface-900 text-brand-500 focus:ring-0 focus:ring-offset-0">
                            <span>{{ $dep->name }}</span>
                        </label>
                    @endforeach
                </x-form.multi-select>
            </div>
        </div>
    </x-ui.card>

    @if($isOpen)
    <div wire:key="employee-modal-wrapper">
    <x-ui.modal wire:key="employee-modal" title="{{ $employeeId ? 'Редагувати' : 'Додати' }} співробітника" maxWidth="lg">
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
                        <x-form.select label="Відділ" model="department_id">
                            <option value="">Оберіть відділ...</option>
                            @foreach($departmentsList as $dep)
                                <option value="{{ $dep->id }}">{{ $dep->name }}</option>
                            @endforeach
                        </x-form.select>
                    </div>
                </div>
        </x-ui.modal>
    </div>
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
                    <x-table.td align="left"><span class="px-2.5 py-1 rounded-lg bg-white/5 text-gray-400 text-xs font-medium">{{ $employee->department->name ?? '—' }}</span></x-table.td>
                    <x-table.td align="right">
                        <x-ui.action-buttons id="{{ $employee->id }}" />
                    </x-table.td>
                </x-table.tr>
                @empty
                <x-table.empty colspan="5" />
                @endforelse
            
        </x-table.wrapper>
        
        <div class="hidden md:block">
            {{ $employees->links() }}
        </div>

    {{-- Mobile Cards --}}
    <x-table.mobile-list>
        @forelse($employees as $employee)
        <x-table.mobile-card layout="gap-3">
            <x-ui.avatar-cell class="flex-1" size="lg" :name="$employee->last_name" :title="$employee->last_name . ' ' . $employee->first_name . ' ' . $employee->middle_name" :subtitle="($employee->position ?? '—') . ' · ' . ($employee->department->name ?? '—')" />
            <x-ui.action-buttons id="{{ $employee->id }}" />
        </x-table.mobile-card>
        @empty
        <x-table.mobile-empty />
        @endforelse
    </x-table.mobile-list>
    
    <div class="md:hidden">
        {{ $employees->links() }}
    </div>

</x-ui.page-wrapper>
</div>
