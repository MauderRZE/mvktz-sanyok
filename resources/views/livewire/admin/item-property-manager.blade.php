<div>
<x-ui.page-wrapper>
    <x-ui.flash />
    <x-ui.toolbar :count="count($properties)" label="Всього властивостей" buttonLabel="Додати властивість" />

    @if($isOpen)
    <x-ui.modal title="{{ $propertyId ? 'Редагувати' : 'Додати' }} властивість" maxWidth="md">
        <x-form.select label="Атрибут (Характеристика)" model="attribute_id" :options="$dictAttributes->pluck('name', 'id')->toArray()" />
        
        <x-form.input label="Значення (наприклад: 16GB, Intel Core i5)" model="attr_value" type="text" />
        
        <div class="mt-4 p-4 border border-white/10 rounded-lg bg-white/5 space-y-4">
            <div class="text-sm text-gray-400">Прив'яжіть до активу АБО до матеріалу:</div>
            <x-form.select label="Прив'язка до Обладнання (Assets)" model="asset_id" :options="['' => 'Не вибрано'] + $assets->mapWithKeys(function($item) {
                return [$item->id => $item->componentType->component_name . ' (Inv: ' . ($item->inventory_number ?? 'Немає') . ')'];
            })->toArray()" />
            
            <x-form.select label="Прив'язка до Матеріалу (МШП)" model="nomenclature_id" :options="['' => 'Не вибрано'] + $materials->mapWithKeys(function($item) {
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
