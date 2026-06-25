<div class="space-y-6">
    <x-ui.flash />
<x-ui.toolbar :count="count($components)" label="Всього" buttonLabel="Додати" />

    @if($isOpen)
    <x-ui.modal title="{{ $componentId ? 'Редагувати' : 'Додати' }} комплектуюче" maxWidth="lg">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
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
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Базовий компонент</label>
                        <select wire:model="component_type_id" class="w-full px-4 py-2.5 bg-surface-900/60 border border-white/10 rounded-xl text-white text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                            <option value="">Оберіть компонент...</option>
                            @foreach($baseComponentsList as $bc)
                                <option value="{{ $bc->id }}">{{ $bc->component_name }}</option>
                            @endforeach
                        </select>
                        @error('component_type_id') <span class="text-xs text-red-400 mt-1">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Виробник / Модель</label>
                        <input type="text" wire:model="brand_model" placeholder="напр. Kingston DDR4 16GB" class="w-full px-4 py-2.5 bg-surface-900/60 border border-white/10 rounded-xl text-white text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                        @error('brand_model') <span class="text-xs text-red-400 mt-1">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Серійний номер</label>
                        <input type="text" wire:model="serial_number" class="w-full px-4 py-2.5 bg-surface-900/60 border border-white/10 rounded-xl text-white text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                        @error('serial_number') <span class="text-xs text-red-400 mt-1">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Модель картриджа (якщо є)</label>
                        <input type="text" wire:model="cartridge_model" class="w-full px-4 py-2.5 bg-surface-900/60 border border-white/10 rounded-xl text-white text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                        @error('cartridge_model') <span class="text-xs text-red-400 mt-1">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Стан роботи</label>
                        <select wire:model="status" class="w-full px-4 py-2.5 bg-surface-900/60 border border-white/10 rounded-xl text-white text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                            <option value="Працює">Працює</option>
                            <option value="Знято">Знято</option>
                            <option value="Зламано">Зламано</option>
                            <option value="В ремонті">В ремонті</option>
                        </select>
                        @error('status') <span class="text-xs text-red-400 mt-1">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="border-t border-white/5 pt-4">
                    <label class="flex items-center gap-2 text-sm text-gray-300 mb-4 cursor-pointer">
                        <input type="checkbox" wire:model="has_network" value="1" class="rounded bg-surface-900 border-white/10 text-brand-600 focus:ring-brand-500">
                        Мережевий пристрій / інтерфейс
                    </label>

                    @if($has_network)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 fade-in">
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">IP-Адреса</label>
                            <input type="text" wire:model="ip_address" placeholder="192.168.1.50" class="w-full px-4 py-2.5 bg-surface-900/60 border border-white/10 rounded-xl text-white text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                            @error('ip_address') <span class="text-xs text-red-400 mt-1">{{ $message }}</span>@enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">MAC-Адреса</label>
                            <input type="text" wire:model="mac_address" placeholder="AA:BB:CC:DD:EE:FF" class="w-full px-4 py-2.5 bg-surface-900/60 border border-white/10 rounded-xl text-white text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                            @error('mac_address') <span class="text-xs text-red-400 mt-1">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    @endif
                </div>
        </x-ui.modal>
    @endif

    {{-- Desktop --}}
    <x-table.wrapper>
            <x-slot name="headers">
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Пристрій (Інв. №)</x-table.th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Тип компонента</x-table.th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Модель</x-table.th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Серійний</x-table.th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Мережа</x-table.th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Статус</x-table.th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider w-24">Дії</x-table.th>
                </x-slot>
            <tbody class="divide-y divide-white/5">
                @forelse($components as $c)
                <tr class="hover:bg-white/[0.02] transition-colors text-sm">
                    <x-table.td align="left" primary class="text-white font-medium">
                        {{ $c->equipment->inventory_number ?? '-' }}
                        <span class="block text-[10px] text-gray-500">{{ $c->equipment->accounting_name ?? '' }}</span>
                    </x-table.td>
                    <x-table.td align="left" class="text-gray-300">{{ $c->componentType->component_name ?? '-' }}</x-table.td>
                    <x-table.td align="left" class="text-gray-300">
                        {{ $c->brand_model ?? '-' }}
                        @if($c->cartridge_model)
                            <span class="block text-[10px] text-brand-400">Картридж: {{ $c->cartridge_model }}</span>
                        @endif
                    </x-table.td>
                    <x-table.td align="left" class="text-gray-400 font-mono text-xs">{{ $c->serial_number ?? '-' }}</x-table.td>
                    <x-table.td align="left" class="text-gray-400">
                        @if($c->has_network)
                            <span class="text-xs text-brand-400 block font-mono">{{ $c->ip_address }}</span>
                            <span class="text-[10px] text-gray-500 block font-mono">{{ $c->mac_address }}</span>
                        @else
                            <span class="text-gray-600">-</span>
                        @endif
                    </x-table.td>
                    <x-table.td align="left">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium 
                            @if($c->status == 'Працює') bg-emerald-500/10 text-emerald-400
                            @elseif($c->status == 'Знято') bg-gray-500/10 text-gray-400
                            @elseif($c->status == 'В ремонті') bg-amber-500/10 text-amber-400
                            @else bg-red-500/10 text-red-400 @endif">
                            <span class="w-1.5 h-1.5 rounded-full 
                                @if($c->status == 'Працює') bg-emerald-400
                                @elseif($c->status == 'Знято') bg-gray-400
                                @elseif($c->status == 'В ремонті') bg-amber-400
                                @else bg-red-400 @endif"></span>
                            {{ $c->status }}
                        </span>
                    </x-table.td>
                    <x-table.td align="right">
                        <x-ui.action-buttons id="{{ $c->id }}" />
                    </x-table.td>
                </tr>
                @empty
                <tr><x-table.td colspan="7" class="px-4 py-10 text-center text-gray-600">Немає записів</x-table.td></tr>
                @endforelse
            </tbody>
        </x-table.wrapper>

    {{-- Mobile --}}
    <x-table.mobile-list>
        @forelse($components as $c)
        <div class="bg-surface-800/50 border border-white/5 rounded-xl p-4 space-y-2">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-xs text-brand-400 font-semibold uppercase tracking-wider">{{ $c->componentType->component_name ?? '-' }}</span>
                    <p class="text-sm text-white font-medium">{{ $c->brand_model ?? '-' }}</p>
                    <p class="text-xs text-gray-400">Пристрій: {{ $c->equipment->inventory_number ?? '-' }}</p>
                </div>
                <div class="flex gap-1 shrink-0">
                    <button wire:click="edit({{ $c->id }})" class="p-2 rounded-lg text-gray-500 hover:text-brand-400 hover:bg-brand-500/10 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                    <button wire:click="delete({{ $c->id }})" class="p-2 rounded-lg text-gray-500 hover:text-red-400 hover:bg-red-500/10 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-2 text-xs text-gray-400 border-t border-white/5 pt-2 font-mono">
                <div>
                    <span class="text-[10px] text-gray-500 block">Серійний:</span>
                    {{ $c->serial_number ?: '-' }}
                </div>
                @if($c->has_network)
                <div>
                    <span class="text-[10px] text-gray-500 block">IP / MAC:</span>
                    {{ $c->ip_address }}<br><span class="text-[9px]">{{ $c->mac_address }}</span>
                </div>
                @endif
            </div>

            <div class="flex items-center justify-between border-t border-white/5 pt-2">
                <span class="text-xs text-gray-500">Статус:</span>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium 
                    @if($c->status == 'Працює') bg-emerald-500/10 text-emerald-400
                    @elseif($c->status == 'Знято') bg-gray-500/10 text-gray-400
                    @elseif($c->status == 'В ремонті') bg-amber-500/10 text-amber-400
                    @else bg-red-500/10 text-red-400 @endif">
                    {{ $c->status }}
                </span>
            </div>
        </div>
        @empty
        <div class="text-center py-10 text-gray-600 text-sm">Немає записів</div>
        @endforelse
    </x-table.mobile-list>
</div>
