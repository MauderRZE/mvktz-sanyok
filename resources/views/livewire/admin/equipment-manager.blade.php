<x-ui.page-wrapper>
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
    <x-ui.card class="p-4 space-y-4">
        <div class="flex flex-col lg:flex-row gap-4 items-center">
            <div class="flex-1 w-full flex gap-2">
                <div class="flex-1">
                    <x-form.search wire:model.debounce.300ms="search" placeholder="Пошук за інвентарним номером або назвою..." />
                </div>
                @if($search !== '' || !empty($filterType) || !empty($filterStatus) || !empty($filterLocation) || !empty($filterEmployee) || !empty($filterCategory))
                    <button wire:click="resetFilters" class="px-4 py-2 bg-white/5 hover:bg-white/10 text-xs text-gray-400 hover:text-white rounded-xl border border-white/10 transition-colors shrink-0 flex items-center gap-1.5" title="Скинути всі фільтри">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        <span>Скинути</span>
                    </button>
                @endif
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 w-full lg:w-auto">
                <x-form.multi-select label="Усі категорії" :selectedCount="count($filterCategory)">
                    @foreach($categoriesList as $cat)
                        <label class="flex items-center gap-2 text-xs text-gray-300 hover:text-white cursor-pointer py-1">
                            <input type="checkbox" value="{{ $cat->id }}" wire:model="filterCategory" class="rounded border-white/10 bg-surface-900 text-brand-500 focus:ring-0 focus:ring-offset-0">
                            <span>{{ $cat->category_name }}</span>
                        </label>
                    @endforeach
                </x-form.multi-select>

                <x-form.multi-select label="Усі типи" :selectedCount="count($filterType)">
                    @foreach($types as $t)
                        <label class="flex items-center gap-2 text-xs text-gray-300 hover:text-white cursor-pointer py-1">
                            <input type="checkbox" value="{{ $t->id }}" wire:model="filterType" class="rounded border-white/10 bg-surface-900 text-brand-500 focus:ring-0 focus:ring-offset-0">
                            <span>{{ $t->type_name }}</span>
                        </label>
                    @endforeach
                </x-form.multi-select>

                <x-form.multi-select label="Всі статуси" :selectedCount="count($filterStatus)">
                    @foreach(['В експлуатації', 'На складі', 'Списано'] as $status)
                        <label class="flex items-center gap-2 text-xs text-gray-300 hover:text-white cursor-pointer py-1">
                            <input type="checkbox" value="{{ $status }}" wire:model="filterStatus" class="rounded border-white/10 bg-surface-900 text-brand-500 focus:ring-0 focus:ring-offset-0">
                            <span>{{ $status }}</span>
                        </label>
                    @endforeach
                </x-form.multi-select>

                <x-form.multi-select label="Усі локації" :selectedCount="count($filterLocation)">
                    @foreach($locationsList as $loc)
                        <label class="flex items-center gap-2 text-xs text-gray-300 hover:text-white cursor-pointer py-1">
                            <input type="checkbox" value="{{ $loc->id }}" wire:model="filterLocation" class="rounded border-white/10 bg-surface-900 text-brand-500 focus:ring-0 focus:ring-offset-0">
                            <span>Каб. {{ $loc->room_number }}</span>
                        </label>
                    @endforeach
                </x-form.multi-select>

                <x-form.multi-select label="Усі співробітники" :selectedCount="count($filterEmployee)">
                    @foreach($employeesList as $emp)
                        <label class="flex items-center gap-2 text-xs text-gray-300 hover:text-white cursor-pointer py-1">
                            <input type="checkbox" value="{{ $emp->id }}" wire:model="filterEmployee" class="rounded border-white/10 bg-surface-900 text-brand-500 focus:ring-0 focus:ring-offset-0">
                            <span>{{ $emp->last_name }} {{ mb_substr($emp->first_name, 0, 1) }}.</span>
                        </label>
                    @endforeach
                </x-form.multi-select>
            </div>
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
            <div>
                <x-form.input label="Дата введення в експлуатацію" model="commissioning_date" type="date" />
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
                    <x-table.th align="left">Комплектуючі</x-table.th>
                    <x-table.th align="left">Розташування / Відпов.</x-table.th>
                    <x-table.th align="left">Статистика</x-table.th>
                    <x-table.th align="left" wire:click="sortBy('status')" class="cursor-pointer hover:bg-white/5">
                        <div class="flex items-center gap-1">Статус @if($sortField === 'status') <span class="text-brand-400">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif</div>
                    </x-table.th>
                    <x-table.th align="right">Дії</x-table.th>
                </x-slot>
            
                @forelse($equipments as $eq)
                <x-table.tr>
                    <x-table.td align="left" muted>#{{ $eq->id }}</x-table.td>
                    <x-table.td align="left" primary>{{ $eq->inventory_number }}</x-table.td>
                    <x-table.td align="left">{{ $eq->accounting_name }}</x-table.td>
                    <x-table.td align="left"><span class="px-2.5 py-1 rounded-lg bg-brand-500/10 text-brand-300 text-xs font-medium">{{ $eq->type->type_name ?? '—' }}</span></x-table.td>
                    <x-table.td align="left" class="text-xs text-gray-300">
                        @if($eq->components->count() > 0)
                            <div class="font-medium text-gray-200">{{ $eq->components->count() }} од.</div>
                            <x-table.cell-subtext class="max-w-[150px] truncate" title="{{ $eq->components->map(fn($c) => ($c->componentType->component_name ?? '') . ($c->brand_model ? ' (' . $c->brand_model . ')' : ''))->implode(', ') }}">
                                {{ $eq->components->map(fn($c) => $c->componentType->component_name ?? '')->unique()->implode(', ') }}
                            </x-table.cell-subtext>
                        @else
                            <span class="text-gray-600">—</span>
                        @endif
                    </x-table.td>
                    <x-table.td align="left" class="text-xs text-gray-300">
                        @php
                            $latestMove = $eq->movements->sortByDesc('move_date')->first();
                        @endphp
                        @if($latestMove)
                            <div class="font-medium text-brand-300">Каб. {{ $latestMove->location->room_number ?? '—' }}</div>
                            <x-table.cell-subtext>
                                {{ $latestMove->employee ? ($latestMove->employee->last_name . ' ' . mb_substr($latestMove->employee->first_name, 0, 1) . '.') : '—' }}
                            </x-table.cell-subtext>
                        @else
                            <span class="text-gray-600">Немає руху</span>
                        @endif
                    </x-table.td>
                    <x-table.td align="left" class="text-xs">
                        <div class="flex items-center gap-1.5">
                            @if($eq->softwareLicenses->count() > 0)
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-blue-500/10 text-blue-400 border border-blue-500/20" title="Ліцензії ПЗ">
                                    💾 {{ $eq->softwareLicenses->count() }}
                                </span>
                            @endif
                            @if($eq->complaints->count() > 0)
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-red-500/10 text-red-400 border border-red-500/20" title="Скарги">
                                    ⚠️ {{ $eq->complaints->count() }}
                                </span>
                            @endif
                            @if($eq->maintenanceLogs->count() > 0)
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20" title="Обслуговування">
                                    🔧 {{ $eq->maintenanceLogs->count() }}
                                </span>
                            @endif
                            @if($eq->softwareLicenses->isEmpty() && $eq->complaints->isEmpty() && $eq->maintenanceLogs->isEmpty())
                                <span class="text-gray-600">—</span>
                            @endif
                        </div>
                    </x-table.td>
                    <x-table.td align="left">
                        <x-ui.badge status="{{ $eq->status }}" />
                    </x-table.td>
                    <x-table.td align="right">
                        <x-ui.action-buttons id="{{ $eq->id }}" :viewAction="true" />
                    </x-table.td>
                </x-table.tr>
                @empty
                <x-table.empty colspan="9" />
                @endforelse
            
        </x-table.wrapper>

    {{-- Mobile Cards --}}
    <x-table.mobile-list>
        @forelse($equipments as $eq)
        <x-table.mobile-card layout="y-3">
            <x-table.mobile-card-header align="start">
                <x-ui.text-block title="{{ $eq->accounting_name }}" subtitle="Інв. № {{ $eq->inventory_number }}" />
                <x-ui.badge status="{{ $eq->status }}" />
            </x-table.mobile-card-header>
            <x-table.mobile-card-footer flex="true">
                <span class="px-2 py-0.5 rounded-lg bg-brand-500/10 text-brand-300 text-xs font-medium">{{ $eq->type->type_name ?? '—' }}</span>
                <x-ui.action-buttons id="{{ $eq->id }}" :viewAction="true" />
            </x-table.mobile-card-footer>
        </x-table.mobile-card>
        @empty
        <x-table.mobile-empty />
        @endforelse
    </x-table.mobile-list>

    <div class="mt-4">
        {{ $equipments->links() }}
    </div>

    {{-- Slide-over --}}
    @if($isViewOpen && $viewEquipment)
    <x-ui.slide-over title="Деталі обладнання: {{ $viewEquipment->inventory_number }}" maxWidth="4xl">
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
                <x-ui.tab-nav name="licenses" label="Ліцензії ПЗ" icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>' />
                <x-ui.tab-nav name="lowValueMaterials" label="Матеріали / МШП" icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>' />
                <x-ui.tab-nav name="complaints" label="Скарги" icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>' />
                <x-ui.tab-nav name="maintenance" label="ТО та ремонти" icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>' />
            </x-slot>

            <x-ui.tab-content name="components">
                <x-table.mobile-list>
                    @forelse($viewEquipment->components as $comp)
                        <x-table.mobile-card>
                            <div>
                                <x-ui.text-block 
                                    title="{{ $comp->componentType->component_name ?? 'Невідомо' }}" 
                                    subtitle="{{ $comp->brand_model }} | s/n: {{ $comp->serial_number ?? '—' }}" 
                                />
                            </div>
                            <div>
                                <x-ui.badge status="{{ $comp->status }}" :dot="false" />
                            </div>
                        </x-table.mobile-card>
                    @empty
                        <x-table.mobile-empty>Немає встановлених компонентів</x-table.mobile-empty>
                    @endforelse
                </x-table.mobile-list>
            </x-ui.tab-content>

            <x-ui.tab-content name="movements">
                <x-table.mobile-list>
                    @forelse($viewEquipment->movements as $mov)
                        <x-table.mobile-card>
                            <div>
                                <x-ui.text-block 
                                    title="{{ $mov->location->room_number ?? 'Невідомо' }}" 
                                    subtitle="Відповідальний: {{ $mov->employee->last_name ?? '—' }} {{ $mov->employee->first_name ?? '' }}" 
                                />
                            </div>
                            <div>
                                <p class="text-sm text-gray-400">{{ $mov->move_date }}</p>
                            </div>
                        </x-table.mobile-card>
                    @empty
                        <x-table.mobile-empty>Історія переміщень порожня</x-table.mobile-empty>
                    @endforelse
                </x-table.mobile-list>
            </x-ui.tab-content>

            <x-ui.tab-content name="licenses">
                <x-table.mobile-list>
                    @forelse($viewEquipment->softwareLicenses as $lic)
                        <x-table.mobile-card>
                            <div>
                                <x-ui.text-block 
                                    title="{{ $lic->software_name }}" 
                                    subtitle="Ключ: {{ $lic->license_key ?? '—' }}" 
                                    subtitleClass="text-xs text-gray-500 font-mono"
                                />
                                @if($lic->expiration_date)
                                    <p class="text-xs text-gray-500 mt-1">Діє до: {{ $lic->expiration_date }}</p>
                                @else
                                    <p class="text-xs text-gray-600 mt-1">Безстрокова</p>
                                @endif
                            </div>
                            <div>
                                <x-ui.badge status="{{ $lic->license_status }}" :dot="false" />
                            </div>
                        </x-table.mobile-card>
                    @empty
                        <x-table.mobile-empty>Немає встановленого ліцензійного ПЗ</x-table.mobile-empty>
                    @endforelse
                </x-table.mobile-list>
            </x-ui.tab-content>

            <x-ui.tab-content name="lowValueMaterials">
                <x-table.mobile-list>
                    @forelse($viewEquipment->lowValueMaterials as $m)
                        <x-table.mobile-card>
                            <div>
                                <x-ui.text-block 
                                    title="{{ $m->material->material_name ?? 'Невідомо' }}" 
                                    subtitle="{{ $m->brand_model }}" 
                                    subtitleClass="text-xs text-brand-400"
                                />
                                <p class="text-xs text-gray-500 mt-1">
                                    S/N: {{ $m->serial_number ?? '—' }} 
                                    @if($m->nomenclature_number) <span class="text-gray-600">|</span> Ном: {{ $m->nomenclature_number }} @endif
                                </p>
                                @if($m->notes)
                                    <p class="text-xs text-gray-400 italic mt-1">{{ $m->notes }}</p>
                                @endif
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold text-gray-200">{{ $m->quantity }} шт.</p>
                                <x-table.cell-subtext>{{ $m->status }}</x-table.cell-subtext>
                            </div>
                        </x-table.mobile-card>
                    @empty
                        <x-table.mobile-empty>Немає закріплених малоцінних матеріалів (МШП)</x-table.mobile-empty>
                    @endforelse
                </x-table.mobile-list>
            </x-ui.tab-content>

            <x-ui.tab-content name="complaints">
                <x-table.mobile-list>
                    @forelse($viewEquipment->complaints as $comp)
                        <x-table.mobile-card layout="none">
                            <x-table.mobile-card-header align="start" class="mb-2">
                                <x-ui.text-block title="{{ $comp->description }}" />
                                <x-ui.badge status="{{ $comp->resolution_status }}" :dot="false" />
                            </x-table.mobile-card-header>
                            <x-table.cell-subtext>Дата: {{ $comp->created_at ?? 'Невідомо' }}</x-table.cell-subtext>
                        </x-table.mobile-card>
                    @empty
                        <x-table.mobile-empty>Скарг не знайдено</x-table.mobile-empty>
                    @endforelse
                </x-table.mobile-list>
            </x-ui.tab-content>

            <x-ui.tab-content name="maintenance">
                <x-table.mobile-list>
                    @forelse($viewEquipment->maintenanceLogs as $log)
                        <x-table.mobile-card>
                            <div>
                                <x-ui.text-block 
                                    title="{{ $log->performed_by }}" 
                                    subtitle="{{ $log->cost ? $log->cost . ' грн' : 'Безкоштовно' }}" 
                                    subtitleClass="text-sm text-brand-400"
                                />
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-400 mb-1">{{ $log->maintenance_date }}</p>
                                <x-ui.badge status="{{ $log->status }}" :dot="false" />
                            </div>
                        </x-table.mobile-card>
                    @empty
                        <x-table.mobile-empty>Журнал ТО порожній</x-table.mobile-empty>
                    @endforelse
                </x-table.mobile-list>
            </x-ui.tab-content>
        </x-ui.tabs>
    </x-ui.slide-over>
    @endif
</x-ui.page-wrapper>
