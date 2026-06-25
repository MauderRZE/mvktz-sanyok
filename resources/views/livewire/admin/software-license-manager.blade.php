<div class="space-y-6">
    <x-ui.flash />
<x-ui.toolbar :count="count($licenses)" label="Всього ліцензій" buttonLabel="Додати ліцензію" />

    @if($isOpen)
    <x-ui.modal title="{{ $licenseId ? 'Редагувати' : 'Додати' }} ліцензію ПЗ" maxWidth="md">
            <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Назва програмного забезпечення</label>
                    <input type="text" wire:model="software_name" placeholder="напр. Windows 11 Pro, Office 2021" class="w-full px-4 py-2.5 bg-surface-900/60 border border-white/10 rounded-xl text-white text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                    @error('software_name') <span class="text-xs text-red-400 mt-1">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Ліцензійний ключ / Сертифікат</label>
                    <input type="text" wire:model="license_key" class="w-full px-4 py-2.5 bg-surface-900/60 border border-white/10 rounded-xl text-white text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                    @error('license_key') <span class="text-xs text-red-400 mt-1">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Встановлено на комплектуюче (ПК)</label>
                    <select wire:model="component_id" class="w-full px-4 py-2.5 bg-surface-900/60 border border-white/10 rounded-xl text-white text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                        <option value="">Оберіть компонент...</option>
                        @foreach($componentsList as $comp)
                            <option value="{{ $comp->id }}">
                                [{{ $comp->equipment->inventory_number ?? 'Склад' }}] {{ $comp->componentType->component_name ?? '-' }} ({{ $comp->brand_model }})
                            </option>
                        @endforeach
                    </select>
                    @error('component_id') <span class="text-xs text-red-400 mt-1">{{ $message }}</span>@enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Статус ліцензії</label>
                        <select wire:model="license_status" class="w-full px-4 py-2.5 bg-surface-900/60 border border-white/10 rounded-xl text-white text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                            <option value="Активна">Активна</option>
                            <option value="Прострочена">Прострочена</option>
                            <option value="Призупинена">Призупинена</option>
                        </select>
                        @error('license_status') <span class="text-xs text-red-400 mt-1">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Дата закінчення</label>
                        <input type="date" wire:model="expiration_date" class="w-full px-4 py-2.5 bg-surface-900/60 border border-white/10 rounded-xl text-white text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                        @error('expiration_date') <span class="text-xs text-red-400 mt-1">{{ $message }}</span>@enderror
                    </div>
                </div>
        </x-ui.modal>
    @endif

    {{-- Desktop --}}
    <x-table.wrapper>
            <x-slot name="headers">
                    <x-table.th align="left">Назва ПЗ</x-table.th>
                    <x-table.th align="left">Ключ ліцензії</x-table.th>
                    <x-table.th align="left">Встановлено на ПК (Компонент)</x-table.th>
                    <x-table.th align="left">Термін дії</x-table.th>
                    <x-table.th align="left">Статус</x-table.th>
                    <x-table.th align="right" width="32">Дії</x-table.th>
                </x-slot>
            <tbody class="divide-y divide-white/5 text-sm">
                @forelse($licenses as $lic)
                <tr class="hover:bg-white/[0.02] transition-colors">
                    <x-table.td align="left" primary class="text-white font-medium">{{ $lic->software_name }}</x-table.td>
                    <x-table.td align="left" class="text-gray-400 font-mono text-xs">{{ $lic->license_key ?? '-' }}</x-table.td>
                    <x-table.td align="left" class="text-gray-300 text-xs">
                        @if($lic->component)
                            <span class="text-brand-400 font-medium">[{{ $lic->component->equipment->inventory_number ?? 'Склад' }}]</span> 
                            {{ $lic->component->componentType->component_name ?? '-' }} ({{ $lic->component->brand_model }})
                        @else
                            -
                        @endif
                    </x-table.td>
                    <x-table.td align="left" class="text-gray-400 whitespace-nowrap">
                        @if($lic->expiration_date)
                            {{ $lic->expiration_date }}
                        @else
                            <span class="text-gray-600">Безстрокова</span>
                        @endif
                    </x-table.td>
                    <x-table.td align="left">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium 
                            @if($lic->license_status == 'Активна') bg-emerald-500/10 text-emerald-400
                            @elseif($lic->license_status == 'Призупинена') bg-amber-500/10 text-amber-400
                            @else bg-red-500/10 text-red-400 @endif">
                            <span class="w-1.5 h-1.5 rounded-full 
                                @if($lic->license_status == 'Активна') bg-emerald-400
                                @elseif($lic->license_status == 'Призупинена') bg-amber-400
                                @else bg-red-400 @endif"></span>
                            {{ $lic->license_status }}
                        </span>
                    </x-table.td>
                    <x-table.td align="right">
                        <x-ui.action-buttons id="{{ $lic->id }}" />
                    </x-table.td>
                </tr>
                @empty
                <tr><x-table.td colspan="6" class="px-5 py-10 text-center text-gray-600">Немає записів</x-table.td></tr>
                @endforelse
            </tbody>
        </x-table.wrapper>

    {{-- Mobile --}}
    <x-table.mobile-list>
        @forelse($licenses as $lic)
        <div class="bg-surface-800/50 border border-white/5 rounded-xl p-4 space-y-2">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-xs text-brand-400 font-semibold uppercase tracking-wider">Ліцензія ПЗ</span>
                    <p class="text-sm text-white font-medium">{{ $lic->software_name }}</p>
                    <p class="text-xs text-gray-400">Ключ: <span class="font-mono">{{ $lic->license_key ?: '-' }}</span></p>
                </div>
                <div class="flex gap-1 shrink-0">
                    <button wire:click="edit({{ $lic->id }})" class="p-2 rounded-lg text-gray-500 hover:text-brand-400 hover:bg-brand-500/10 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                    <button wire:click="delete({{ $lic->id }})" class="p-2 rounded-lg text-gray-500 hover:text-red-400 hover:bg-red-500/10 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                </div>
            </div>
            
            <div class="text-xs text-gray-400 border-t border-white/5 pt-2">
                Встановлено: 
                @if($lic->component)
                    [{{ $lic->component->equipment->inventory_number ?? 'Склад' }}] {{ $lic->component->componentType->component_name ?? '-' }} ({{ $lic->component->brand_model }})
                @else
                    -
                @endif
            </div>

            <div class="flex items-center justify-between border-t border-white/5 pt-2 text-xs">
                <span class="text-gray-500 font-semibold">Термін:</span>
                <span class="text-gray-300">{{ $lic->expiration_date ?: 'Безстрокова' }}</span>
                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-medium 
                    @if($lic->license_status == 'Активна') bg-emerald-500/10 text-emerald-400
                    @elseif($lic->license_status == 'Призупинена') bg-amber-500/10 text-amber-400
                    @else bg-red-500/10 text-red-400 @endif">
                    {{ $lic->license_status }}
                </span>
            </div>
        </div>
        @empty
        <div class="text-center py-10 text-gray-600 text-sm">Немає записів</div>
        @endforelse
    </x-table.mobile-list>
</div>
