<div>
<x-ui.page-wrapper>
    <x-ui.flash />
<x-ui.toolbar :count="count($movements)" label="Всього переміщень" buttonLabel="Перемістити техніку" />

    {{-- Filters Bar --}}
    <x-ui.card class="p-4 mb-4 space-y-4">
        <div class="flex flex-col lg:flex-row gap-4 items-center">
            <div class="flex-1 w-full flex gap-2">
                <div class="flex-1">
                    <x-form.search wire:model.live.debounce.300ms="search" placeholder="Пошук за інвентарним номером, описом, співробітником, кабінетом..." />
                </div>
                @if($search !== '' || !empty($filterEquipment) || !empty($filterLocation) || !empty($filterEmployee))
                    <button wire:click="resetFilters" class="px-4 py-2 bg-white/5 hover:bg-white/10 text-xs text-gray-400 hover:text-white rounded-xl border border-white/10 transition-colors shrink-0 flex items-center gap-1.5" title="Скинути всі фільтри">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        <span>Скинути</span>
                    </button>
                @endif
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 w-full lg:w-auto">
                <x-form.multi-select label="Актив" :selectedCount="count($filterAsset)">
                    @foreach($assetsList as $asset)
                        <label class="flex items-center gap-2 text-xs text-gray-300 hover:text-white cursor-pointer py-1">
                            <input type="checkbox" value="{{ $asset->id }}" wire:model.live="filterAsset" class="rounded border-white/10 bg-surface-900 text-brand-500 focus:ring-0 focus:ring-offset-0">
                            <span>{{ $asset->baseComponent->component_name ?? 'Актив' }} {{ $asset->model->model_name ?? '' }} (SN: {{ $asset->serial_number ?? '—' }})</span>
                        </label>
                    @endforeach
                </x-form.multi-select>

                <x-form.multi-select label="Кабінети" :selectedCount="count($filterLocation)">
                    @foreach($locationsList as $loc)
                        <label class="flex items-center gap-2 text-xs text-gray-300 hover:text-white cursor-pointer py-1">
                            <input type="checkbox" value="{{ $loc->id }}" wire:model.live="filterLocation" class="rounded border-white/10 bg-surface-900 text-brand-500 focus:ring-0 focus:ring-offset-0">
                            <span>Каб. {{ $loc->room_number }}</span>
                        </label>
                    @endforeach
                </x-form.multi-select>

                <x-form.multi-select label="Відповідальні" :selectedCount="count($filterEmployee)">
                    @foreach($employeesList as $emp)
                        <label class="flex items-center gap-2 text-xs text-gray-300 hover:text-white cursor-pointer py-1">
                            <input type="checkbox" value="{{ $emp->id }}" wire:model.live="filterEmployee" class="rounded border-white/10 bg-surface-900 text-brand-500 focus:ring-0 focus:ring-offset-0">
                            <span>{{ $emp->fullName }}</span>
                        </label>
                    @endforeach
                </x-form.multi-select>
            </div>
        </div>
    </x-ui.card>

    @if($isOpen)
    <x-ui.modal title="{{ $movementId ? 'Редагувати' : 'Зареєструвати' }} переміщення" maxWidth="md">
            <div>
                    <x-form.select label="Актив" model="asset_id">
                            <option value="">Оберіть актив...</option>
                        @foreach($assetsList as $asset)
                            <option value="{{ $asset->id }}">
                                {{ $asset->baseComponent->component_name ?? 'Актив' }} {{ $asset->model->model_name ?? '' }} 
                                (SN: {{ $asset->serial_number ?? '—' }}) 
                                {{ $asset->equipment ? '- Інв.№: ' . $asset->equipment->inv_number : '' }}
                            </option>
                        @endforeach
                        </x-form.select>
                </div>
                <div>
                    <x-form.select label="Куди переміщено (Кабінет / Локація)" model="location_id">
                            <option value="">Оберіть локацію...</option>
                        @foreach($locationsList as $loc)
                            <option value="{{ $loc->id }}">Кабінет {{ $loc->room_number }}</option>
                        @endforeach
                        </x-form.select>
                </div>
                <div>
                    <x-form.select label="Відповідальний співробітник" model="employee_id">
                            <option value="">Без відповідального (на склад)...</option>
                        @foreach($employeesList as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->fullName }} ({{ $emp->position }})</option>
                        @endforeach
                        </x-form.select>
                </div>
                <div>
                    <x-form.input label="Дата переміщення" model="action_date" type="date" />
                </div>
        </x-ui.modal>
    @endif

    {{-- Desktop --}}
    <x-table.wrapper>
            <x-slot name="headers">
                    <x-table.th align="left" width="28">Дата</x-table.th>
                    <x-table.th align="left">Актив (СН / Інв. №)</x-table.th>
                    <x-table.th align="left">Нове розташування</x-table.th>
                    <x-table.th align="left">Матеріально відповідальний</x-table.th>
                    <x-table.th align="right" width="32">Дії</x-table.th>
                </x-slot>
            
                @forelse($movements as $m)
                <x-table.tr>
                    <x-table.td align="left" class="text-gray-400 whitespace-nowrap">{{ $m->action_date }}</x-table.td>
                    <x-table.td align="left" primary>
                        {{ $m->asset->baseComponent->component_name ?? 'Актив' }} {{ $m->asset->model->model_name ?? '' }}
                        <x-table.cell-subtext>
                            SN: {{ $m->asset->serial_number ?? '—' }}
                            {{ $m->asset && $m->asset->equipment ? ' | Інв.№: ' . $m->asset->equipment->inv_number : '' }}
                        </x-table.cell-subtext>
                    </x-table.td>
                    <x-table.td align="left">
                        @if($m->asset && $m->asset->location)
                            Каб. {{ $m->asset->location->room_number }}
                            @if($m->toHolder && $m->toHolder->organization)
                                <span class="text-xs text-gray-500">({{ $m->toHolder->organization->org_name }})</span>
                            @endif
                        @elseif($m->toHolder && $m->toHolder->organization)
                            {{ $m->toHolder->organization->org_name }}
                        @else
                            —
                        @endif
                    </x-table.td>
                    <x-table.td align="left">{{ $m->employee->fullName ?? 'На складі (без відповідального)' }}</x-table.td>
                    <x-table.td align="right">
                        <x-ui.action-buttons id="{{ $m->id }}" />
                    </x-table.td>
                </x-table.tr>
                @empty
                <x-table.empty colspan="5" />
                @endforelse
            
        </x-table.wrapper>

    {{-- Mobile --}}
    <x-table.mobile-list>
        @forelse($movements as $m)
        <x-table.mobile-card layout="col">
            <x-table.mobile-card-header align="start">
                <div>
                    <x-table.cell-subtext>Дата: {{ $m->action_date }}</x-table.cell-subtext>
                    <x-ui.text-block 
                        title="Актив: {{ $m->asset->baseComponent->component_name ?? 'Актив' }} {{ $m->asset->model->model_name ?? '' }}" 
                        subtitle="Куди: {{ $m->asset && $m->asset->location ? 'Каб. ' . $m->asset->location->room_number : ($m->toHolder->organization->org_name ?? '—') }}" 
                        subtitleClass="text-xs text-brand-400 font-medium"
                    />
                </div>
                <x-ui.action-buttons id="{{ $m->id }}" />
            </x-table.mobile-card-header>
            <x-table.mobile-card-footer>
                Відповідальний: {{ $m->employee->fullName ?? 'На складі (без відповідального)' }}
            </x-table.mobile-card-footer>
        </x-table.mobile-card>
        @empty
        <x-table.mobile-empty />
        @endforelse
    </x-table.mobile-list>
</x-ui.page-wrapper>
</div>
