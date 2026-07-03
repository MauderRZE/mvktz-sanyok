<div>
<x-ui.page-wrapper>
    <x-ui.flash />


    <x-ui.toolbar :count="count($requirements)" label="Всього правил" buttonLabel="Додати шаблон комплекту" />

    @if($isOpen)
    <x-ui.modal title="{{ $isEditMode ? 'Редагувати' : 'Додати' }} вимогу комплекту" maxWidth="md">
            <div>
                    <x-form.select label="Тип техніки (напр. Комп'ютер)" model="equipment_type_id">
                            <option value="">Оберіть тип техніки...</option>
                        @foreach($typesList as $t)
                            <option value="{{ $t->id }}">{{ $t->type_name }}</option>
                        @endforeach
                        </x-form.select>
                </div>
                <div>
                    <x-form.select label="Необхідний компонент (напр. Монітор)" model="component_id">
                            <option value="">Оберіть базовий компонент...</option>
                        @foreach($componentsList as $c)
                            <option value="{{ $c->id }}">{{ $c->component_name }}</option>
                        @endforeach
                        </x-form.select>
                </div>
        </x-ui.modal>
    @endif

    {{-- Desktop --}}
    <x-table.wrapper>
            <x-slot name="headers">
                    <x-table.th align="left">Тип техніки</x-table.th>
                    <x-table.th align="left">Обов'язковий компонент комплектації</x-table.th>
                    <x-table.th align="right" width="32">Дії</x-table.th>
                </x-slot>
            
                @forelse($requirements as $r)
                <x-table.tr>
                    <x-table.td align="left" primary>{{ $r->equipmentType->type_name ?? '-' }}</x-table.td>
                    <x-table.td align="left">{{ $r->component->component_name ?? '-' }}</x-table.td>
                    <x-table.td align="right">
                        <x-ui.action-buttons editAction="edit({{ $r->equipment_type_id }}, {{ $r->component_id }})" deleteAction="delete({{ $r->equipment_type_id }}, {{ $r->component_id }})" />
                    </x-table.td>
                </x-table.tr>
                @empty
                <x-table.empty colspan="3" />
                @endforelse
            
        </x-table.wrapper>

    {{-- Mobile --}}
    <x-table.mobile-list>
        @forelse($requirements as $r)
        <x-table.mobile-card>
            <x-ui.text-block 
                title="{{ $r->equipmentType->type_name ?? '-' }}" 
                subtitle="Потребує: {{ $r->component->component_name ?? '-' }}" 
                subtitleClass="text-xs text-brand-400" 
            />
            <x-ui.action-buttons editAction="edit({{ $r->equipment_type_id }}, {{ $r->component_id }})" deleteAction="delete({{ $r->equipment_type_id }}, {{ $r->component_id }})" />
        </x-table.mobile-card>
        @empty
        <x-table.mobile-empty />
        @endforelse
    </x-table.mobile-list>
</x-ui.page-wrapper>
</div>
