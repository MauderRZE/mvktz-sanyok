<div>
@if($isOpen && $equipment)
<x-ui.slide-over title="Деталі обладнання: {{ $equipment->inv_number }}" maxWidth="4xl">
    {{-- Заголовок --}}
    <div class="mb-6">
        <h3 class="text-2xl font-bold text-white mb-1">{{ $equipment->account_name }}</h3>
        <div class="flex flex-wrap items-center gap-3 mt-2">
            <span class="px-3 py-1 rounded-lg bg-brand-500/10 text-brand-300 text-sm font-medium">
                {{ $equipment->assets->first()?->componentType?->component_name ?? 'Обладнання' }}
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
    <x-ui.tabs active="assets">
        <x-slot name="nav">
            <x-ui.tab-nav name="assets" label="Активи" icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" /></svg>' />
            <x-ui.tab-nav name="components" label="Комплектуючі" icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>' />
            <x-ui.tab-nav name="movements" label="Рух" icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>' />
            <x-ui.tab-nav name="lowValueMaterials" label="Матеріали / МШП" icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>' />
            <x-ui.tab-nav name="maintenance" label="ТО та ремонти" icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>' />
        </x-slot>

        {{-- Активи --}}
        <x-ui.tab-content name="assets">
            <x-table.mobile-list>
                @forelse($equipment->assets as $comp)
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

        {{-- Комплектуючі --}}
        <x-ui.tab-content name="components">
            <div class="space-y-3">
                @forelse($equipment->assets as $asset)
                  @if(empty($asset->parent_asset_id))
                    <div class="mb-4 bg-surface-800/30 p-3 rounded-xl border border-white/5">
                        <div class="flex justify-between items-start mb-2 {{ $asset->childAssets->count() > 0 ? 'border-b border-white/10 pb-3' : '' }}">
                            <div>
                                <h4 class="text-lg font-medium text-white">
                                    {{ $asset->componentType->component_name ?? 'Апаратна одиниця' }}
                                    @if($asset->hostname)
                                        <span class="text-sm text-gray-400 font-normal ml-1">({{ $asset->hostname }})</span>
                                    @endif
                                </h4>
                                <p class="text-sm text-brand-300 mt-0.5">
                                    {{ $asset->model->brand->brandtz_name ?? '' }} {{ $asset->model->model_name ?? '' }}
                                </p>
                                @if($asset->serial_number)
                                    <p class="text-xs text-gray-500 mt-0.5">s/n: {{ $asset->serial_number }}</p>
                                @endif
                            </div>
                            <x-ui.badge status="{{ $asset->status }}" :dot="false" />
                        </div>
                        
                        @if($asset->childAssets->count() > 0)
                            <div class="mt-3 pl-2 border-l-2 border-white/10">
                                <div class="space-y-3">
                                    @foreach($asset->childAssets as $child)
                                        <div class="bg-surface-800/50 border border-white/5 rounded-xl p-4 flex items-center justify-between">
                                            <div>
                                                <x-ui.text-block
                                                    title="{{ $child->componentType->component_name ?? 'Деталь' }}"
                                                    subtitle="{{ $child->model->brand->brandtz_name ?? '' }} {{ $child->model->model_name ?? '' }} {{ $child->serial_number ? '| s/n: ' . $child->serial_number : '' }}"
                                                />
                                                @if($child->itemProperties->count() > 0)
                                                    <div class="mt-2 flex flex-wrap gap-1">
                                                        @foreach($child->itemProperties as $prop)
                                                            <span class="px-2 py-0.5 rounded-md bg-gray-800 text-gray-300 text-xs">
                                                                {{ $prop->attribute->attribute_name ?? 'Властивість' }}: {{ $prop->property_value }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <x-ui.badge status="{{ $child->status }}" :dot="false" />
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                  @endif
                @empty
                    <div class="text-center py-10 text-gray-600 text-sm">Немає прикріплених комплектуючих</div>
                @endforelse
            </div>
        </x-ui.tab-content>

        {{-- Рух --}}
        <x-ui.tab-content name="movements">
            <x-table.mobile-list>
                @forelse($equipment->movements->sortByDesc('action_date') as $mov)
                    <x-table.mobile-card>
                        <div>
                            <x-ui.text-block
                                title="{{ $mov->toHolder->organization->org_name ?? 'Організація не вказана' }}"
                                subtitle="Відповідальний: {{ $mov->employee?->last_name ?? '—' }} {{ $mov->employee?->first_name ?? '' }}"
                            />
                            @if($mov->employee?->department)
                                <p class="text-xs text-gray-500 mt-1">
                                    🏬 {{ $mov->employee->department->name }}
                                </p>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm text-gray-400">{{ $mov->action_date }}</p>
                        </div>
                    </x-table.mobile-card>
                @empty
                    <x-table.mobile-empty>Історія переміщень порожня</x-table.mobile-empty>
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
                                title="{{ $m->material_account_name }}"
                                subtitle="Договір: {{ $m->contract?->contract_number ?? '—' }}"
                                subtitleClass="text-xs text-brand-400"
                            />
                            <p class="text-xs text-gray-500 mt-1">
                                @if($m->nomenklature_number) Ном: {{ $m->nomenklature_number }} @endif
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-gray-200">{{ $m->count }} шт.</p>
                            @if($m->price)
                                <x-table.cell-subtext>{{ $m->price }} грн</x-table.cell-subtext>
                            @endif
                        </div>
                    </x-table.mobile-card>
                @empty
                    <x-table.mobile-empty>Немає закріплених малоцінних матеріалів (МШП)</x-table.mobile-empty>
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
                                title="{{ $log->issue_description }}"
                                subtitle="{{ $log->status }}"
                                subtitleClass="text-sm text-brand-400"
                            />
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-400 mb-1">Відпр: {{ $log->sent_date }}</p>
                            @if($log->return_date)
                                <p class="text-sm text-gray-500 mb-1">Поверн: {{ $log->return_date }}</p>
                            @endif
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
