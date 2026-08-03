<div>
<x-ui.page-wrapper>
    <x-ui.flash />
    <x-ui.toolbar :count="$properties->total()" label="Всього властивостей" buttonLabel="Додати властивість" />

    {{-- Filters Bar --}}
    <x-ui.card class="p-4 mb-4 space-y-4">
        <div class="flex flex-col md:flex-row gap-4 items-center">
            <div class="flex-1 w-full flex gap-2">
                <div class="flex-1">
                    <x-form.search wire:model.live.debounce.300ms="search" placeholder="Пошук за значенням, атрибутом, інвентарним номером чи номенклатурою..." />
                </div>
                @if($search !== '' || !empty($filterAttribute))
                    <button wire:click="resetFilters" class="px-4 py-2 bg-white/5 hover:bg-white/10 text-xs text-gray-400 hover:text-white rounded-xl border border-white/10 transition-colors shrink-0 flex items-center gap-1.5" title="Скинути всі фільтри">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        <span>Скинути</span>
                    </button>
                @endif
            </div>
            <div class="grid grid-cols-1 gap-3 w-full md:w-64">
                <x-form.multi-select label="Атрибути" :selectedCount="count($filterAttribute)">
                    <label class="flex items-center gap-2 text-xs text-gray-300 hover:text-white cursor-pointer py-1">
                        <input type="checkbox" value="null" wire:model.live="filterAttribute" class="rounded border-white/10 bg-surface-900 text-brand-500 focus:ring-0 focus:ring-offset-0">
                        <span>Без атрибута</span>
                    </label>
                    @foreach($dictAttributes as $attr)
                        <label class="flex items-center gap-2 text-xs text-gray-300 hover:text-white cursor-pointer py-1">
                            <input type="checkbox" value="{{ $attr->id }}" wire:model.live="filterAttribute" class="rounded border-white/10 bg-surface-900 text-brand-500 focus:ring-0 focus:ring-offset-0">
                            <span>{{ $attr->name }}</span>
                        </label>
                    @endforeach
                </x-form.multi-select>
            </div>
        </div>
    </x-ui.card>

    @if($isOpen)
        <x-ui.modal title="{{ $form->propertyId ? 'Редагувати' : 'Додати' }} властивість" maxWidth="md">
            <div>
                <x-form.select label="Атрибут" model="form.attribute_id">
                    <option value="">Оберіть атрибут...</option>
                    @foreach($dictAttributes as $attr)
                        <option value="{{ $attr->id }}">{{ $attr->name }}</option>
                    @endforeach
                </x-form.select>
            </div>

            <div>
                <x-form.input label="Значення властивості" model="form.attr_value" type="text" placeholder="Введіть значення..." />
            </div>

            <div>
                <x-form.select label="Прив'язка до об'єкта (Обладнання)" model="form.asset_id">
                    <option value="">Без прив'язки</option>
                    @foreach($assets as $asset)
                        <option value="{{ $asset->id }}">
                            {{ $asset->componentType->component_name ?? 'Об\'єкт' }} 
                            {{ $asset->equipment ? '(Інв.№ ' . $asset->equipment->inv_number . ')' : '' }}
                        </option>
                    @endforeach
                </x-form.select>
            </div>

            <div>
                <x-form.select label="Прив'язка до номенклатури (МШП)" model="form.nomenclature_id">
                    <option value="">Без прив'язки</option>
                    @foreach($materials as $mat)
                        <option value="{{ $mat->id }}">{{ $mat->material_account_name }}</option>
                    @endforeach
                </x-form.select>
            </div>
        </x-ui.modal>
    @endif

    {{-- Desktop Table --}}
    <x-table.wrapper>
        <x-slot name="headers">
            <x-table.th align="left" width="20" class="cursor-pointer" wire:click="sortBy('id')">
                ID @if($sortField === 'id') {{ $sortDirection === 'asc' ? '↑' : '↓' }} @endif
            </x-table.th>
            <x-table.th align="left">Атрибут</x-table.th>
            <x-table.th align="left">Значення</x-table.th>
            <x-table.th align="left" class="cursor-pointer" wire:click="sortBy('inv_number')">
                Інв. номер @if($sortField === 'inv_number') {{ $sortDirection === 'asc' ? '↑' : '↓' }} @endif
            </x-table.th>
            <x-table.th align="left">Об'єкт / Номенклатура</x-table.th>
            <x-table.th align="right" width="32">Дії</x-table.th>
        </x-slot>

        @forelse($properties as $prop)
            <x-table.tr>
                <x-table.td align="left" muted>#{{ $prop->id }}</x-table.td>
                <x-table.td align="left" primary>{{ $prop->attribute->name ?? '—' }}</x-table.td>
                <x-table.td align="left">{{ $prop->attr_value ?? '—' }}</x-table.td>
                <x-table.td align="left">
                    @if($prop->asset && $prop->asset->equipment)
                        <span class="px-2.5 py-1 rounded-lg bg-white/5 text-gray-300 text-xs font-mono">{{ $prop->asset->equipment->inv_number }}</span>
                    @else
                        <span class="text-gray-500">—</span>
                    @endif
                </x-table.td>
                <x-table.td align="left">
                    @if($prop->asset)
                        <span class="px-2.5 py-1 rounded-lg bg-blue-500/10 text-blue-400 text-xs font-medium border border-blue-500/20">
                            {{ $prop->asset->componentType->component_name ?? 'Об\'єкт' }}
                        </span>
                    @elseif($prop->nomenclature)
                        <span class="px-2.5 py-1 rounded-lg bg-purple-500/10 text-purple-400 text-xs font-medium border border-purple-500/20">
                            {{ $prop->nomenclature->material_account_name }}
                        </span>
                    @else
                        <span class="text-gray-500">—</span>
                    @endif
                </x-table.td>
                <x-table.td align="right">
                    <x-ui.action-buttons id="{{ $prop->id }}" />
                </x-table.td>
            </x-table.tr>
        @empty
            <x-table.empty colspan="6" />
        @endforelse
    </x-table.wrapper>

    <div class="hidden md:block">
        {{ $properties->links() }}
    </div>

    {{-- Mobile List --}}
    <x-table.mobile-list>
        @forelse($properties as $prop)
            <x-table.mobile-card layout="gap-3">
                <div class="flex-1 space-y-1">
                    <div class="text-sm font-semibold text-white">
                        {{ $prop->attribute->name ?? 'Без атрибута' }}: <span class="text-brand-400">{{ $prop->attr_value }}</span>
                    </div>
                    <div class="text-xs text-gray-400">
                        @if($prop->asset)
                            Об'єкт: {{ $prop->asset->componentType->component_name ?? '—' }}
                            @if($prop->asset->equipment)
                                (Інв. №{{ $prop->asset->equipment->inv_number }})
                            @endif
                        @elseif($prop->nomenclature)
                            МШП: {{ $prop->nomenclature->material_account_name }}
                        @else
                            Не прив'язано
                        @endif
                    </div>
                </div>
                <x-ui.action-buttons id="{{ $prop->id }}" />
            </x-table.mobile-card>
        @empty
            <x-table.mobile-empty />
        @endforelse
    </x-table.mobile-list>
</x-ui.page-wrapper>
</div>