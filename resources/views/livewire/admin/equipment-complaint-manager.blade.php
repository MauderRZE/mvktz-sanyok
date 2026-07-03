<div>
<x-ui.page-wrapper>
    <x-ui.flash />
<x-ui.toolbar :count="count($complaints)" label="Всього скарг" buttonLabel="Зареєструвати скаргу" />

    @if($isOpen)
    <x-ui.modal title="{{ $complaintId ? 'Редагувати' : 'Зареєструвати' }} скаргу" maxWidth="lg">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <x-form.select label="Обладнання (Інв. №)" model="equipment_id">
                            <option value="">Оберіть обладнання...</option>
                            @foreach($equipmentList as $eq)
                                <option value="{{ $eq->id }}">{{ $eq->inventory_number }} - {{ $eq->accounting_name }}</option>
                            @endforeach
                        </x-form.select>
                    </div>
                    <div>
                        <x-form.select label="Співробітник (хто поскаржився)" model="reported_by_employee_id">
                            <option value="">Оберіть співробітника...</option>
                            @foreach($employeesList as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->fullName }} ({{ $emp->position }})</option>
                            @endforeach
                        </x-form.select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <x-form.input label="Дата скарги" model="complaint_date" type="date" />
                    </div>
                    <div>
                        <x-form.select label="Статус вирішення" model="resolution_status">
                            <option value="Відкрито">Відкрито</option>
                            <option value="В роботі">В роботі</option>
                            <option value="Вирішено">Вирішено</option>
                        </x-form.select>
                    </div>
                </div>

                @if($resolution_status === 'Вирішено')
                <div>
                    <x-form.input label="Дата вирішення" model="resolution_date" type="date" />
                </div>
                @endif

                <div>
                    <x-form.textarea label="Опис несправності / проблеми" model="issue_description" placeholder="Детально опишіть проблему..." rows="3" />
                </div>
        </x-ui.modal>
    @endif

    {{-- Desktop --}}
    <x-table.wrapper>
            <x-slot name="headers">
                    <x-table.th align="left">Дата</x-table.th>
                    <x-table.th align="left">Обладнання</x-table.th>
                    <x-table.th align="left">Скаржник</x-table.th>
                    <x-table.th align="left">Опис проблеми</x-table.th>
                    <x-table.th align="left">Статус</x-table.th>
                    <x-table.th align="right" width="24">Дії</x-table.th>
                </x-slot>
            
                @forelse($complaints as $c)
                <x-table.tr>
                    <x-table.td align="left" class="text-gray-400 whitespace-nowrap">{{ $c->complaint_date }}</x-table.td>
                    <x-table.td align="left" primary>
                        {{ $c->equipment->inventory_number ?? '-' }}
                        <x-table.cell-subtext>{{ $c->equipment->accounting_name ?? '' }}</x-table.cell-subtext>
                    </x-table.td>
                    <x-table.td align="left">{{ $c->employee->fullName ?? 'Не вказано' }}</x-table.td>
                    <x-table.td class="max-w-xs truncate" title="{{ $c->issue_description }}">{{ $c->issue_description }}</x-table.td>
                    <x-table.td align="left">
                        <x-ui.badge status="{{ $c->resolution_status }}">
                            {{ $c->resolution_status }}
                            @if($c->resolution_date && $c->resolution_status == 'Вирішено')
                                <span class="text-[10px] text-emerald-500 ml-1">({{ $c->resolution_date }})</span>
                            @endif
                        </x-ui.badge>
                    </x-table.td>
                    <x-table.td align="right">
                        <x-ui.action-buttons id="{{ $c->id }}" />
                    </x-table.td>
                </x-table.tr>
                @empty
                <x-table.empty colspan="6" />
                @endforelse
            
        </x-table.wrapper>

    {{-- Mobile --}}
    <x-table.mobile-list>
        @forelse($complaints as $c)
        <x-table.mobile-card layout="y-2">
            <x-table.mobile-card-header align="start">
                <div>
                    <x-table.cell-subtext>{{ $c->complaint_date }}</x-table.cell-subtext>
                    <x-ui.text-block 
                        title="Обладнання: {{ $c->equipment->inventory_number ?? '-' }}" 
                        subtitle="Скаржник: {{ $c->employee->fullName ?? 'Не вказано' }}" 
                    />
                </div>
                <x-ui.action-buttons id="{{ $c->id }}" />
            </x-table.mobile-card-header>
            
            <x-table.mobile-card-note>
                {{ $c->issue_description }}
            </x-table.mobile-card-note>

            <x-table.mobile-card-footer flex="true">
                <span class="text-xs text-gray-500">Статус:</span>
                <x-ui.badge status="{{ $c->resolution_status }}" :dot="false" />
            </x-table.mobile-card-footer>
        </x-table.mobile-card>
        @empty
        <x-table.mobile-empty />
        @endforelse
    </x-table.mobile-list>
</x-ui.page-wrapper>
</div>
