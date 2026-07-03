<div>
<x-ui.page-wrapper>
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
                    <x-table.td align="left" primary>
                        {{ $m->material->material_name ?? '-' }}
                        @if($m->brand_model)
                            <x-table.cell-accent class="block text-xs font-normal mt-0.5">{{ $m->brand_model }}</x-table.cell-accent>
                        @endif
                        @if($m->notes)
                            <x-table.cell-subtext class="italic">{{ $m->notes }}</x-table.cell-subtext>
                        @endif
                    </x-table.td>
                    <x-table.td align="left" class="text-gray-400 font-mono text-xs">
                        <div>S/N: {{ $m->serial_number ?? '-' }}</div>
                        @if($m->nomenclature_number)
                            <x-table.cell-subtext>Ном: {{ $m->nomenclature_number }}</x-table.cell-subtext>
                        @endif
                    </x-table.td>
                    <x-table.td align="left" class="text-gray-300 font-semibold">
                        <div>{{ $m->quantity }} шт.</div>
                        <x-table.cell-subtext class="font-normal">{{ $m->status ?? 'На складі' }}</x-table.cell-subtext>
                    </x-table.td>
                    <x-table.td align="left">
                        @if($m->equipment)
                            <x-table.cell-accent>{{ $m->equipment->inventory_number }}</x-table.cell-accent>
                            <x-table.cell-subtext>{{ $m->equipment->accounting_name }}</x-table.cell-subtext>
                        @else
                            <span class="text-gray-500 italic">На складі</span>
                        @endif
                    </x-table.td>
                    <x-table.td align="left" class="text-gray-400 text-xs">
                        @if($m->contract)
                            Договір №{{ $m->contract->contract_number }}
                            <x-table.cell-subtext>{{ $m->contract->contract_date }}</x-table.cell-subtext>
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
                <x-table.empty colspan="7" />
                @endforelse
            
        </x-table.wrapper>

    {{-- Mobile --}}
    <x-table.mobile-list>
        @forelse($materials as $m)
        <x-table.mobile-card layout="y-2">
            <x-table.mobile-card-header align="start">
                <div>
                    <span class="text-xs text-gray-500">Кількість: {{ $m->quantity }} шт. ({{ $m->status ?? 'На складі' }})</span>
                    <x-ui.text-block 
                        title="{{ $m->material->material_name ?? '-' }}" 
                        subtitle="{{ $m->brand_model ? '(' . $m->brand_model . ')' : '' }}" 
                        subtitleClass="text-brand-400 font-normal mt-1"
                    />
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
                <x-ui.action-buttons id="{{ $m->id }}" />
            </x-table.mobile-card-header>
            @if($m->notes)
                <x-table.mobile-card-note class="italic">{{ $m->notes }}</x-table.mobile-card-note>
            @endif
        </x-table.mobile-card>
        @empty
        <x-table.mobile-empty />
        @endforelse
    </x-table.mobile-list>
</x-ui.page-wrapper>
</div>
