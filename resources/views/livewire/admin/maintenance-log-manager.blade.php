<div class="space-y-6">
    <x-ui.flash />
<x-ui.toolbar :count="count($logs)" label="Всього записів ТО" buttonLabel="Додати запис ТО" />

    @if($isOpen)
    <x-ui.modal title="{{ $logId ? 'Редагувати' : 'Додати' }} запис обслуговування" maxWidth="md">
            <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Обладнання (ПК / Пристрій)</label>
                    <select wire:model="equipment_id" class="w-full px-4 py-2.5 bg-surface-900/60 border border-white/10 rounded-xl text-white text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                        <option value="">Оберіть обладнання...</option>
                        @foreach($equipmentList as $eq)
                            <option value="{{ $eq->id }}">{{ $eq->inventory_number }} - {{ $eq->accounting_name }}</option>
                        @endforeach
                    </select>
                    @error('equipment_id') <span class="text-xs text-red-400 mt-1">{{ $message }}</span>@enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Тип обслуговування</label>
                        <select wire:model="action_type_id" class="w-full px-4 py-2.5 bg-surface-900/60 border border-white/10 rounded-xl text-white text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                            <option value="">Оберіть тип робіт...</option>
                            @foreach($typesList as $t)
                                <option value="{{ $t->id }}">{{ $t->type_name }}</option>
                            @endforeach
                        </select>
                        @error('action_type_id') <span class="text-xs text-red-400 mt-1">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Вартість (грн)</label>
                        <input type="number" step="0.01" wire:model="cost" class="w-full px-4 py-2.5 bg-surface-900/60 border border-white/10 rounded-xl text-white text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                        @error('cost') <span class="text-xs text-red-400 mt-1">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Дата проведення робіт</label>
                    <input type="date" wire:model="action_date" class="w-full px-4 py-2.5 bg-surface-900/60 border border-white/10 rounded-xl text-white text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                    @error('action_date') <span class="text-xs text-red-400 mt-1">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Детальний опис проведених робіт</label>
                    <textarea wire:model="description" rows="3" class="w-full px-4 py-2.5 bg-surface-900/60 border border-white/10 rounded-xl text-white text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors" placeholder="Опишіть які саме роботи було виконано..."></textarea>
                    @error('description') <span class="text-xs text-red-400 mt-1">{{ $message }}</span>@enderror
                </div>
        </x-ui.modal>
    @endif

    {{-- Desktop --}}
    <x-table.wrapper>
            <x-slot name="headers">
                    <x-table.th align="left" width="28">Дата</x-table.th>
                    <x-table.th align="left">Обладнання (Інв. №)</x-table.th>
                    <x-table.th align="left">Тип роботи</x-table.th>
                    <x-table.th align="left">Опис</x-table.th>
                    <x-table.th align="left" width="28">Вартість</x-table.th>
                    <x-table.th align="right" width="32">Дії</x-table.th>
                </x-slot>
            <tbody class="divide-y divide-white/5 text-sm">
                @forelse($logs as $l)
                <tr class="hover:bg-white/[0.02] transition-colors">
                    <x-table.td align="left" class="text-gray-400 whitespace-nowrap">{{ $l->action_date }}</x-table.td>
                    <x-table.td align="left" primary class="text-white font-medium">
                        {{ $l->equipment->inventory_number ?? '-' }}
                        <span class="block text-[10px] text-gray-500">{{ $l->equipment->accounting_name ?? '' }}</span>
                    </x-table.td>
                    <x-table.td align="left" class="text-brand-400 font-medium">{{ $l->maintenanceType->type_name ?? '-' }}</x-table.td>
                    <td class="px-5 py-3 text-gray-300 max-w-xs truncate" title="{{ $l->description }}">{{ $l->description }}</x-table.td>
                    <x-table.td align="left" class="text-emerald-400 font-semibold">{{ number_format($l->cost, 2, '.', ' ') }} грн</x-table.td>
                    <x-table.td align="right">
                        <x-ui.action-buttons id="{{ $l->id }}" />
                    </x-table.td>
                </tr>
                @empty
                <tr><x-table.td colspan="6" class="px-5 py-10 text-center text-gray-600">Немає записів</x-table.td></tr>
                @endforelse
            </tbody>
        </x-table.wrapper>

    {{-- Mobile --}}
    <x-table.mobile-list>
        @forelse($logs as $l)
        <div class="bg-surface-800/50 border border-white/5 rounded-xl p-4 space-y-2">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-xs text-gray-500">Дата: {{ $l->action_date }}</span>
                    <p class="text-sm text-white font-medium">Обладнання: {{ $l->equipment->inventory_number ?? '-' }}</p>
                    <p class="text-xs text-brand-400 font-medium">Робота: {{ $l->maintenanceType->type_name ?? '-' }}</p>
                </div>
                <div class="flex gap-1 shrink-0">
                    <button wire:click="edit({{ $l->id }})" class="p-2 rounded-lg text-gray-500 hover:text-brand-400 hover:bg-brand-500/10 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                    <button wire:click="delete({{ $l->id }})" class="p-2 rounded-lg text-gray-500 hover:text-red-400 hover:bg-red-500/10 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                </div>
            </div>
            
            <p class="text-xs text-gray-300 bg-surface-900/60 p-2.5 rounded-lg border border-white/5">
                {{ $l->description }}
            </p>

            <div class="flex items-center justify-between border-t border-white/5 pt-2 text-xs">
                <span class="text-gray-500 font-semibold">Вартість:</span>
                <span class="text-emerald-400 font-bold">{{ number_format($l->cost, 2, '.', ' ') }} грн</span>
            </div>
        </div>
        @empty
        <div class="text-center py-10 text-gray-600 text-sm">Немає записів</div>
        @endforelse
    </x-table.mobile-list>
</div>
