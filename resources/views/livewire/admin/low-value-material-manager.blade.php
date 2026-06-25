<div class="space-y-6">
    <x-ui.flash />
<x-ui.toolbar :count="count($materials)" label="Всього позицій" buttonLabel="Додати МШП" />

    @if($isOpen)
    <x-ui.modal title="{{ $materialId ? 'Редагувати' : 'Додати' }} МШП" maxWidth="lg">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Назва матеріалу (базовий)</label>
                        <select wire:model="base_material_id" class="w-full px-4 py-2.5 bg-surface-900/60 border border-white/10 rounded-xl text-white text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                            <option value="">Оберіть матеріал...</option>
                            @foreach($baseMaterialsList as $bm)
                                <option value="{{ $bm->id }}">{{ $bm->material_name }}</option>
                            @endforeach
                        </select>
                        @error('base_material_id') <span class="text-xs text-red-400 mt-1">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Кількість</label>
                        <input type="number" wire:model="quantity" class="w-full px-4 py-2.5 bg-surface-900/60 border border-white/10 rounded-xl text-white text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                        @error('quantity') <span class="text-xs text-red-400 mt-1">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Прив'язане обладнання (якщо встановлено)</label>
                        <select wire:model="equipment_id" class="w-full px-4 py-2.5 bg-surface-900/60 border border-white/10 rounded-xl text-white text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                            <option value="">Не встановлено (на складі)...</option>
                            @foreach($equipmentList as $eq)
                                <option value="{{ $eq->id }}">{{ $eq->inventory_number }} - {{ $eq->accounting_name }}</option>
                            @endforeach
                        </select>
                        @error('equipment_id') <span class="text-xs text-red-400 mt-1">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Договір закупівлі</label>
                        <select wire:model="contract_id" class="w-full px-4 py-2.5 bg-surface-900/60 border border-white/10 rounded-xl text-white text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                            <option value="">Оберіть договір...</option>
                            @foreach($contractsList as $c)
                                <option value="{{ $c->id }}">Договір №{{ $c->contract_number }} ({{ $c->contract_date }})</option>
                            @endforeach
                        </select>
                        @error('contract_id') <span class="text-xs text-red-400 mt-1">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Серійний номер</label>
                        <input type="text" wire:model="serial_number" class="w-full px-4 py-2.5 bg-surface-900/60 border border-white/10 rounded-xl text-white text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                        @error('serial_number') <span class="text-xs text-red-400 mt-1">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Дата закупівлі</label>
                        <input type="date" wire:model="purchase_date" class="w-full px-4 py-2.5 bg-surface-900/60 border border-white/10 rounded-xl text-white text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                        @error('purchase_date') <span class="text-xs text-red-400 mt-1">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Дата встановлення</label>
                        <input type="date" wire:model="installation_date" class="w-full px-4 py-2.5 bg-surface-900/60 border border-white/10 rounded-xl text-white text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                        @error('installation_date') <span class="text-xs text-red-400 mt-1">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Примітки</label>
                    <textarea wire:model="notes" rows="2" class="w-full px-4 py-2.5 bg-surface-900/60 border border-white/10 rounded-xl text-white text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors"></textarea>
                    @error('notes') <span class="text-xs text-red-400 mt-1">{{ $message }}</span>@enderror
                </div>
        </x-ui.modal>
    @endif

    {{-- Desktop --}}
    <x-table.wrapper>
            <x-slot name="headers">
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Матеріал (МШП)</x-table.th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Серійний</x-table.th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">К-сть</x-table.th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Встановлено на</x-table.th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Договір</x-table.th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Дати</x-table.th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider w-24">Дії</x-table.th>
                </x-slot>
            <tbody class="divide-y divide-white/5 text-sm">
                @forelse($materials as $m)
                <tr class="hover:bg-white/[0.02] transition-colors">
                    <x-table.td align="left" primary class="text-white font-medium">
                        {{ $m->material->material_name ?? '-' }}
                        @if($m->notes)
                            <span class="block text-[10px] text-gray-500 italic">{{ $m->notes }}</span>
                        @endif
                    </x-table.td>
                    <x-table.td align="left" class="text-gray-400 font-mono text-xs">{{ $m->serial_number ?? '-' }}</x-table.td>
                    <x-table.td align="left" class="text-gray-300 font-semibold">{{ $m->quantity }} шт.</x-table.td>
                    <x-table.td align="left" class="text-gray-300">
                        @if($m->equipment)
                            <span class="text-brand-400">{{ $m->equipment->inventory_number }}</span>
                            <span class="block text-[10px] text-gray-500">{{ $m->equipment->accounting_name }}</span>
                        @else
                            <span class="text-gray-500 italic">На складі</span>
                        @endif
                    </x-table.td>
                    <x-table.td align="left" class="text-gray-400 text-xs">
                        @if($m->contract)
                            Договір №{{ $m->contract->contract_number }}
                            <span class="block text-[10px] text-gray-500">{{ $m->contract->contract_date }}</span>
                        @else
                            -
                        @endif
                    </x-table.td>
                    <x-table.td align="left" class="text-xs text-gray-400 whitespace-nowrap">
                        @if($m->purchase_date) <div>Придбано: {{ $m->purchase_date }}</div> @endif
                        @if($m->installation_date) <div>Встановлено: {{ $m->installation_date }}</div> @endif
                    </x-table.td>
                    <x-table.td align="right">
                        <x-ui.action-buttons id="{{ $m->id }}" />
                    </x-table.td>
                </tr>
                @empty
                <tr><x-table.td colspan="7" class="px-4 py-10 text-center text-gray-600">Немає записів</x-table.td></tr>
                @endforelse
            </tbody>
        </x-table.wrapper>

    {{-- Mobile --}}
    <x-table.mobile-list>
        @forelse($materials as $m)
        <div class="bg-surface-800/50 border border-white/5 rounded-xl p-4 space-y-2">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-xs text-gray-500">Кількість: {{ $m->quantity }} шт.</span>
                    <p class="text-sm text-white font-medium">{{ $m->material->material_name ?? '-' }}</p>
                    <p class="text-xs text-gray-400">
                        Розташування: 
                        @if($m->equipment)
                            Встановлено на {{ $m->equipment->inventory_number }}
                        @else
                            На складі
                        @endif
                    </p>
                </div>
                <div class="flex gap-1 shrink-0">
                    <button wire:click="edit({{ $m->id }})" class="p-2 rounded-lg text-gray-500 hover:text-brand-400 hover:bg-brand-500/10 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                    <button wire:click="delete({{ $m->id }})" class="p-2 rounded-lg text-gray-500 hover:text-red-400 hover:bg-red-500/10 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                </div>
            </div>
            @if($m->notes)
                <p class="text-xs text-gray-400 italic bg-surface-900/60 p-2 rounded-lg border border-white/5">{{ $m->notes }}</p>
            @endif
        </div>
        @empty
        <div class="text-center py-10 text-gray-600 text-sm">Немає записів</div>
        @endforelse
    </x-table.mobile-list>
</div>
