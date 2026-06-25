<div class="space-y-6">
    <x-ui.flash />
<x-ui.toolbar :count="count($movements)" label="Всього переміщень" buttonLabel="Перемістити техніку" />

    @if($isOpen)
    <x-ui.modal title="{{ $movementId ? 'Редагувати' : 'Зареєструвати' }} переміщення" maxWidth="md">
            <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Обладнання (Інв. №)</label>
                    <select wire:model="equipment_id" class="w-full px-4 py-2.5 bg-surface-900/60 border border-white/10 rounded-xl text-white text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                        <option value="">Оберіть обладнання...</option>
                        @foreach($equipmentList as $eq)
                            <option value="{{ $eq->id }}">{{ $eq->inventory_number }} - {{ $eq->accounting_name }}</option>
                        @endforeach
                    </select>
                    @error('equipment_id') <span class="text-xs text-red-400 mt-1">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Куди переміщено (Кабінет / Локація)</label>
                    <select wire:model="location_id" class="w-full px-4 py-2.5 bg-surface-900/60 border border-white/10 rounded-xl text-white text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                        <option value="">Оберіть локацію...</option>
                        @foreach($locationsList as $loc)
                            <option value="{{ $loc->id }}">Кабінет {{ $loc->room_number }}</option>
                        @endforeach
                    </select>
                    @error('location_id') <span class="text-xs text-red-400 mt-1">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Відповідальний співробітник</label>
                    <select wire:model="employee_id" class="w-full px-4 py-2.5 bg-surface-900/60 border border-white/10 rounded-xl text-white text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                        <option value="">Без відповідального (на склад)...</option>
                        @foreach($employeesList as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->fullName }} ({{ $emp->position }})</option>
                        @endforeach
                    </select>
                    @error('employee_id') <span class="text-xs text-red-400 mt-1">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Дата переміщення</label>
                    <input type="date" wire:model="move_date" class="w-full px-4 py-2.5 bg-surface-900/60 border border-white/10 rounded-xl text-white text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                    @error('move_date') <span class="text-xs text-red-400 mt-1">{{ $message }}</span>@enderror
                </div>
        </x-ui.modal>
    @endif

    {{-- Desktop --}}
    <x-table.wrapper>
            <x-slot name="headers">
                    <x-table.th align="left" width="28">Дата</x-table.th>
                    <x-table.th align="left">Обладнання (Інв. №)</x-table.th>
                    <x-table.th align="left">Нове розташування</x-table.th>
                    <x-table.th align="left">Матеріально відповідальний</x-table.th>
                    <x-table.th align="right" width="32">Дії</x-table.th>
                </x-slot>
            <tbody class="divide-y divide-white/5">
                @forelse($movements as $m)
                <tr class="hover:bg-white/[0.02] transition-colors text-sm">
                    <x-table.td align="left" class="text-gray-400 whitespace-nowrap">{{ $m->move_date }}</x-table.td>
                    <x-table.td align="left" primary class="text-white font-medium">
                        {{ $m->equipment->inventory_number ?? '-' }}
                        <span class="block text-[10px] text-gray-500">{{ $m->equipment->accounting_name ?? '' }}</span>
                    </x-table.td>
                    <x-table.td align="left" class="text-gray-300">Кабінет {{ $m->location->room_number ?? '-' }}</x-table.td>
                    <x-table.td align="left" class="text-gray-300">{{ $m->employee->fullName ?? 'На складі (без відповідального)' }}</x-table.td>
                    <x-table.td align="right">
                        <x-ui.action-buttons id="{{ $m->id }}" />
                    </x-table.td>
                </tr>
                @empty
                <tr><x-table.td colspan="5" class="px-5 py-10 text-center text-gray-600">Немає записів</x-table.td></tr>
                @endforelse
            </tbody>
        </x-table.wrapper>

    {{-- Mobile --}}
    <x-table.mobile-list>
        @forelse($movements as $m)
        <div class="bg-surface-800/50 border border-white/5 rounded-xl p-4 flex flex-col gap-2">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-xs text-gray-500">Дата: {{ $m->move_date }}</span>
                    <p class="text-sm text-white font-medium">Обладнання: {{ $m->equipment->inventory_number ?? '-' }}</p>
                    <p class="text-xs text-brand-400 font-medium">Куди: Кабінет {{ $m->location->room_number ?? '-' }}</p>
                </div>
                <div class="flex gap-1 shrink-0">
                    <button wire:click="edit({{ $m->id }})" class="p-2 rounded-lg text-gray-500 hover:text-brand-400 hover:bg-brand-500/10 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                    <button wire:click="delete({{ $m->id }})" class="p-2 rounded-lg text-gray-500 hover:text-red-400 hover:bg-red-500/10 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                </div>
            </div>
            <div class="text-xs text-gray-400 border-t border-white/5 pt-2">
                Відповідальний: {{ $m->employee->fullName ?? 'На складі (без відповідального)' }}
            </div>
        </div>
        @empty
        <div class="text-center py-10 text-gray-600 text-sm">Немає записів</div>
        @endforelse
    </x-table.mobile-list>
</div>
