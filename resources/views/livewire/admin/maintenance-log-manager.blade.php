<div>
<x-ui.page-wrapper>
    <x-ui.flash />
<x-ui.toolbar :count="count($logs)" label="Всього записів ТО" buttonLabel="Додати запис ТО" />

    @if($isOpen)
    <x-ui.modal title="{{ $logId ? 'Редагувати' : 'Додати' }} запис обслуговування" maxWidth="md">
            <div>
                    <x-form.select label="Обладнання (ПК / Пристрій)" model="equipment_id">
                            <option value="">Оберіть обладнання...</option>
                        @foreach($equipmentList as $eq)
                            <option value="{{ $eq->id }}">{{ $eq->inventory_number }} - {{ $eq->accounting_name }}</option>
                        @endforeach
                        </x-form.select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <x-form.select label="Тип обслуговування" model="action_type_id">
                            <option value="">Оберіть тип робіт...</option>
                            @foreach($typesList as $t)
                                <option value="{{ $t->id }}">{{ $t->type_name }}</option>
                            @endforeach
                        </x-form.select>
                    </div>
                    <div>
                        <x-form.input type="number" step="0.01" label="Вартість (грн)" model="cost" />
                    </div>
                </div>

                <div>
                    <x-form.input label="Дата проведення робіт" model="action_date" type="date" />
                </div>

                <div>
                    <x-form.textarea label="Детальний опис проведених робіт" model="description" placeholder="Опишіть які саме роботи було виконано..." rows="3" />
                </div>
        </x-ui.modal>
    @endif

    {{-- Desktop --}}
    <x-table.wrapper>
            <x-slot name="headers">
                    <x-table.th align="left" width="28">Дата</x-table.th>
                    <x-table.th align="left">Обладнання (Інв. №)</x-table.th>
                    <x-table.th align="left">Тип роботи</x-table.th>
                    <x-table.th align="left">Опис</x-table.th>
                    <x-table.th align="left" width="28">Вартість</x-table.th>
                    <x-table.th align="right" width="32">Дії</x-table.th>
                </x-slot>
            
                @forelse($logs as $l)
                <x-table.tr>
                    <x-table.td align="left" class="text-gray-400 whitespace-nowrap">{{ $l->action_date }}</x-table.td>
                    <x-table.td align="left" primary>
                        {{ $l->equipment->inventory_number ?? '-' }}
                        <x-table.cell-subtext>{{ $l->equipment->accounting_name ?? '' }}</x-table.cell-subtext>
                    </x-table.td>
                    <x-table.td align="left" class="text-brand-400 font-medium">{{ $l->maintenanceType->type_name ?? '-' }}</x-table.td>
                    <x-table.td class="max-w-xs truncate" title="{{ $l->description }}">{{ $l->description }}</x-table.td>
                    <x-table.td align="left" class="text-emerald-400 font-semibold">{{ number_format($l->cost, 2, '.', ' ') }} грн</x-table.td>
                    <x-table.td align="right">
                        <x-ui.action-buttons id="{{ $l->id }}" />
                    </x-table.td>
                </x-table.tr>
                @empty
                <x-table.empty colspan="6" />
                @endforelse
            
        </x-table.wrapper>

    {{-- Mobile --}}
    <x-table.mobile-list>
        @forelse($logs as $l)
        <x-table.mobile-card layout="y-2">
            <x-table.mobile-card-header align="start">
                <div>
                    <x-table.cell-subtext>Дата: {{ $l->action_date }}</x-table.cell-subtext>
                    <x-ui.text-block 
                        title="Обладнання: {{ $l->equipment->inventory_number ?? '-' }}" 
                        subtitle="Робота: {{ $l->maintenanceType->type_name ?? '-' }}" 
                        subtitleClass="text-xs text-brand-400 font-medium"
                    />
                </div>
                <x-ui.action-buttons id="{{ $l->id }}" />
            </x-table.mobile-card-header>
            
            <x-table.mobile-card-note>
                {{ $l->description }}
            </x-table.mobile-card-note>

            <x-table.mobile-card-footer flex="true">
                <span class="text-gray-500 font-semibold">Вартість:</span>
                <span class="text-emerald-400 font-bold">{{ number_format($l->cost, 2, '.', ' ') }} грн</span>
            </x-table.mobile-card-footer>
        </x-table.mobile-card>
        @empty
        <x-table.mobile-empty />
        @endforelse
    </x-table.mobile-list>
</x-ui.page-wrapper>
</div>
