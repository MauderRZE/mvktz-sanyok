<div class="space-y-6">
    {{-- Flash Message --}}
    <x-ui.flash />
{{-- Header & Filters --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <p class="text-sm text-gray-500">Всього записів: <span class="text-gray-300 font-medium">{{ $equipments->total() }}</span></p>
        </div>
        <x-ui.button wire:click="create()">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Додати
        </x-ui.button>
    </div>

    {{-- Filters Bar --}}
    <x-ui.card class="p-4 flex flex-col sm:flex-row gap-4 items-center">
        <x-form.search wire:model.debounce.300ms="search" placeholder="Пошук за інвентарним номером або назвою..." />
        <div class="w-full sm:w-48">
            <x-form.simple-select wire:model="filterType">
                <option value="">Усі типи</option>
                @foreach($types as $t)
                    <option value="{{ $t->id }}">{{ $t->type_name }}</option>
                @endforeach
            </x-form.simple-select>
        </div>
        <div class="w-full sm:w-48">
            <x-form.simple-select wire:model="filterStatus">
                <option value="">Всі статуси</option>
                <option value="В експлуатації">В експлуатації</option>
                <option value="На складі">На складі</option>
                <option value="Списано">Списано</option>
            </x-form.simple-select>
        </div>
    </x-ui.card>

    {{-- Modal --}}
    @if($isOpen)
    <x-ui.modal title="{{ $equipmentId ? 'Редагувати' : 'Додати' }} обладнання" maxWidth="lg">
        <div class="space-y-4" x-data x-init="document.body.style.overflow='hidden'" x-destroy="document.body.style.overflow=''">
            <div>
                <x-form.input label="Інвентарний номер" model="inventory_number" type="text" />
            </div>
            <div>
                <x-form.input label="Назва (бухгалтерська)" model="accounting_name" type="text" />
            </div>
            <div>
                <x-form.select label="Тип" model="equipment_type_id">
                    <option value="" class="bg-surface-800">— Оберіть тип —</option>
                    @foreach($types as $t)
                        <option value="{{ $t->id }}" class="bg-surface-800">{{ $t->type_name }}</option>
                    @endforeach
                </x-form.select>
            </div>
            <div>
                <x-form.select label="Статус" model="status">
                    <option value="В експлуатації" class="bg-surface-800">В експлуатації</option>
                    <option value="На складі" class="bg-surface-800">На складі</option>
                    <option value="Списано" class="bg-surface-800">Списано</option>
                </x-form.select>
            </div>
        </div>
    </x-ui.modal>
    @endif

    {{-- Desktop Table --}}
    <x-table.wrapper>
            <x-slot name="headers">
                    <x-table.th align="left" wire:click="sortBy('id')" class="cursor-pointer hover:bg-white/5">
                        <div class="flex items-center gap-1">ID @if($sortField === 'id') <span class="text-brand-400">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif</div>
                    </x-table.th>
                    <x-table.th align="left" wire:click="sortBy('inventory_number')" class="cursor-pointer hover:bg-white/5">
                        <div class="flex items-center gap-1">Інв. № @if($sortField === 'inventory_number') <span class="text-brand-400">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif</div>
                    </x-table.th>
                    <x-table.th align="left" wire:click="sortBy('accounting_name')" class="cursor-pointer hover:bg-white/5">
                        <div class="flex items-center gap-1">Назва @if($sortField === 'accounting_name') <span class="text-brand-400">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif</div>
                    </x-table.th>
                    <x-table.th align="left" wire:click="sortBy('equipment_type_id')" class="cursor-pointer hover:bg-white/5">
                        <div class="flex items-center gap-1">Тип @if($sortField === 'equipment_type_id') <span class="text-brand-400">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif</div>
                    </x-table.th>
                    <x-table.th align="left" wire:click="sortBy('status')" class="cursor-pointer hover:bg-white/5">
                        <div class="flex items-center gap-1">Статус @if($sortField === 'status') <span class="text-brand-400">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif</div>
                    </x-table.th>
                    <x-table.th align="right">Дії</x-table.th>
                </x-slot>
            
                @forelse($equipments as $eq)
                <x-table.tr>
                    <x-table.td align="left" muted>#{{ $eq->id }}</x-table.td>
                    <x-table.td align="left" primary class="text-white font-medium">{{ $eq->inventory_number }}</x-table.td>
                    <x-table.td align="left" class="text-gray-300">{{ $eq->accounting_name }}</x-table.td>
                    <x-table.td align="left"><span class="px-2.5 py-1 rounded-lg bg-brand-500/10 text-brand-300 text-xs font-medium">{{ $eq->type->type_name ?? '—' }}</span></x-table.td>
                    <x-table.td align="left">
                        <x-ui.badge status="{{ $eq->status }}" />
                    </x-table.td>
                    <x-table.td align="right">
                        <x-ui.action-buttons id="{{ $eq->id }}" />
                    </x-table.td>
                </x-table.tr>
                @empty
                <x-table.tr><x-table.td colspan="6" class="px-5 py-10 text-center text-gray-600 text-sm">Немає записів</x-table.td></x-table.tr>
                @endforelse
            
        </x-table.wrapper>

    {{-- Mobile Cards --}}
    <x-table.mobile-list>
        @forelse($equipments as $eq)
        <x-table.mobile-card layout="y-3">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-white font-medium text-sm">{{ $eq->accounting_name }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">Інв. № {{ $eq->inventory_number }}</p>
                </div>
                <x-ui.badge status="{{ $eq->status }}" />
            </div>
            <div class="flex items-center justify-between pt-2 border-t border-white/5">
                <span class="px-2 py-0.5 rounded-lg bg-brand-500/10 text-brand-300 text-xs font-medium">{{ $eq->type->type_name ?? '—' }}</span>
                <x-ui.action-buttons id="{{ $eq->id }}" />
            </div>
        </x-table.mobile-card>
        @empty
        <div class="text-center py-10 text-gray-600 text-sm">Немає записів</div>
        @endforelse
    </x-table.mobile-list>

    <div class="mt-4">
        {{ $equipments->links() }}
    </div>

    {{-- Slide-over --}}
    @if($isViewOpen && $viewEquipment)
    <x-ui.slide-over title="Деталі обладнання: {{ $viewEquipment->inventory_number }}" maxWidth="2xl">
        <div class="mb-6">
            <h3 class="text-2xl font-bold text-white mb-1">{{ $viewEquipment->accounting_name }}</h3>
            <div class="flex items-center gap-3 mt-2">
                <span class="px-3 py-1 rounded-lg bg-brand-500/10 text-brand-300 text-sm font-medium">{{ $viewEquipment->type->type_name ?? '—' }}</span>
                <x-ui.badge status="{{ $viewEquipment->status }}" />
            </div>
        </div>

        <x-ui.tabs active="components">
            <x-slot name="nav">
                <x-ui.tab-nav name="components" label="Комплектуючі" icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" /></svg>' />
                <x-ui.tab-nav name="movements" label="Рух" icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>' />
                <x-ui.tab-nav name="complaints" label="Скарги" icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>' />
                <x-ui.tab-nav name="maintenance" label="ТО та ремонти" icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>' />
            </x-slot>

            <x-ui.tab-content name="components">
                <div class="space-y-4">
                    @forelse($viewEquipment->components as $comp)
                        <div class="bg-surface-800 border border-white/5 rounded-xl p-4 flex justify-between items-center">
                            <div>
                                <p class="text-white font-medium">{{ $comp->componentType->component_name ?? 'Невідомо' }}</p>
                                <p class="text-sm text-gray-500">{{ $comp->brand_model }} <span class="text-gray-600">|</span> s/n: {{ $comp->serial_number ?? '—' }}</p>
                            </div>
                            <div>
                                <x-ui.badge status="{{ $comp->status }}" :dot="false" />
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">Немає встановлених компонентів</p>
                    @endforelse
                </div>
            </x-ui.tab-content>

            <x-ui.tab-content name="movements">
                <div class="space-y-4">
                    @forelse($viewEquipment->movements as $mov)
                        <div class="bg-surface-800 border border-white/5 rounded-xl p-4 flex justify-between items-center">
                            <div>
                                <p class="text-white font-medium">{{ $mov->location->room_number ?? 'Невідомо' }}</p>
                                <p class="text-sm text-gray-500">Відповідальний: {{ $mov->employee->last_name ?? '—' }} {{ $mov->employee->first_name ?? '' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-400">{{ $mov->move_date }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">Історія переміщень порожня</p>
                    @endforelse
                </div>
            </x-ui.tab-content>

            <x-ui.tab-content name="complaints">
                <div class="space-y-4">
                    @forelse($viewEquipment->complaints as $comp)
                        <div class="bg-surface-800 border border-white/5 rounded-xl p-4">
                            <div class="flex justify-between items-start mb-2">
                                <p class="text-white font-medium">{{ $comp->description }}</p>
                                <x-ui.badge status="{{ $comp->resolution_status }}" :dot="false" />
                            </div>
                            <p class="text-sm text-gray-500">Дата: {{ $comp->created_at ?? 'Невідомо' }}</p>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">Скарг не знайдено</p>
                    @endforelse
                </div>
            </x-ui.tab-content>

            <x-ui.tab-content name="maintenance">
                <div class="space-y-4">
                    @forelse($viewEquipment->maintenanceLogs as $log)
                        <div class="bg-surface-800 border border-white/5 rounded-xl p-4 flex justify-between items-center">
                            <div>
                                <p class="text-white font-medium">{{ $log->performed_by }}</p>
                                <p class="text-sm text-brand-400">{{ $log->cost ? $log->cost . ' грн' : 'Безкоштовно' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-400 mb-1">{{ $log->maintenance_date }}</p>
                                <x-ui.badge status="{{ $log->status }}" :dot="false" />
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">Журнал ТО порожній</p>
                    @endforelse
                </div>
            </x-ui.tab-content>
        </x-ui.tabs>
    </x-ui.slide-over>
    @endif
</div>
