<div>
<x-ui.page-wrapper>
    <x-ui.flash />
    <x-ui.toolbar :count="count($software)" label="Всього ПЗ" buttonLabel="Додати ПЗ" />

    @if($isOpen)
    <x-ui.modal title="{{ $softwareId ? 'Редагувати' : 'Додати' }} запис про ПЗ" maxWidth="md">
        <x-form.select label="Комп'ютер (Обладнання)" model="computer_id" :options="$computers->mapWithKeys(function($item) {
            return [$item->id => $item->componentType->component_name . ' (Inv: ' . ($item->inv_number ?? 'Немає') . ')'];
        })->toArray()" />
        
        <x-form.input label="Назва ПЗ (Windows, Office...)" model="software_name" type="text" />
        <x-form.input label="Версія (22H2, 2019...)" model="version" type="text" />
        <x-form.checkbox label="Ліцензійне ПЗ?" model="is_licensed" />
        
        <x-form.select label="Прив'язка до ліцензії" model="license_id" :options="$licenses->pluck('license_name', 'id')->toArray()" />
    </x-ui.modal>
    @endif

    {{-- Desktop --}}
    <x-table.wrapper>
        <x-slot name="headers">
            <x-table.th align="left" width="20">ID</x-table.th>
            <x-table.th align="left">Комп'ютер</x-table.th>
            <x-table.th align="left">Назва ПЗ</x-table.th>
            <x-table.th align="left">Версія</x-table.th>
            <x-table.th align="center">Ліцензія</x-table.th>
            <x-table.th align="right" width="32">Дії</x-table.th>
        </x-slot>

        @forelse($software as $sw)
        <x-table.tr>
            <x-table.td align="left" muted>#{{ $sw->id }}</x-table.td>
            <x-table.td align="left" primary>{{ $sw->computer->componentType->component_name ?? 'N/A' }}</x-table.td>
            <x-table.td align="left" primary>{{ $sw->software_name }}</x-table.td>
            <x-table.td align="left">{{ $sw->version }}</x-table.td>
            <x-table.td align="center">
                @if($sw->is_licensed)
                    <span class="px-2 py-1 text-xs font-medium bg-green-500/20 text-green-400 rounded-md">Так</span>
                    @if($sw->license)
                        <div class="text-xs text-gray-400 mt-1">{{ $sw->license->license_name }}</div>
                    @endif
                @else
                    <span class="px-2 py-1 text-xs font-medium bg-red-500/20 text-red-400 rounded-md">Ні</span>
                @endif
            </x-table.td>
            <x-table.td align="right">
                <x-ui.action-buttons id="{{ $sw->id }}" />
            </x-table.td>
        </x-table.tr>
        @empty
        <x-table.empty colspan="6" />
        @endforelse
    </x-table.wrapper>

    {{-- Mobile --}}
    <x-table.mobile-list>
        @forelse($software as $sw)
        <x-table.mobile-card>
            <x-ui.text-block 
                title="{{ $sw->software_name }} v{{ $sw->version }}" 
                subtitle="ПК: {{ $sw->computer->componentType->component_name ?? 'N/A' }}" 
            />
            <x-ui.action-buttons id="{{ $sw->id }}" />
        </x-table.mobile-card>
        @empty
        <x-table.mobile-empty />
        @endforelse
    </x-table.mobile-list>
</x-ui.page-wrapper>
</div>
