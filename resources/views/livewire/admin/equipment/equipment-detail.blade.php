<div>
@if($isOpen && $equipment)
<x-ui.slide-over title="Деталі обладнання: {{ $equipment->inv_number }}" maxWidth="4xl">
    {{-- Заголовок --}}
    <div class="mb-6">
        <h3 class="text-2xl font-bold text-white mb-1">{{ $equipment->account_name }}</h3>
        <div class="flex flex-wrap items-center gap-3 mt-2">
            <span class="px-3 py-1 rounded-lg bg-brand-500/10 text-brand-300 text-sm font-medium">
                {{ $equipment->components->first()?->componentType?->component_name ?? 'Обладнання' }}
            </span>
            <x-ui.badge status="{{ $equipment->status }}" />
            @if($equipment->buy_price)
                <span class="px-3 py-1 rounded-lg bg-gray-800 text-gray-300 text-sm font-medium">
                    Ціна: {{ number_format($equipment->buy_price, 2) }} грн
                </span>
            @endif
            @if($equipment->purchase_id && $equipment->contract)
                <span class="px-3 py-1 rounded-lg bg-blue-500/10 text-blue-300 text-sm font-medium">
                    Договір: № {{ $equipment->contract->contract_number ?? $equipment->contract->id }}
                    @if($equipment->contract->supplier)
                        <span class="text-blue-400/70">({{ $equipment->contract->supplier->supplier_name ?? '' }})</span>
                    @endif
                </span>
            @endif
            @if($equipment->retirement_act_id && $equipment->retirementAct)
                <span class="px-3 py-1 rounded-lg bg-red-500/10 text-red-300 text-sm font-medium">
                    Списано за актом № {{ $equipment->retirementAct->act_number ?? $equipment->retirementAct->id }}
                </span>
            @endif
        </div>
        @if($equipment->notes)
            <p class="mt-4 text-sm text-gray-400 bg-surface-800/50 p-3 rounded-xl border border-white/5">
                {{ $equipment->notes }}
            </p>
        @endif
    </div>

    {{-- Вкладки --}}
    <x-ui.tabs active="components">
        <x-slot name="nav">
            <x-ui.tab-nav name="components" label="Комплектуючі" icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" /></svg>' />
            <x-ui.tab-nav name="movements" label="Рух" icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>' />
            <x-ui.tab-nav name="licenses" label="Ліцензії ПЗ" icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>' />
            <x-ui.tab-nav name="lowValueMaterials" label="Матеріали / МШП" icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>' />
            <x-ui.tab-nav name="complaints" label="Скарги" icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>' />
            <x-ui.tab-nav name="maintenance" label="ТО та ремонти" icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>' />
        </x-slot>

        {{-- Комплектуючі --}}
        <x-ui.tab-content name="components">
            <x-table.mobile-list>
                @forelse($equipment->components as $comp)
                    <x-table.mobile-card>
                        <div>
                            <x-ui.text-block
                                title="{{ $comp->componentType->component_name ?? 'Невідомо' }}"
                                subtitle="{{ $comp->brand_model }} | s/n: {{ $comp->serial_number ?? '—' }}"
                            />
                            @if($comp->location)
                                <p class="text-xs text-gray-500 mt-1">📍 Каб. {{ $comp->location->room_number }}</p>
                            @endif
                            @if($comp->holder?->organization)
                                <p class="text-xs text-gray-500">🏢 {{ $comp->holder->organization->org_name }}</p>
                            @endif
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

        {{-- Рух --}}
        <x-ui.tab-content name="movements">
            <x-table.mobile-list>
                @forelse($equipment->movements->sortByDesc('move_date') as $mov)
                    <x-table.mobile-card>
                        <div>
                            <x-ui.text-block
                                title="Каб. {{ $mov->location->room_number ?? 'Невідомо' }}"
                                subtitle="Відповідальний: {{ $mov->employee?->last_name ?? '—' }} {{ $mov->employee?->first_name ?? '' }}"
                            />
                            @if($mov->employee?->departmentRelationship)
                                <p class="text-xs text-gray-500 mt-1">
                                    🏬 {{ $mov->employee->departmentRelationship->name }}
                                </p>
                            @endif
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

        {{-- Ліцензії --}}
        <x-ui.tab-content name="licenses">
            <x-table.mobile-list>
                @forelse($equipment->softwareLicenses as $lic)
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

        {{-- МШП --}}
        <x-ui.tab-content name="lowValueMaterials">
            <x-table.mobile-list>
                @forelse($equipment->lowValueMaterials as $m)
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

        {{-- Скарги --}}
        <x-ui.tab-content name="complaints">
            <x-table.mobile-list>
                @forelse($equipment->complaints as $comp)
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

        {{-- ТО та ремонти --}}
        <x-ui.tab-content name="maintenance">
            <x-table.mobile-list>
                @forelse($equipment->maintenanceLogs as $log)
                    <x-table.mobile-card>
                        <div>
                            <x-ui.text-block
                                title="{{ $log->performed_by }}"
                                subtitle="{{ $log->cost ? number_format($log->cost, 2) . ' грн' : 'Безкоштовно' }}"
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

    <div class="mt-6 pt-4 border-t border-white/10 flex justify-end">
        <button wire:click="close" class="px-4 py-2 text-sm text-gray-400 hover:text-white bg-white/5 hover:bg-white/10 rounded-xl transition-colors">
            Закрити
        </button>
    </div>
</x-ui.slide-over>
@endif
</div>
