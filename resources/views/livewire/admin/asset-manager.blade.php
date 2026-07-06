<div>
<x-ui.page-wrapper>
    <x-ui.flash />
    
    {{-- Header & Buttons --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <p class="text-sm text-gray-500">Всього записів: <span class="text-gray-300 font-medium">{{ $assets->total() }}</span></p>
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
                    <x-form.search wire:model.live.debounce.300ms="search" placeholder="Пошук за серійним номером, IP/MAC, примітками або інв. номером обладнання..." />
                </div>
                @if($search !== '' || !empty($filterStatus) || !empty($filterBaseComponent))
                    <button wire:click="resetFilters" class="px-4 py-2 bg-white/5 hover:bg-white/10 text-xs text-gray-400 hover:text-white rounded-xl border border-white/10 transition-colors shrink-0 flex items-center gap-1.5" title="Скинути всі фільтри">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        <span>Скинути</span>
                    </button>
                @endif
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 w-full lg:w-auto">
                <x-form.multi-select label="Базові компоненти" :selectedCount="count($filterBaseComponent)">
                    @foreach($baseComponentsList as $bc)
                        <label class="flex items-center gap-2 text-xs text-gray-300 hover:text-white cursor-pointer py-1">
                            <input type="checkbox" value="{{ $bc->id }}" wire:model.live="filterBaseComponent" class="rounded border-white/10 bg-surface-900 text-brand-500 focus:ring-0 focus:ring-offset-0">
                            <span>{{ $bc->component_name }}</span>
                        </label>
                    @endforeach
                </x-form.multi-select>

                <x-form.multi-select label="Статуси" :selectedCount="count($filterStatus)">
                    @foreach(['Працює', 'Знято', 'Зламано', 'В ремонті', 'Потребує уваги', 'Списано'] as $st)
                        <label class="flex items-center gap-2 text-xs text-gray-300 hover:text-white cursor-pointer py-1">
                            <input type="checkbox" value="{{ $st }}" wire:model.live="filterStatus" class="rounded border-white/10 bg-surface-900 text-brand-500 focus:ring-0 focus:ring-offset-0">
                            <span>{{ $st }}</span>
                        </label>
                    @endforeach
                </x-form.multi-select>
            </div>
        </div>
    </x-ui.card>

    @if($isOpen)
    <x-ui.modal title="{{ $assetId ? 'Редагувати' : 'Додати' }} актив" maxWidth="2xl">
        <div class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-end">
                    <div>
                        <x-form.select label="Обладнання (ПК / Пристрій)" model="equipment_id">
                            <option value="">Оберіть обладнання...</option>
                            @foreach($equipmentList as $eq)
                                <option value="{{ $eq->id }}">{{ $eq->inv_number }} - {{ $eq->account_name }}</option>
                            @endforeach
                        </x-form.select>
                    </div>
                    <div>
                        <x-form.select label="Базовий компонент" model="base_component_id">
                            <option value="">Оберіть компонент...</option>
                            @foreach($baseComponentsList as $bc)
                                <option value="{{ $bc->id }}">{{ $bc->component_name }}</option>
                            @endforeach
                        </x-form.select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-end">
                    <div>
                        <x-form.select label="Модель" model="model_id">
                            <option value="">Оберіть модель...</option>
                            @foreach($modelsList as $m)
                                <option value="{{ $m->id }}">{{ $m->brand->brandtz_name ?? '' }} {{ $m->model_name }}</option>
                            @endforeach
                        </x-form.select>
                    </div>
                    <div>
                        <x-form.select label="Батьківський актив" model="parent_asset_id">
                            <option value="">Немає (Основний актив)</option>
                            @foreach($parentAssetsList as $pa)
                                <option value="{{ $pa->id }}">#{{ $pa->id }} - {{ $pa->componentType->component_name ?? '' }} ({{ $pa->serial_number ?: 'без S/N' }})</option>
                            @endforeach
                        </x-form.select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-end">
                    <div>
                        <x-form.input label="Серійний номер" model="serial_number" type="text" />
                    </div>
                    <div>
                        <x-form.input label="Додаткові примітки" model="notes" type="text" placeholder="напр. розширено пам'ять" />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-end">
                    <div>
                        <x-form.select label="Локація (Кабінет)" model="current_loc_id">
                            <option value="">Не задано...</option>
                            @foreach($locationsList as $loc)
                                <option value="{{ $loc->id }}">Каб. {{ $loc->room_number }}</option>
                            @endforeach
                        </x-form.select>
                    </div>
                    <div>
                        <x-form.select label="Відповідальний" model="current_holder_id">
                            <option value="">Не задано...</option>
                            @foreach($holdersList as $holder)
                                @php
                                    $empName = $holder->employee ? $holder->employee->last_name . ' ' . mb_substr($holder->employee->first_name, 0, 1) . '.' : null;
                                    $orgName = $holder->organization->org_name ?? null;
                                    
                                    if ($empName && $orgName) {
                                        $displayName = $empName . ' (' . $orgName . ')';
                                    } elseif ($empName) {
                                        $displayName = $empName;
                                    } elseif ($orgName) {
                                        $displayName = $orgName;
                                    } else {
                                        $displayName = 'Невідомий утримувач';
                                    }
                                @endphp
                                <option value="{{ $holder->id }}">{{ $displayName }}</option>
                            @endforeach
                        </x-form.select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-end">
                    <div>
                        <x-form.select label="МШП (Малоцінка)" model="nomenclature_id">
                            <option value="">Не прив'язано...</option>
                            @foreach($nomenclaturesList as $nom)
                                <option value="{{ $nom->id }}">{{ $nom->material_account_name }} ({{ $nom->nomenklature_number }})</option>
                            @endforeach
                        </x-form.select>
                    </div>
                    <div>
                        <x-form.select label="Акт списання" model="write_off_act_id">
                            <option value="">Не списано...</option>
                            @foreach($writeOffActsList as $act)
                                <option value="{{ $act->id }}">Акт №{{ $act->act_number }}</option>
                            @endforeach
                        </x-form.select>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 items-end">
                    <div>
                        <x-form.select label="Стан роботи" model="status">
                            <option value="Працює">Працює</option>
                            <option value="Потребує уваги">Потребує уваги</option>
                            <option value="В ремонті">В ремонті</option>
                            <option value="Списано">Списано</option>
                        </x-form.select>
                    </div>
                </div>

                <div class="border-t border-white/5 pt-4">
                    <x-form.checkbox label="Мережевий пристрій / інтерфейс" model="has_network" :live="true" />

                    @if($has_network)
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end mt-4 fade-in">
                        <div>
                            <x-form.input label="Hostname" model="hostname" type="text" placeholder="SRV-01" />
                        </div>
                        <div>
                            <x-form.input label="IP-Адреса" model="ip_address" type="text" placeholder="192.168.1.50" />
                        </div>
                        <div>
                            <x-form.input label="MAC-Адреса" model="mac_address" type="text" placeholder="AA:BB:CC:DD:EE:FF" />
                        </div>
                    </div>
                    @endif
                </div>
        </div>
        </x-ui.modal>
    @endif

    {{-- Desktop --}}
    <x-table.wrapper>
            <x-slot name="headers">
                    <x-table.th width="40"></x-table.th>
                    <x-table.th align="left">Пристрій (Інв. №)</x-table.th>
                    <x-table.th align="left">Тип компонента</x-table.th>
                    <x-table.th align="left" wire:click="sortBy('serial_number')" class="cursor-pointer hover:bg-white/5">
                        <div class="flex items-center gap-1">Модель / S/N @if($sortField === 'serial_number') <span class="text-brand-400">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif</div>
                    </x-table.th>
                    <x-table.th align="left" wire:click="sortBy('ip_address')" class="cursor-pointer hover:bg-white/5">
                        <div class="flex items-center gap-1">Мережа @if($sortField === 'ip_address') <span class="text-brand-400">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif</div>
                    </x-table.th>
                    <x-table.th align="left">Місце / Власник</x-table.th>
                    <x-table.th align="left" wire:click="sortBy('status')" class="cursor-pointer hover:bg-white/5">
                        <div class="flex items-center gap-1">Статус @if($sortField === 'status') <span class="text-brand-400">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif</div>
                    </x-table.th>
                    <x-table.th align="right" width="24">Дії</x-table.th>
                </x-slot>
            
                @forelse($assets as $c)
                <x-table.tr class="{{ in_array($c->id, $expandedRows) ? 'bg-surface-900/50' : '' }}">
                    <x-table.td align="center">
                        <button wire:click="toggleRow({{ $c->id }})" class="w-6 h-6 flex items-center justify-center rounded hover:bg-white/10 transition-colors text-gray-400 hover:text-white">
                            <svg class="w-4 h-4 transition-transform duration-200 {{ in_array($c->id, $expandedRows) ? 'rotate-90' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </x-table.td>
                    <x-table.td align="left" primary>
                        {{ $c->equipment->inv_number ?? '-' }}
                        <x-table.cell-subtext>{{ $c->equipment->account_name ?? '' }}</x-table.cell-subtext>
                    </x-table.td>
                    <x-table.td align="left">{{ $c->componentType->component_name ?? '-' }}</x-table.td>
                    <x-table.td align="left">
                        @if($c->model)
                            <div class="font-medium">{{ $c->model->brand->brandtz_name ?? '' }} {{ $c->model->model_name }}</div>
                        @else
                            -
                        @endif
                        @if($c->serial_number)
                            <x-table.cell-subtext class="font-mono">SN: {{ $c->serial_number }}</x-table.cell-subtext>
                        @endif
                    </x-table.td>
                    <x-table.td align="left" class="text-gray-400">
                        @if(!empty($c->ip_address) || !empty($c->mac_address) || !empty($c->hostname))
                            @if(!empty($c->hostname))
                                <span class="text-xs text-white block">{{ $c->hostname }}</span>
                            @endif
                            <span class="text-xs text-brand-400 block font-mono">{{ $c->ip_address }}</span>
                            @if(!empty($c->mac_address))
                                <x-table.cell-subtext class="font-mono">{{ $c->mac_address }}</x-table.cell-subtext>
                            @endif
                        @else
                            <span class="text-gray-600">-</span>
                        @endif
                    </x-table.td>
                    <x-table.td align="left">
                        @if($c->location)
                            <div class="text-sm text-white">Каб. {{ $c->location->room_number }}</div>
                        @endif
                        @if($c->holder)
                            <x-table.cell-subtext>
                            @php
                                $empName = $c->holder->employee ? $c->holder->employee->last_name . ' ' . mb_substr($c->holder->employee->first_name, 0, 1) . '.' : null;
                                $orgName = $c->holder->organization->org_name ?? null;
                                echo $empName ? $empName . ($orgName ? " ($orgName)" : '') : ($orgName ?? 'Невідомий');
                            @endphp
                            </x-table.cell-subtext>
                        @endif
                        @if(!$c->location && !$c->holder)
                            <span class="text-gray-600">-</span>
                        @endif
                    </x-table.td>
                    <x-table.td align="left">
                        <x-ui.badge status="{{ $c->status }}" />
                    </x-table.td>
                    <x-table.td align="right">
                        <x-ui.action-buttons id="{{ $c->id }}" />
                    </x-table.td>
                </x-table.tr>

                @if(in_array($c->id, $expandedRows))
                <tr class="bg-surface-900/30 border-b border-white/5">
                    <td colspan="8" class="px-4 py-3">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-xs">
                            <div>
                                <span class="text-gray-500 block mb-1 uppercase tracking-wider">📦 Облік МБП</span>
                                @if($c->lowValueMaterial)
                                    <div class="text-gray-300">{{ $c->lowValueMaterial->material_account_name }}</div>
                                    <div class="text-gray-500 font-mono">{{ $c->lowValueMaterial->nomenklature_number }}</div>
                                @else
                                    <span class="text-gray-600">-</span>
                                @endif
                            </div>
                            <div>
                                <span class="text-gray-500 block mb-1 uppercase tracking-wider">📋 Акт списання</span>
                                @if($c->writeOffAct)
                                    <div class="text-gray-300">Акт №{{ $c->writeOffAct->act_number }}</div>
                                @else
                                    <span class="text-gray-600">-</span>
                                @endif
                            </div>
                            <div>
                                <span class="text-gray-500 block mb-1 uppercase tracking-wider">🔗 Батьківський актив</span>
                                @if($c->parentAsset)
                                    <div class="text-brand-400">#{{ $c->parentAsset->id }} — {{ $c->parentAsset->componentType->component_name ?? 'Актив' }}</div>
                                    @if($c->parentAsset->serial_number)
                                        <div class="text-gray-500 font-mono">SN: {{ $c->parentAsset->serial_number }}</div>
                                    @endif
                                @else
                                    <span class="text-gray-600">-</span>
                                @endif
                            </div>
                            <div>
                                <span class="text-gray-500 block mb-1 uppercase tracking-wider">📝 Примітки (ID: {{ $c->id }})</span>
                                <div class="text-gray-300">{{ $c->notes ?: '-' }}</div>
                            </div>
                        </div>
                    </td>
                </tr>
                @endif
                @empty
                <x-table.empty colspan="8" />
                @endforelse
            
        </x-table.wrapper>

    {{-- Mobile --}}
    <x-table.mobile-list>
        @forelse($assets as $c)
        <x-table.mobile-card layout="y-2">
            <x-table.mobile-card-header align="start">
                <div>
                    <span class="text-xs text-brand-400 font-semibold uppercase tracking-wider block mb-1">{{ $c->componentType->component_name ?? '-' }}</span>
                    <x-ui.text-block 
                        title="{{ $c->model ? (($c->model->brand->brandtz_name ?? '') . ' ' . $c->model->model_name) : ($c->notes ?? '-') }}" 
                        subtitle="Пристрій: {{ $c->equipment->inv_number ?? '-' }}" 
                    />
                </div>
                <x-ui.action-buttons id="{{ $c->id }}" />
            </x-table.mobile-card-header>
            
            <x-table.mobile-card-footer class="grid grid-cols-2 gap-2 font-mono">
                <div>
                    <x-table.cell-subtext>Серійний:</x-table.cell-subtext>
                    {{ $c->serial_number ?: '-' }}
                </div>
                @if(!empty($c->ip_address) || !empty($c->mac_address) || !empty($c->hostname))
                <div>
                    <x-table.cell-subtext>Мережа:</x-table.cell-subtext>
                    @if($c->hostname) <div>{{ $c->hostname }}</div> @endif
                    {{ $c->ip_address }}<br><span class="text-[9px]">{{ $c->mac_address }}</span>
                </div>
                @endif
            </x-table.mobile-card-footer>

            <x-table.mobile-card-footer class="grid grid-cols-2 gap-2">
                <div>
                    <x-table.cell-subtext>Місце / Власник:</x-table.cell-subtext>
                    <div class="text-xs">
                        @if($c->location) Каб. {{ $c->location->room_number }}<br> @endif
                        @if($c->holder)
                            @php
                                $empName = $c->holder->employee ? mb_substr($c->holder->employee->first_name, 0, 1) . '. ' . $c->holder->employee->last_name : null;
                                $orgName = $c->holder->organization->org_name ?? null;
                                echo $empName ? $empName . ($orgName ? " ($orgName)" : '') : ($orgName ?? 'Невідомий');
                            @endphp
                        @endif
                        @if(!$c->location && !$c->holder) - @endif
                    </div>
                </div>
                <div>
                    <x-table.cell-subtext>Деталі:</x-table.cell-subtext>
                    <div class="text-xs">
                        @if($c->parentAsset) 🔗 Батьківський: #{{ $c->parentAsset->id }}<br> @endif
                        @if($c->lowValueMaterial) 📦 МБП: {{ $c->lowValueMaterial->nomenklature_number }}<br> @endif
                        @if($c->writeOffAct) 📋 Списано: Акт №{{ $c->writeOffAct->act_number }}<br> @endif
                        @if($c->notes) 📝 {{ mb_strimwidth($c->notes, 0, 30, '...') }} @endif
                    </div>
                </div>
            </x-table.mobile-card-footer>

            <x-table.mobile-card-footer flex="true">
                <span class="text-xs text-gray-500">Статус:</span>
                <x-ui.badge status="{{ $c->status }}" :dot="false" />
            </x-table.mobile-card-footer>
        </x-table.mobile-card>
        @empty
        <x-table.mobile-empty />
        @endforelse
    </x-table.mobile-list>

    <div class="mt-4">
        {{ $assets->links() }}
    </div>
</x-ui.page-wrapper>
</div>
