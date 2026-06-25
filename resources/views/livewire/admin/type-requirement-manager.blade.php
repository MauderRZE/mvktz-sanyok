<div class="space-y-6">
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
                    <x-table.td align="left" primary class="text-white font-medium">{{ $r->equipmentType->type_name ?? '-' }}</x-table.td>
                    <x-table.td align="left" class="text-gray-300">{{ $r->component->component_name ?? '-' }}</x-table.td>
                    <x-table.td align="right">
                        <x-ui.action-buttons editAction="edit({{ $r->equipment_type_id }}, {{ $r->component_id }})" deleteAction="delete({{ $r->equipment_type_id }}, {{ $r->component_id }})" />
                    </x-table.td>
                </x-table.tr>
                @empty
                <x-table.tr><x-table.td colspan="3" class="px-5 py-10 text-center text-gray-600">Немає записів</x-table.td></x-table.tr>
                @endforelse
            
        </x-table.wrapper>

    {{-- Mobile --}}
    <x-table.mobile-list>
        @forelse($requirements as $r)
        <x-table.mobile-card>
            <div>
                <p class="text-sm text-white font-medium">{{ $r->equipmentType->type_name ?? '-' }}</p>
                <p class="text-xs text-brand-400">Потребує: {{ $r->component->component_name ?? '-' }}</p>
            </div>
            <div class="flex gap-1 shrink-0">
                <button wire:click="edit({{ $r->equipment_type_id }}, {{ $r->component_id }})" class="p-2 rounded-lg text-gray-500 hover:text-brand-400 hover:bg-brand-500/10 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                <button wire:click="delete({{ $r->equipment_type_id }}, {{ $r->component_id }})" class="p-2 rounded-lg text-gray-500 hover:text-red-400 hover:bg-red-500/10 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
            </div>
        </x-table.mobile-card>
        @empty
        <div class="text-center py-10 text-gray-600 text-sm">Немає записів</div>
        @endforelse
    </x-table.mobile-list>
</div>
