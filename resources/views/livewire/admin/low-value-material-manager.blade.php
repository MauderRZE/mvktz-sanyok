<div class="space-y-6">
    <x-ui.flash />
<x-ui.toolbar :count="count($materials)" label="Всього позицій" buttonLabel="Додати МШП" />

    @if($isOpen)
    <x-ui.modal title="{{ $materialId ? 'Редагувати' : 'Додати' }} МШП" maxWidth="lg">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <x-form.select label="Назва матеріалу (базовий)" model="base_material_id">
                            <option value="">Оберіть матеріал...</option>
                            @foreach($baseMaterialsList as $bm)
                                <option value="{{ $bm->id }}">{{ $bm->material_name }}</option>
                            @endforeach
                        </x-form.select>
                    </div>
                    <div>
                        <x-form.input label="Кількість" model="quantity" type="number" />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <x-form.input label="Бренд / Модель" model="brand_model" type="text" />
                    </div>
                    <div>
                        <x-form.input label="Номенклатурний номер" model="nomenclature_number" type="text" />
                    </div>
                    <div>
                        <x-form.select label="Статус" model="status">
                            <option value="На складі">На складі</option>
                            <option value="Видано">Видано</option>
                            <option value="Списано">Списано</option>
                        </x-form.select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <x-form.select label="Прив'язане обладнання (якщо встановлено)" model="equipment_id">
                            <option value="">Не встановлено (на складі)...</option>
                            @foreach($equipmentList as $eq)
                                <option value="{{ $eq->id }}">{{ $eq->inventory_number }} - {{ $eq->accounting_name }}</option>
                            @endforeach
                        </x-form.select>
                    </div>
                    <div>
                        <x-form.select label="Договір закупівлі" model="contract_id">
                            <option value="">Оберіть договір...</option>
                            @foreach($contractsList as $c)
                                <option value="{{ $c->id }}">Договір №{{ $c->contract_number }} ({{ $c->contract_date }})</option>
                            @endforeach
                        </x-form.select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <x-form.input label="Серійний номер" model="serial_number" type="text" />
                    </div>
                    <div>
                        <x-form.input label="Дата закупівлі" model="purchase_date" type="date" />
                    </div>
                    <div>
                        <x-form.input label="Дата встановлення" model="installation_date" type="date" />
                    </div>
                </div>

                <div>
                    <x-form.textarea label="Примітки" model="notes" rows="2" />
                </div>
        </x-ui.modal>
    @endif

    {{-- Desktop --}}
    <x-table.wrapper>
            <x-slot name="headers">
                    <x-table.th align="left">Матеріал (МШП)</x-table.th>
                    <x-table.th align="left">Серійний</x-table.th>
                    <x-table.th align="left">К-сть</x-table.th>
                    <x-table.th align="left">Встановлено на</x-table.th>
                    <x-table.th align="left">Договір</x-table.th>
                    <x-table.th align="left">Дати</x-table.th>
                    <x-table.th align="right" width="24">Дії</x-table.th>
                </x-slot>
            
                @forelse($materials as $m)
                <x-table.tr>
                    <x-table.td align="left" primary class="text-white font-medium">
                        {{ $m->material->material_name ?? '-' }}
                        @if($m->brand_model)
                            <span class="block text-xs text-brand-400 font-normal">{{ $m->brand_model }}</span>
                        @endif
                        @if($m->notes)
                            <span class="block text-[10px] text-gray-500 italic">{{ $m->notes }}</span>
                        @endif
                    </x-table.td>
                    <x-table.td align="left" class="text-gray-400 font-mono text-xs">
                        <div>S/N: {{ $m->serial_number ?? '-' }}</div>
                        @if($m->nomenclature_number)
                            <div class="text-[10px] text-gray-500">Ном: {{ $m->nomenclature_number }}</div>
                        @endif
                    </x-table.td>
                    <x-table.td align="left" class="text-gray-300 font-semibold">
                        <div>{{ $m->quantity }} шт.</div>
                        <div class="text-[10px] text-gray-500 font-normal">{{ $m->status ?? 'На складі' }}</div>
                    </x-table.td>
                    <x-table.td align="left" class="text-gray-300">
                        @if($m->equipment)
                            <span class="text-brand-400">{{ $m->equipment->inventory_number }}</span>
                            <span class="block text-[10px] text-gray-500">{{ $m->equipment->accounting_name }}</span>
                        @else
                            <span class="text-gray-500 italic">На складі</span>
                        @endif
                    </x-table.td>
                    <x-table.td align="left" class="text-gray-400 text-xs">
                        @if($m->contract)
                            Договір №{{ $m->contract->contract_number }}
                            <span class="block text-[10px] text-gray-500">{{ $m->contract->contract_date }}</span>
                        @else
                            -
                        @endif
                    </x-table.td>
                    <x-table.td align="left" class="text-xs text-gray-400 whitespace-nowrap">
                        @if($m->purchase_date) <div>Придбано: {{ $m->purchase_date }}</div> @endif
                        @if($m->installation_date) <div>Встановлено: {{ $m->installation_date }}</div> @endif
                    </x-table.td>
                    <x-table.td align="right">
                        <x-ui.action-buttons id="{{ $m->id }}" />
                    </x-table.td>
                </x-table.tr>
                @empty
                <x-table.tr><x-table.td colspan="7" class="px-4 py-10 text-center text-gray-600">Немає записів</x-table.td></x-table.tr>
                @endforelse
            
        </x-table.wrapper>

    {{-- Mobile --}}
    <x-table.mobile-list>
        @forelse($materials as $m)
        <x-table.mobile-card layout="y-2">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-xs text-gray-500">Кількість: {{ $m->quantity }} шт. ({{ $m->status ?? 'На складі' }})</span>
                    <p class="text-sm text-white font-medium">
                        {{ $m->material->material_name ?? '-' }}
                        @if($m->brand_model)
                            <span class="text-brand-400 font-normal">({{ $m->brand_model }})</span>
                        @endif
                    </p>
                    @if($m->nomenclature_number || $m->serial_number)
                        <p class="text-[11px] text-gray-400 font-mono">
                            @if($m->serial_number) S/N: {{ $m->serial_number }} @endif
                            @if($m->nomenclature_number) Ном: {{ $m->nomenclature_number }} @endif
                        </p>
                    @endif
                    <p class="text-xs text-gray-400">
                        Розташування: 
                        @if($m->equipment)
                            Встановлено на {{ $m->equipment->inventory_number }}
                        @else
                            На складі
                        @endif
                    </p>
                </div>
                <div class="flex gap-1 shrink-0">
                    <button wire:click="edit({{ $m->id }})" class="p-2 rounded-lg text-gray-500 hover:text-brand-400 hover:bg-brand-500/10 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                    <button wire:click="delete({{ $m->id }})" class="p-2 rounded-lg text-gray-500 hover:text-red-400 hover:bg-red-500/10 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                </div>
            </div>
            @if($m->notes)
                <p class="text-xs text-gray-400 italic bg-surface-900/60 p-2 rounded-lg border border-white/5">{{ $m->notes }}</p>
            @endif
        </x-table.mobile-card>
        @empty
        <div class="text-center py-10 text-gray-600 text-sm">Немає записів</div>
        @endforelse
    </x-table.mobile-list>
</div>
