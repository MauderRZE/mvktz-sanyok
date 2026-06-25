<div class="space-y-6">
    <x-ui.flash />
<x-ui.toolbar :count="count($components)" label="Всього" buttonLabel="Додати" />

    @if($isOpen)
    <x-ui.modal title="{{ $componentId ? 'Редагувати' : 'Додати' }} комплектуюче" maxWidth="lg">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <x-form.select label="Обладнання (ПК / Пристрій)" model="equipment_id">
                            <option value="">Оберіть обладнання...</option>
                            @foreach($equipmentList as $eq)
                                <option value="{{ $eq->id }}">{{ $eq->inventory_number }} - {{ $eq->accounting_name }}</option>
                            @endforeach
                        </x-form.select>
                    </div>
                    <div>
                        <x-form.select label="Базовий компонент" model="component_type_id">
                            <option value="">Оберіть компонент...</option>
                            @foreach($baseComponentsList as $bc)
                                <option value="{{ $bc->id }}">{{ $bc->component_name }}</option>
                            @endforeach
                        </x-form.select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <x-form.input label="Виробник / Модель" model="brand_model" type="text" placeholder="напр. Kingston DDR4 16GB" />
                    </div>
                    <div>
                        <x-form.input label="Серійний номер" model="serial_number" type="text" />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <x-form.input label="Модель картриджа (якщо є)" model="cartridge_model" type="text" />
                    </div>
                    <div>
                        <x-form.select label="Стан роботи" model="status">
                            <option value="Працює">Працює</option>
                            <option value="Знято">Знято</option>
                            <option value="Зламано">Зламано</option>
                            <option value="В ремонті">В ремонті</option>
                        </x-form.select>
                    </div>
                </div>

                <div class="border-t border-white/5 pt-4">
                    <x-form.checkbox label="Мережевий пристрій / інтерфейс" model="has_network" />

                    @if($has_network)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 fade-in">
                        <div>
                            <x-form.input label="IP-Адреса" model="ip_address" type="text" placeholder="192.168.1.50" />
                        </div>
                        <div>
                            <x-form.input label="MAC-Адреса" model="mac_address" type="text" placeholder="AA:BB:CC:DD:EE:FF" />
                        </div>
                    </div>
                    @endif
                </div>
        </x-ui.modal>
    @endif

    {{-- Desktop --}}
    <x-table.wrapper>
            <x-slot name="headers">
                    <x-table.th align="left">Пристрій (Інв. №)</x-table.th>
                    <x-table.th align="left">Тип компонента</x-table.th>
                    <x-table.th align="left">Модель</x-table.th>
                    <x-table.th align="left">Серійний</x-table.th>
                    <x-table.th align="left">Мережа</x-table.th>
                    <x-table.th align="left">Статус</x-table.th>
                    <x-table.th align="right" width="24">Дії</x-table.th>
                </x-slot>
            
                @forelse($components as $c)
                <x-table.tr class="text-sm">
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
                </x-table.tr>
                @empty
                <x-table.tr><x-table.td colspan="7" class="px-4 py-10 text-center text-gray-600">Немає записів</x-table.td></x-table.tr>
                @endforelse
            
        </x-table.wrapper>

    {{-- Mobile --}}
    <x-table.mobile-list>
        @forelse($components as $c)
        <x-table.mobile-card layout="y-2">
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
        </x-table.mobile-card>
        @empty
        <div class="text-center py-10 text-gray-600 text-sm">Немає записів</div>
        @endforelse
    </x-table.mobile-list>
</div>
