<div class="space-y-6">
    <x-ui.flash />
<x-ui.toolbar :count="count($employees)" label="Всього" buttonLabel="Додати" />

    @if($isOpen)
    <x-ui.modal title="{{ $employeeId ? 'Редагувати' : 'Додати' }} співробітника" maxWidth="lg">
            <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Прізвище</label>
                    <input type="text" wire:model="last_name" class="w-full px-4 py-2.5 bg-surface-900/60 border border-white/10 rounded-xl text-white text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                    @error('last_name') <span class="text-xs text-red-400 mt-1">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Ім'я</label>
                    <input type="text" wire:model="first_name" class="w-full px-4 py-2.5 bg-surface-900/60 border border-white/10 rounded-xl text-white text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                    @error('first_name') <span class="text-xs text-red-400 mt-1">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">По-батькові</label>
                    <input type="text" wire:model="middle_name" class="w-full px-4 py-2.5 bg-surface-900/60 border border-white/10 rounded-xl text-white text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Посада</label>
                        <input type="text" wire:model="position" class="w-full px-4 py-2.5 bg-surface-900/60 border border-white/10 rounded-xl text-white text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Відділ</label>
                        <input type="text" wire:model="department" class="w-full px-4 py-2.5 bg-surface-900/60 border border-white/10 rounded-xl text-white text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
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
            <tbody class="divide-y divide-white/5">
                @forelse($employees as $employee)
                <tr class="hover:bg-white/[0.02] transition-colors">
                    <x-table.td align="left" class="text-gray-500">#{{ $employee->id }}</x-table.td>
                    <x-table.td align="left">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-brand-400 to-brand-600 flex items-center justify-center text-xs font-bold text-white shrink-0">{{ strtoupper(substr($employee->last_name, 0, 1)) }}</div>
                            <div>
                                <p class="text-sm text-white font-medium">{{ $employee->last_name }} {{ $employee->first_name }}</p>
                                <p class="text-xs text-gray-500">{{ $employee->middle_name }}</p>
                            </div>
                        </div>
                    </x-table.td>
                    <x-table.td align="left" class="text-gray-300">{{ $employee->position ?? '—' }}</x-table.td>
                    <x-table.td align="left"><span class="px-2.5 py-1 rounded-lg bg-white/5 text-gray-400 text-xs font-medium">{{ $employee->department ?? '—' }}</span></x-table.td>
                    <x-table.td align="right">
                        <x-ui.action-buttons id="{{ $employee->id }}" />
                    </x-table.td>
                </tr>
                @empty
                <tr><x-table.td colspan="5" class="px-5 py-10 text-center text-gray-600 text-sm">Немає записів</x-table.td></tr>
                @endforelse
            </tbody>
        </x-table.wrapper>

    {{-- Mobile Cards --}}
    <x-table.mobile-list>
        @forelse($employees as $employee)
        <div class="bg-surface-800/50 border border-white/5 rounded-xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-brand-400 to-brand-600 flex items-center justify-center text-sm font-bold text-white shrink-0">{{ strtoupper(substr($employee->last_name, 0, 1)) }}</div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-white font-medium truncate">{{ $employee->last_name }} {{ $employee->first_name }} {{ $employee->middle_name }}</p>
                    <p class="text-xs text-gray-500">{{ $employee->position ?? '—' }} · {{ $employee->department ?? '—' }}</p>
                </div>
                <div class="flex gap-1 shrink-0">
                    <button wire:click="edit({{ $employee->id }})" class="p-2 rounded-lg text-gray-500 hover:text-brand-400 hover:bg-brand-500/10 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                    <button wire:click="delete({{ $employee->id }})" class="p-2 rounded-lg text-gray-500 hover:text-red-400 hover:bg-red-500/10 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-10 text-gray-600 text-sm">Немає записів</div>
        @endforelse
    </x-table.mobile-list>
</div>
