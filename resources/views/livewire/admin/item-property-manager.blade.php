<div>
<x-ui.page-wrapper>
    <x-ui.flash />
    <x-ui.toolbar :count="count($properties)" label="Всього властивостей" buttonLabel="Додати властивість" />

    {{-- Filters Bar --}}
    <x-ui.card class="p-4 mb-4 space-y-4">
        <div class="flex flex-col lg:flex-row gap-4 items-center">
            <div class="flex-1 w-full flex gap-2">
                <div class="flex-1">
                    <x-form.search wire:model.live.debounce.300ms="search" placeholder="Пошук за назвою характеристики, значенням або обладнанням/матеріалом..." />
                </div>
                @if($search !== '' || !empty($filterAttribute))
                    <button wire:click="resetFilters" class="px-4 py-2 bg-white/5 hover:bg-white/10 text-xs text-gray-400 hover:text-white rounded-xl border border-white/10 transition-colors shrink-0 flex items-center gap-1.5" title="Скинути всі фільтри">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        <span>Скинути</span>
                    </button>
                @endif
            </div>
            <div class="w-full lg:w-auto">
                <x-form.multi-select label="Характеристика" :selectedCount="count($filterAttribute)">
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
        <x-form.select label="Атрибут (Характеристика)" model="form.attribute_id" :options="['' => 'Не вибрано'] + $dictAttributes->pluck('name', 'id')->toArray()" />
        
        <x-form.input label="Значення (наприклад: 16GB, Intel Core i5)" model="form.attr_value" type="text" />
        
        <div class="mt-4 p-4 border border-white/10 rounded-lg bg-white/5 space-y-4">
            <div class="text-sm text-gray-400">Прив'яжіть до активу АБО до матеріалу:</div>
            <x-form.select label="Прив'язка до Обладнання (Assets)" model="form.asset_id" :options="['' => 'Не вибрано'] + $assets->mapWithKeys(function($item) {
                return [$item->id => $item->componentType->component_name . ' (Inv: ' . ($item->inv_number ?? 'Немає') . ')'];
            })->toArray()" />
            
            <x-form.select label="Прив'язка до Матеріалу (МШП)" model="form.nomenclature_id" :options="['' => 'Не вибрано'] + $materials->mapWithKeys(function($item) {
                return [$item->id => $item->material_account_name];
            })->toArray()" />
        </div>
    </x-ui.modal>
    @endif

    {{-- Desktop --}}
    <x-table.wrapper>
        <x-slot name="headers">
            <x-table.th align="left" width="20">ID</x-table.th>
            <x-table.th align="left">Прив'язка</x-table.th>
            <x-table.th align="left">Атрибут</x-table.th>
            <x-table.th align="left">Значення</x-table.th>
            <x-table.th align="right" width="32">Дії</x-table.th>
        </x-slot>

        @forelse($properties as $prop)
        <x-table.tr>
            <x-table.td align="left" muted>#{{ $prop->id }}</x-table.td>
            <x-table.td align="left" primary>
                @if($prop->asset_id)
                    <span class="text-brand-400">Обладнання:</span> {{ $prop->asset->componentType->component_name ?? 'N/A' }}
                @elseif($prop->nomenclature_id)
                    <span class="text-orange-400">Матеріал:</span> {{ $prop->nomenclature->material_account_name ?? 'N/A' }}
                @else
                    <span class="text-gray-500">Не прив'язано</span>
                @endif
            </x-table.td>
            <x-table.td align="left" primary>{{ $prop->attribute->name ?? 'N/A' }}</x-table.td>
            <x-table.td align="left">{{ $prop->attr_value }}</x-table.td>
            <x-table.td align="right">
                <x-ui.action-buttons id="{{ $prop->id }}" />
            </x-table.td>
        </x-table.tr>
        @empty
        <x-table.empty colspan="5" />
        @endforelse
    </x-table.wrapper>

    {{-- Mobile --}}
    <x-table.mobile-list>
        @forelse($properties as $prop)
        <x-table.mobile-card>
            <x-ui.text-block 
                title="{{ $prop->attribute->name ?? 'N/A' }}: {{ $prop->attr_value }}" 
                subtitle="{{ $prop->asset_id ? 'Обладнання' : ($prop->nomenclature_id ? 'МШП' : 'Немає') }}" 
            />
            <x-ui.action-buttons id="{{ $prop->id }}" />
        </x-table.mobile-card>
        @empty
        <x-table.mobile-empty />
        @endforelse
    </x-table.mobile-list>
</x-ui.page-wrapper>
</div>
