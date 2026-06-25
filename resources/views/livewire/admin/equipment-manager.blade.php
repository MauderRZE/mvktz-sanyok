<div class="space-y-6">
    {{-- Flash Message --}}
    <x-ui.flash />
{{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <p class="text-sm text-gray-500">Всього записів: <span class="text-gray-300 font-medium">{{ count($equipments) }}</span></p>
        </div>
        <button wire:click="create()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-brand-600 to-brand-500 hover:from-brand-500 hover:to-brand-400 text-white text-sm font-semibold rounded-xl shadow-lg shadow-brand-500/20 hover:shadow-brand-500/30 transition-all duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Додати
        </button>
    </div>

    {{-- Modal --}}
    @if($isOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4" x-data x-init="document.body.style.overflow='hidden'" x-destroy="document.body.style.overflow=''">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeModal()"></div>
        <div class="relative w-full max-w-lg bg-surface-800 border border-white/5 rounded-2xl shadow-2xl fade-in overflow-hidden">
            <div class="px-6 py-4 border-b border-white/5 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white">{{ $equipmentId ? 'Редагувати' : 'Додати' }} обладнання</h3>
                <button wire:click="closeModal()" class="text-gray-500 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-6 space-y-4">
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
            <div class="px-6 py-4 border-t border-white/5 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                <button wire:click="closeModal()" class="px-5 py-2.5 text-sm font-medium text-gray-400 hover:text-white bg-white/5 hover:bg-white/10 rounded-xl transition-colors">Скасувати</button>
                <button wire:click.prevent="store()" class="px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-brand-600 to-brand-500 hover:from-brand-500 hover:to-brand-400 rounded-xl shadow-lg shadow-brand-500/20 transition-all">Зберегти</button>
            </div>
        </div>
    </div>
    @endif

    {{-- Desktop Table --}}
    <x-table.wrapper>
            <x-slot name="headers">
                    <x-table.th align="left">ID</x-table.th>
                    <x-table.th align="left">Інв. №</x-table.th>
                    <x-table.th align="left">Назва</x-table.th>
                    <x-table.th align="left">Тип</x-table.th>
                    <x-table.th align="left">Статус</x-table.th>
                    <x-table.th align="right">Дії</x-table.th>
                </x-slot>
            <tbody class="divide-y divide-white/5">
                @forelse($equipments as $eq)
                <tr class="hover:bg-white/[0.02] transition-colors">
                    <x-table.td align="left" class="text-gray-500">#{{ $eq->id }}</x-table.td>
                    <x-table.td align="left" primary class="text-white font-medium">{{ $eq->inventory_number }}</x-table.td>
                    <x-table.td align="left" class="text-gray-300">{{ $eq->accounting_name }}</x-table.td>
                    <x-table.td align="left"><span class="px-2.5 py-1 rounded-lg bg-brand-500/10 text-brand-300 text-xs font-medium">{{ $eq->type->type_name ?? '—' }}</span></x-table.td>
                    <x-table.td align="left">
                        @if($eq->status == 'В експлуатації')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-500/10 text-emerald-400 text-xs font-medium"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>{{ $eq->status }}</span>
                        @elseif($eq->status == 'На складі')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-amber-500/10 text-amber-400 text-xs font-medium"><span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>{{ $eq->status }}</span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-red-500/10 text-red-400 text-xs font-medium"><span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>{{ $eq->status }}</span>
                        @endif
                    </x-table.td>
                    <x-table.td align="right">
                        <x-ui.action-buttons id="{{ $eq->id }}" />
                    </x-table.td>
                </tr>
                @empty
                <tr><x-table.td colspan="6" class="px-5 py-10 text-center text-gray-600 text-sm">Немає записів</x-table.td></tr>
                @endforelse
            </tbody>
        </x-table.wrapper>

    {{-- Mobile Cards --}}
    <x-table.mobile-list>
        @forelse($equipments as $eq)
        <div class="bg-surface-800/50 border border-white/5 rounded-xl p-4 space-y-3">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-white font-medium text-sm">{{ $eq->accounting_name }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">Інв. № {{ $eq->inventory_number }}</p>
                </div>
                @if($eq->status == 'В експлуатації')
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg bg-emerald-500/10 text-emerald-400 text-xs font-medium"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>{{ $eq->status }}</span>
                @elseif($eq->status == 'На складі')
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg bg-amber-500/10 text-amber-400 text-xs font-medium"><span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>{{ $eq->status }}</span>
                @else
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg bg-red-500/10 text-red-400 text-xs font-medium"><span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>{{ $eq->status }}</span>
                @endif
            </div>
            <div class="flex items-center justify-between pt-2 border-t border-white/5">
                <span class="px-2 py-0.5 rounded-lg bg-brand-500/10 text-brand-300 text-xs font-medium">{{ $eq->type->type_name ?? '—' }}</span>
                <x-ui.action-buttons id="{{ $eq->id }}" />
            </div>
        </div>
        @empty
        <div class="text-center py-10 text-gray-600 text-sm">Немає записів</div>
        @endforelse
    </x-table.mobile-list>

    {{-- Slide-over --}}
    @if($isViewOpen && $viewEquipment)
    <x-ui.slide-over title="Деталі обладнання: {{ $viewEquipment->inventory_number }}" maxWidth="2xl">
        <div class="mb-6">
            <h3 class="text-2xl font-bold text-white mb-1">{{ $viewEquipment->accounting_name }}</h3>
            <div class="flex items-center gap-3 mt-2">
                <span class="px-3 py-1 rounded-lg bg-brand-500/10 text-brand-300 text-sm font-medium">{{ $viewEquipment->type->type_name ?? '—' }}</span>
                @if($viewEquipment->status == 'В експлуатації')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-emerald-500/10 text-emerald-400 text-sm font-medium"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>{{ $viewEquipment->status }}</span>
                @elseif($viewEquipment->status == 'На складі')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-amber-500/10 text-amber-400 text-sm font-medium"><span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>{{ $viewEquipment->status }}</span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-red-500/10 text-red-400 text-sm font-medium"><span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>{{ $viewEquipment->status }}</span>
                @endif
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
                                <span class="px-2 py-1 rounded text-xs font-medium {{ $comp->status == 'Працює' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-red-500/10 text-red-400' }}">{{ $comp->status }}</span>
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
                                <span class="px-2 py-1 rounded text-xs font-medium {{ $comp->resolution_status == 'Відкрито' ? 'bg-red-500/10 text-red-400' : ($comp->resolution_status == 'В роботі' ? 'bg-amber-500/10 text-amber-400' : 'bg-emerald-500/10 text-emerald-400') }}">{{ $comp->resolution_status }}</span>
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
                                <span class="px-2 py-1 rounded text-xs font-medium {{ $log->status == 'Виконано' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-amber-500/10 text-amber-400' }}">{{ $log->status }}</span>
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
