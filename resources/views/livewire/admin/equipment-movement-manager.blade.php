<div>
<x-ui.page-wrapper>
    <x-ui.flash />
<x-ui.toolbar :count="count($movements)" label="Всього переміщень" buttonLabel="Перемістити техніку" />

    @if($isOpen)
    <x-ui.modal title="{{ $movementId ? 'Редагувати' : 'Зареєструвати' }} переміщення" maxWidth="md">
            <div>
                    <x-form.select label="Обладнання (Інв. №)" model="equipment_id">
                            <option value="">Оберіть обладнання...</option>
                        @foreach($equipmentList as $eq)
                            <option value="{{ $eq->id }}">{{ $eq->inventory_number }} - {{ $eq->accounting_name }}</option>
                        @endforeach
                        </x-form.select>
                </div>
                <div>
                    <x-form.select label="Куди переміщено (Кабінет / Локація)" model="location_id">
                            <option value="">Оберіть локацію...</option>
                        @foreach($locationsList as $loc)
                            <option value="{{ $loc->id }}">Кабінет {{ $loc->room_number }}</option>
                        @endforeach
                        </x-form.select>
                </div>
                <div>
                    <x-form.select label="Відповідальний співробітник" model="employee_id">
                            <option value="">Без відповідального (на склад)...</option>
                        @foreach($employeesList as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->fullName }} ({{ $emp->position }})</option>
                        @endforeach
                        </x-form.select>
                </div>
                <div>
                    <x-form.input label="Дата переміщення" model="move_date" type="date" />
                </div>
        </x-ui.modal>
    @endif

    {{-- Desktop --}}
    <x-table.wrapper>
            <x-slot name="headers">
                    <x-table.th align="left" width="28">Дата</x-table.th>
                    <x-table.th align="left">Обладнання (Інв. №)</x-table.th>
                    <x-table.th align="left">Нове розташування</x-table.th>
                    <x-table.th align="left">Матеріально відповідальний</x-table.th>
                    <x-table.th align="right" width="32">Дії</x-table.th>
                </x-slot>
            
                @forelse($movements as $m)
                <x-table.tr>
                    <x-table.td align="left" class="text-gray-400 whitespace-nowrap">{{ $m->move_date }}</x-table.td>
                    <x-table.td align="left" primary>
                        {{ $m->equipment->inventory_number ?? '-' }}
                        <x-table.cell-subtext>{{ $m->equipment->accounting_name ?? '' }}</x-table.cell-subtext>
                    </x-table.td>
                    <x-table.td align="left">Кабінет {{ $m->asset->location->room_number ?? ($m->location->room_number ?? '-') }}</x-table.td>
                    <x-table.td align="left">{{ $m->employee->fullName ?? 'На складі (без відповідального)' }}</x-table.td>
                    <x-table.td align="right">
                        <x-ui.action-buttons id="{{ $m->id }}" />
                    </x-table.td>
                </x-table.tr>
                @empty
                <x-table.empty colspan="5" />
                @endforelse
            
        </x-table.wrapper>

    {{-- Mobile --}}
    <x-table.mobile-list>
        @forelse($movements as $m)
        <x-table.mobile-card layout="col">
            <x-table.mobile-card-header align="start">
                <div>
                    <x-table.cell-subtext>Дата: {{ $m->move_date }}</x-table.cell-subtext>
                    <x-ui.text-block 
                        title="Обладнання: {{ $m->equipment->inventory_number ?? '-' }}" 
                        subtitle="Куди: Кабінет {{ $m->asset->location->room_number ?? ($m->location->room_number ?? '-') }}" 
                        subtitleClass="text-xs text-brand-400 font-medium"
                    />
                </div>
                <x-ui.action-buttons id="{{ $m->id }}" />
            </x-table.mobile-card-header>
            <x-table.mobile-card-footer>
                Відповідальний: {{ $m->employee->fullName ?? 'На складі (без відповідального)' }}
            </x-table.mobile-card-footer>
        </x-table.mobile-card>
        @empty
        <x-table.mobile-empty />
        @endforelse
    </x-table.mobile-list>
</x-ui.page-wrapper>
</div>
