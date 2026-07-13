<div>
<x-ui.page-wrapper>
    <x-ui.flash />
    
    <livewire:admin.equipment.equipment-move-modal />

    {{-- Header & Buttons --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <p class="text-sm text-gray-500">Всього записів: <span class="text-gray-300 font-medium">{{ $assets->total() }}</span></p>
        </div>
        <div class="flex gap-2" x-data="{
            showPrintModal: false,
            orientation: 'landscape',
            columns: ['equipment', 'component_type', 'model_sn', 'network', 'location', 'status'],
            getReportUrl() {
                const params = new URLSearchParams();
                if ($wire.search) params.append('search', $wire.search);
                const arrays = {
                    filterStatus: $wire.filterStatus,
                    filterBaseComponent: $wire.filterBaseComponent,
                    filterLocation: $wire.filterLocation,
                    filterHolder: $wire.filterHolder,
                    filterModel: $wire.filterModel,
                    filterNetwork: $wire.filterNetwork,
                    filterCategory: $wire.filterCategory,
                };
                for (const [key, values] of Object.entries(arrays)) {
                    if (Array.isArray(values)) {
                        values.forEach(v => params.append(key + '[]', v));
                    }
                }
                params.append('orientation', this.orientation);
                this.columns.forEach(c => params.append('columns[]', c));
                
                return '/admin/assets/report?' + params.toString();
            }
        }">
            <button @click="showPrintModal = true" class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium transition-colors rounded-xl bg-surface-800 text-white hover:bg-surface-700 border border-white/10">
                🖨 Друк звіту
            </button>
            <x-ui.button wire:click="create()">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Додати
            </x-ui.button>

            <!-- Modal -->
            <template x-teleport="body">
                <div x-show="showPrintModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" style="display: none;">
                    <div @click.away="showPrintModal = false" class="relative w-full max-w-sm bg-surface-800 border border-white/5 rounded-2xl shadow-2xl p-6 space-y-4">
                        <h3 class="text-lg font-semibold text-white">Налаштування звіту</h3>
                        
                        <div>
                            <label class="block text-xs font-medium text-gray-400 mb-2">Орієнтація сторінки</label>
                            <div class="flex gap-4">
                                <label class="flex items-center gap-2 text-sm text-gray-300">
                                    <input type="radio" x-model="orientation" value="landscape" class="text-brand-500 bg-surface-900 border-white/10"> Альбомна
                                </label>
                                <label class="flex items-center gap-2 text-sm text-gray-300">
                                    <input type="radio" x-model="orientation" value="portrait" class="text-brand-500 bg-surface-900 border-white/10"> Книжкова
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-400 mb-2">Стовпчики звіту</label>
                            <div class="space-y-2">
                                <template x-for="(label, key) in {'id':'ID', 'equipment':'Пристрій (Інв. №)', 'component_type':'Тип компонента', 'model_sn':'Модель / S/N', 'network':'Мережа', 'location':'Місце / Власник', 'status':'Статус'}">
                                    <label class="flex items-center gap-2 text-sm text-gray-300">
                                        <input type="checkbox" x-model="columns" :value="key" class="rounded text-brand-500 bg-surface-900 border-white/10">
                                        <span x-text="label"></span>
                                    </label>
                                </template>
                            </div>
                        </div>

                        <div class="pt-4 flex justify-end gap-2">
                            <button @click="showPrintModal = false" class="px-4 py-2 text-sm text-gray-400 hover:text-white">Скасувати</button>
                            <a :href="getReportUrl()" target="_blank" @click="showPrintModal = false" class="px-4 py-2 text-sm font-medium text-white bg-brand-600 rounded-xl hover:bg-brand-500">Сформувати</a>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>

    {{-- Filters Bar --}}
    <x-ui.card class="p-4 space-y-4">
        <div class="flex flex-col gap-4">
            <div class="w-full flex gap-2">
                <div class="flex-1">
                    <x-form.search wire:model.live.debounce.300ms="search" placeholder="Пошук по всіх полях (серійний номер, IP, модель, кабінет, ПІБ, обладнання...)" />
                </div>
                @if($search !== '' || !empty($filterStatus) || !empty($filterCategory) || !empty($filterBaseComponent) || !empty($filterModel) || !empty($filterLocation) || !empty($filterHolder) || !empty($filterNetwork))
                    <button wire:click="resetFilters" class="px-4 py-2 bg-white/5 hover:bg-white/10 text-xs text-gray-400 hover:text-white rounded-xl border border-white/10 transition-colors shrink-0 flex items-center gap-1.5" title="Скинути всі фільтри">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        <span>Скинути</span>
                    </button>
                @endif
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-7 gap-3 w-full">
                <x-form.multi-select label="Категорія" :selectedCount="count($filterCategory)">
                    @foreach($categoriesList as $cat)
                        <label class="flex items-center gap-2 text-xs text-gray-300 hover:text-white cursor-pointer py-1">
                            <input type="checkbox" value="{{ $cat->id }}" wire:model.live="filterCategory" class="rounded border-white/10 bg-surface-900 text-brand-500 focus:ring-0 focus:ring-offset-0">
                            <span>{{ $cat->category_name }}</span>
                        </label>
                    @endforeach
                </x-form.multi-select>

                <x-form.multi-select label="Тип компонента" :selectedCount="count($filterBaseComponent)">
                    @foreach($baseComponentsList as $bc)
                        <label class="flex items-center gap-2 text-xs text-gray-300 hover:text-white cursor-pointer py-1">
                            <input type="checkbox" value="{{ $bc->id }}" wire:model.live="filterBaseComponent" class="rounded border-white/10 bg-surface-900 text-brand-500 focus:ring-0 focus:ring-offset-0">
                            <span>{{ $bc->component_name }}</span>
                        </label>
                    @endforeach
                </x-form.multi-select>

                <x-form.multi-select label="Моделі" :selectedCount="count($filterModel)">
                    @foreach($modelsList as $m)
                        <label class="flex items-center gap-2 text-xs text-gray-300 hover:text-white cursor-pointer py-1">
                            <input type="checkbox" value="{{ $m->id }}" wire:model.live="filterModel" class="rounded border-white/10 bg-surface-900 text-brand-500 focus:ring-0 focus:ring-offset-0">
                            <span>{{ trim(($m->brand->brandtz_name ?? '') . ' ' . $m->model_name) }}</span>
                        </label>
                    @endforeach
                </x-form.multi-select>

                <x-form.multi-select label="Локації" :selectedCount="count($filterLocation)">
                    @foreach($locationsList as $loc)
                        <label class="flex items-center gap-2 text-xs text-gray-300 hover:text-white cursor-pointer py-1">
                            <input type="checkbox" value="{{ $loc->id }}" wire:model.live="filterLocation" class="rounded border-white/10 bg-surface-900 text-brand-500 focus:ring-0 focus:ring-offset-0">
                            <span>Каб. {{ $loc->room_number }}</span>
                        </label>
                    @endforeach
                </x-form.multi-select>

                <x-form.multi-select label="Власники" :selectedCount="count($filterHolder)">
                    @foreach($holdersList as $h)
                        <label class="flex items-center gap-2 text-xs text-gray-300 hover:text-white cursor-pointer py-1">
                            <input type="checkbox" value="{{ $h->id }}" wire:model.live="filterHolder" class="rounded border-white/10 bg-surface-900 text-brand-500 focus:ring-0 focus:ring-offset-0">
                            <span class="truncate" title="{{ $h->employee ? $h->employee->last_name . ' ' . mb_substr($h->employee->first_name, 0, 1) . '.' . ($h->organization ? ' (' . $h->organization->org_name . ')' : '') : ($h->organization->org_name ?? 'Невідомий') }}">
                                {{ $h->employee ? $h->employee->last_name . ' ' . mb_substr($h->employee->first_name, 0, 1) . '.' : ($h->organization->org_name ?? 'Невідомий') }}
                            </span>
                        </label>
                    @endforeach
                </x-form.multi-select>

                <x-form.multi-select label="Мережа" :selectedCount="count($filterNetwork)">
                    <label class="flex items-center gap-2 text-xs text-gray-300 hover:text-white cursor-pointer py-1">
                        <input type="checkbox" value="1" wire:model.live="filterNetwork" class="rounded border-white/10 bg-surface-900 text-brand-500 focus:ring-0 focus:ring-offset-0">
                        <span>Є мережа</span>
                    </label>
                    <label class="flex items-center gap-2 text-xs text-gray-300 hover:text-white cursor-pointer py-1">
                        <input type="checkbox" value="0" wire:model.live="filterNetwork" class="rounded border-white/10 bg-surface-900 text-brand-500 focus:ring-0 focus:ring-offset-0">
                        <span>Немає мережі</span>
                    </label>
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
    <div wire:key="asset-modal-wrapper">
    <x-ui.modal wire:key="asset-modal" title="{{ $form->assetId ? 'Редагувати' : 'Додати' }} актив" maxWidth="2xl">
        @php
            $eqOptions = $equipmentList->mapWithKeys(fn($eq) => [$eq->id => $eq->inv_number . ' - ' . $eq->account_name])->toArray();
            $bcOptions = $baseComponentsList->mapWithKeys(fn($bc) => [$bc->id => $bc->component_name])->toArray();
            $modOptions = $modelsList->mapWithKeys(fn($m) => [$m->id => trim(($m->brand->brandtz_name ?? '') . ' ' . $m->model_name)])->toArray();
            $paOptions = $parentAssetsList->mapWithKeys(fn($pa) => [
                $pa->id => '#' . $pa->id . ' - ' . ($pa->componentType->component_name ?? '') . ($pa->equipment?->inv_number ? ' [Інв. №' . $pa->equipment->inv_number . ']' : '') . ' (' . ($pa->serial_number ?: 'без S/N') . ')'
            ])->toArray();
            $locOptions = $locationsList->mapWithKeys(fn($loc) => [$loc->id => 'Каб. ' . $loc->room_number])->toArray();
            $nomOptions = $nomenclaturesList->mapWithKeys(fn($nom) => [$nom->id => $nom->material_account_name . ' (' . $nom->nomenklature_number . ')'])->toArray();
            $actOptions = $writeOffActsList->mapWithKeys(fn($act) => [$act->id => 'Акт №' . $act->act_number])->toArray();
            
            $holdersOptions = $holdersList->mapWithKeys(function($holder) {
                $empName = $holder->employee ? $holder->employee->last_name . ' ' . mb_substr($holder->employee->first_name, 0, 1) . '.' : null;
                $orgName = $holder->organization->org_name ?? null;
                if ($empName && $orgName) $dn = $empName . ' (' . $orgName . ')';
                elseif ($empName) $dn = $empName;
                elseif ($orgName) $dn = $orgName;
                else $dn = 'Невідомий утримувач';
                return [$holder->id => $dn];
            })->toArray();

            $isSystemUnit = false;
            if ($form->base_component_id) {
                $selectedComponent = $baseComponentsList->firstWhere('id', $form->base_component_id);
                if ($selectedComponent && mb_strtolower($selectedComponent->component_name) === 'системний блок') {
                    $isSystemUnit = true;
                }
            }
        @endphp
        <div class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-end">
                <div>
                    <x-form.select label="Обладнання (ПК / Пристрій)" model="form.equipment_id" placeholder="Оберіть обладнання..." :options="$eqOptions" />
                </div>
                <div>
                    <x-form.select label="Базовий компонент" model="form.base_component_id" placeholder="Оберіть компонент..." :live="true" :options="$bcOptions" />
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-end">
                <div>
                    <x-form.select label="Модель" model="form.model_id" placeholder="Оберіть модель..." :options="$modOptions" :nullable="true" />
                </div>
                <div>
                    @if($isSystemUnit)
                        <div class="opacity-50 pointer-events-none" title="Системний блок не може мати батьківського активу">
                            <x-form.select label="Батьківський актив" model="form.parent_asset_id" placeholder="Не застосовується" :options="[]" />
                        </div>
                    @else
                        <x-form.select label="Батьківський актив" model="form.parent_asset_id" placeholder="Немає (Основний актив)" :options="$paOptions" :nullable="true" />
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-end">
                <div>
                    <x-form.input label="Серійний номер" model="form.serial_number" type="text" />
                </div>
                <div>
                    <x-form.input label="Додаткові примітки" model="form.notes" type="text" placeholder="напр. розширено пам'ять" />
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-end">
                <div>
                    <x-form.select label="Локація (Кабінет)" model="form.current_loc_id" placeholder="Не задано..." :options="$locOptions" :nullable="true" />
                </div>
                <div>
                    <x-form.select label="Відповідальний" model="form.current_holder_id" placeholder="Не задано..." :options="$holdersOptions" :nullable="true" />
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-end">
                <div>
                    <x-form.select label="МШП (Малоцінка)" model="form.nomenclature_id" placeholder="Не прив'язано..." :options="$nomOptions" :nullable="true" />
                </div>
                <div>
                    <x-form.select label="Акт списання" model="form.write_off_act_id" placeholder="Не списано..." :options="$actOptions" :nullable="true" />
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 items-end">
                <div>
                    <x-form.select label="Стан роботи" model="form.status">
                        <option value="Працює">Працює</option>
                        <option value="Потребує уваги">Потребує уваги</option>
                        <option value="В ремонті">В ремонті</option>
                        <option value="Списано">Списано</option>
                    </x-form.select>
                </div>
            </div>

                <div class="border-t border-white/5 pt-4">
                    <x-form.checkbox label="Мережевий пристрій / інтерфейс" model="form.has_network" :live="true" />

                    @if($form->has_network)
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end mt-4 fade-in">
                        <div>
                            <x-form.input label="Hostname" model="form.hostname" type="text" placeholder="SRV-01" />
                        </div>
                        <div>
                            <x-form.input label="IP-Адреса" model="form.ip_address" type="text" placeholder="192.168.1.50" />
                        </div>
                        <div>
                            <x-form.input label="MAC-Адреса" model="form.mac_address" type="text" placeholder="AA:BB:CC:DD:EE:FF" />
                        </div>
                    </div>
                    @endif
                </div>
        </div>
        </x-ui.modal>
    </div>
    @endif

    {{-- Desktop --}}
    <x-table.wrapper>
            <x-slot name="headers">
                    <x-table.th width="40"></x-table.th>
                    <x-table.th align="left" wire:click="sortBy('equipment')" class="cursor-pointer hover:bg-white/5">
                        <div class="flex items-center gap-1">Пристрій (Інв. №) @if($sortField === 'equipment') <span class="text-brand-400">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif</div>
                    </x-table.th>
                    <x-table.th align="left" wire:click="sortBy('component_type')" class="cursor-pointer hover:bg-white/5">
                        <div class="flex items-center gap-1">Тип компонента @if($sortField === 'component_type') <span class="text-brand-400">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif</div>
                    </x-table.th>
                    <x-table.th align="left">
                        <div class="flex items-center gap-1">Модель / S/N</div>
                    </x-table.th>
                    <x-table.th align="left" wire:click="sortBy('ip_address')" class="cursor-pointer hover:bg-white/5">
                        <div class="flex items-center gap-1">Мережа @if($sortField === 'ip_address') <span class="text-brand-400">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif</div>
                    </x-table.th>
                    <x-table.th align="left" wire:click="sortBy('location')" class="cursor-pointer hover:bg-white/5">
                        <div class="flex items-center gap-1">Місце / Власник @if($sortField === 'location') <span class="text-brand-400">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif</div>
                    </x-table.th>
                    <x-table.th align="left" wire:click="sortBy('status')" class="cursor-pointer hover:bg-white/5">
                        <div class="flex items-center gap-1">Статус @if($sortField === 'status') <span class="text-brand-400">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif</div>
                    </x-table.th>
                    <x-table.th align="right" width="24">Дії</x-table.th>
                </x-slot>
            
                @forelse($assets as $c)
                <x-table.tr class="{{ in_array($c->id, $expandedRows) ? 'bg-surface-900/50' : '' }}">
                    <x-table.td align="center">
                        <button wire:click="toggleRow({{ $c->id }})" class="w-6 h-6 flex items-center justify-center rounded hover:bg-white/10 transition-colors text-gray-400 hover:text-white relative">
                            <svg class="w-4 h-4 transition-transform duration-200 {{ in_array($c->id, $expandedRows) ? 'rotate-90' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            @if($c->childAssets && $c->childAssets->count() > 0)
                                <span class="absolute -top-1 -right-2 bg-brand-500/20 text-brand-300 px-1 py-0.5 rounded text-[8px] font-bold">{{ $c->childAssets->count() }}</span>
                            @endif
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
                        <x-ui.action-buttons id="{{ $c->id }}" :viewAction="true" moveAction="$dispatch('openMoveAsset', { id: {{ $c->id }} })" />
                    </x-table.td>
                </x-table.tr>

                @if(in_array($c->id, $expandedRows))
                <tr class="bg-surface-900/30 border-b border-white/5">
                    <td colspan="8" class="px-4 py-3">
                        @if($c->childAssets && $c->childAssets->count() > 0)
                            <div class="mb-4">
                                <span class="text-gray-500 block mb-2 text-xs uppercase tracking-wider">📦 Вкладені компоненти ({{ $c->childAssets->count() }})</span>
                                <div class="bg-surface-950 rounded-lg border border-white/5 overflow-hidden">
                                    <table class="w-full text-xs text-left">
                                        <tbody class="divide-y divide-white/5">
                                            @foreach($c->childAssets as $child)
                                                <tr class="hover:bg-white/5 transition-colors">
                                                    <td class="py-2 px-3 w-8 text-center text-gray-500">└─</td>
                                                    <td class="py-2 px-3 font-medium text-brand-300">
                                                        {{ $child->componentType->component_name ?? '-' }}
                                                    </td>
                                                    <td class="py-2 px-3">
                                                        <div class="text-white">{{ $child->model->brand->brandtz_name ?? '' }} {{ $child->model->model_name ?? '' }}</div>
                                                        @if($child->serial_number)
                                                            <div class="text-gray-500 font-mono text-[10px]">SN: {{ $child->serial_number }}</div>
                                                        @endif
                                                    </td>
                                                    <td class="py-2 px-3 text-gray-400">
                                                        @if($child->location) Каб. {{ $child->location->room_number }}<br> @endif
                                                        @if($child->holder)
                                                            @php
                                                                $empName = $child->holder->employee ? $child->holder->employee->last_name . ' ' . mb_substr($child->holder->employee->first_name, 0, 1) . '.' : null;
                                                                $orgName = $child->holder->organization->org_name ?? null;
                                                                echo $empName ? $empName . ($orgName ? " ($orgName)" : '') : ($orgName ?? '');
                                                            @endphp
                                                        @endif
                                                    </td>
                                                    <td class="py-2 px-3">
                                                        @if($child->status !== 'Працює')
                                                            <x-ui.badge status="{{ $child->status }}" :dot="false" />
                                                        @endif
                                                    </td>
                                                    <td class="py-2 px-3 text-right">
                                                        <button wire:click="edit({{ $child->id }})" class="p-1 text-gray-400 hover:text-brand-400 transition-colors" title="Редагувати компонент">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
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
                <x-ui.action-buttons id="{{ $c->id }}" :viewAction="true" moveAction="$dispatch('openMoveAsset', { id: {{ $c->id }} })" />
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

    @if($isViewOpen && $viewAsset)
    <x-ui.slide-over title="Деталі активу #{{ $viewAsset->id }}" maxWidth="md">
        <div class="space-y-6">
            <div>
                <h3 class="text-xl font-bold text-white mb-1">{{ $viewAsset->componentType->component_name ?? 'Актив' }}</h3>
                <p class="text-brand-400">{{ $viewAsset->model->brand->brandtz_name ?? '' }} {{ $viewAsset->model->model_name ?? '' }}</p>
                <div class="flex flex-wrap items-center gap-2 mt-3">
                    <x-ui.badge status="{{ $viewAsset->status }}" />
                    @if($viewAsset->serial_number)
                        <span class="px-2 py-1 rounded-md bg-white/5 border border-white/10 text-xs font-mono text-gray-300">SN: {{ $viewAsset->serial_number }}</span>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 text-sm">
                @if($viewAsset->equipment)
                <div class="bg-surface-950 p-3 rounded-xl border border-white/5">
                    <span class="text-gray-500 text-xs uppercase tracking-wider block mb-1">🖥 Зв'язок з Обладнанням</span>
                    <div class="text-gray-300">{{ $viewAsset->equipment->account_name }}</div>
                    <div class="text-gray-500 font-mono text-xs mt-0.5">Інв. №: {{ $viewAsset->equipment->inv_number ?? '-' }}</div>
                </div>
                @endif

                @if($viewAsset->location || $viewAsset->holder)
                <div class="bg-surface-950 p-3 rounded-xl border border-white/5">
                    <span class="text-gray-500 text-xs uppercase tracking-wider block mb-1">📍 Розміщення та Відповідальний</span>
                    @if($viewAsset->location)
                        <div class="text-brand-300 mb-1">Каб. {{ $viewAsset->location->room_number }}</div>
                    @endif
                    @if($viewAsset->holder)
                        <div class="text-gray-300">
                            @php
                                $empName = $viewAsset->holder->employee ? $viewAsset->holder->employee->last_name . ' ' . mb_substr($viewAsset->holder->employee->first_name, 0, 1) . '.' : null;
                                $orgName = $viewAsset->holder->organization->org_name ?? null;
                                echo $empName ? $empName . ($orgName ? " ($orgName)" : '') : ($orgName ?? 'Невідомий утримувач');
                            @endphp
                        </div>
                    @endif
                </div>
                @endif

                @if(!empty($viewAsset->ip_address) || !empty($viewAsset->mac_address) || !empty($viewAsset->hostname))
                <div class="bg-surface-950 p-3 rounded-xl border border-white/5">
                    <span class="text-gray-500 text-xs uppercase tracking-wider block mb-1">🌐 Мережеві налаштування</span>
                    @if($viewAsset->hostname)
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-gray-500 text-xs">Hostname:</span>
                            <span class="text-gray-300">{{ $viewAsset->hostname }}</span>
                        </div>
                    @endif
                    @if($viewAsset->ip_address)
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-gray-500 text-xs">IP:</span>
                            <span class="text-brand-400 font-mono text-xs">{{ $viewAsset->ip_address }}</span>
                        </div>
                    @endif
                    @if($viewAsset->mac_address)
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500 text-xs">MAC:</span>
                            <span class="text-gray-400 font-mono text-xs">{{ $viewAsset->mac_address }}</span>
                        </div>
                    @endif
                </div>
                @endif

                @if($viewAsset->lowValueMaterial)
                <div class="bg-surface-950 p-3 rounded-xl border border-white/5">
                    <span class="text-gray-500 text-xs uppercase tracking-wider block mb-1">📦 Облік МБП</span>
                    <div class="text-gray-300">{{ $viewAsset->lowValueMaterial->material_account_name }}</div>
                    <div class="text-gray-500 font-mono text-xs mt-0.5">{{ $viewAsset->lowValueMaterial->nomenklature_number }}</div>
                </div>
                @endif

                @if($viewAsset->writeOffAct)
                <div class="bg-surface-950 p-3 rounded-xl border border-white/5">
                    <span class="text-gray-500 text-xs uppercase tracking-wider block mb-1">📋 Акт списання</span>
                    <div class="text-gray-300">Акт №{{ $viewAsset->writeOffAct->act_number }}</div>
                </div>
                @endif

                @if($viewAsset->parentAsset)
                <div class="bg-surface-950 p-3 rounded-xl border border-white/5">
                    <span class="text-gray-500 text-xs uppercase tracking-wider block mb-1">🔗 Батьківський актив</span>
                    <div class="text-brand-400">#{{ $viewAsset->parentAsset->id }} — {{ $viewAsset->parentAsset->componentType->component_name ?? 'Актив' }}</div>
                    @if($viewAsset->parentAsset->serial_number)
                        <div class="text-gray-500 font-mono text-xs mt-0.5">SN: {{ $viewAsset->parentAsset->serial_number }}</div>
                    @endif
                </div>
                @endif

                @if($viewAsset->notes)
                <div class="bg-surface-950 p-3 rounded-xl border border-white/5">
                    <span class="text-gray-500 text-xs uppercase tracking-wider block mb-1">📝 Примітки</span>
                    <div class="text-gray-300 whitespace-pre-wrap">{{ $viewAsset->notes }}</div>
                </div>
                @endif
            </div>

            <div class="mt-6 pt-4 border-t border-white/10 flex justify-end">
                <button wire:click="closeView" class="px-4 py-2 text-sm text-gray-400 hover:text-white bg-white/5 hover:bg-white/10 rounded-xl transition-colors">
                    Закрити
                </button>
            </div>
        </div>
    </x-ui.slide-over>
    @endif
</x-ui.page-wrapper>
</div>
