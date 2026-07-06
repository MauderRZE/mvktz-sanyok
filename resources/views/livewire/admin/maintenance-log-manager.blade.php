<div>
<x-ui.page-wrapper>
    <x-ui.flash />
<x-ui.toolbar :count="count($logs)" label="Всього записів ТО" buttonLabel="Додати запис ТО" />

    @if($isOpen)
    <x-ui.modal title="{{ $logId ? 'Редагувати' : 'Додати' }} запис обслуговування" maxWidth="md">
            <div>
                    <x-form.select label="Обладнання (Комплектуюча)" model="assets_id">
                            <option value="">Оберіть обладнання...</option>
                        @foreach($assetsList as $comp)
                            <option value="{{ $comp->id }}">
                                [{{ $comp->equipment->inv_number ?? 'Склад' }}] {{ $comp->componentType->component_name ?? '-' }}
                            </option>
                        @endforeach
                        </x-form.select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <x-form.input label="Дата відправки" model="sent_date" type="date" />
                    </div>
                    <div>
                        <x-form.input label="Дата повернення" model="return_date" type="date" />
                    </div>
                </div>

                <div>
                    <x-form.select label="Статус" model="status">
                        <option value="В ремонті">В ремонті</option>
                        <option value="Відремонтовано">Відремонтовано</option>
                        <option value="Неможливо відремонтувати">Неможливо відремонтувати</option>
                    </x-form.select>
                </div>

                <div>
                    <x-form.textarea label="Опис проблеми (скарга)" model="issue_description" placeholder="Опишіть проблему..." rows="3" />
                </div>
        </x-ui.modal>
    @endif

    {{-- Desktop --}}
    <x-table.wrapper>
            <x-slot name="headers">
                    <x-table.th align="left" width="28">Відправлено</x-table.th>
                    <x-table.th align="left">Комплектуюча (Інв. №)</x-table.th>
                    <x-table.th align="left">Проблема</x-table.th>
                    <x-table.th align="left" width="28">Статус / Повернення</x-table.th>
                    <x-table.th align="right" width="32">Дії</x-table.th>
                </x-slot>
            
                @forelse($logs as $l)
                <x-table.tr>
                    <x-table.td align="left" class="text-gray-400 whitespace-nowrap">{{ $l->sent_date }}</x-table.td>
                    <x-table.td align="left" primary>
                        {{ $l->asset->componentType->component_name ?? '-' }}
                        <x-table.cell-subtext>{{ $l->asset->equipment->inv_number ?? '' }} {{ $l->asset->equipment->account_name ?? '' }}</x-table.cell-subtext>
                    </x-table.td>
                    <x-table.td class="max-w-xs truncate" title="{{ $l->issue_description }}">{{ $l->issue_description }}</x-table.td>
                    <x-table.td align="left">
                        <x-ui.badge status="{{ $l->status }}" />
                        @if($l->return_date) <div class="text-xs text-gray-500 mt-1">{{ $l->return_date }}</div> @endif
                    </x-table.td>
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
                    <x-table.cell-subtext>Відпр: {{ $l->sent_date }}</x-table.cell-subtext>
                    <x-ui.text-block 
                        title="Обладнання: {{ $l->asset->equipment->inv_number ?? '-' }}" 
                        subtitle="Комплектуюча: {{ $l->asset->componentType->component_name ?? '-' }}" 
                        subtitleClass="text-xs text-brand-400 font-medium"
                    />
                </div>
                <x-ui.action-buttons id="{{ $l->id }}" />
            </x-table.mobile-card-header>
            
            <x-table.mobile-card-note>
                {{ $l->issue_description }}
            </x-table.mobile-card-note>

            <x-table.mobile-card-footer flex="true">
                <span class="text-gray-500 font-semibold">Статус:</span>
                <span class="text-gray-300">{{ $l->status }}</span>
                @if($l->return_date)
                    <span class="text-gray-500 text-xs mt-1">Повернено: {{ $l->return_date }}</span>
                @endif
            </x-table.mobile-card-footer>
        </x-table.mobile-card>
        @empty
        <x-table.mobile-empty />
        @endforelse
    </x-table.mobile-list>
</x-ui.page-wrapper>
</div>
