<div>
<x-ui.page-wrapper>
    <x-ui.flash />
<x-ui.toolbar :count="count($logs)" label="Всього записів ТО" buttonLabel="Додати запис ТО" />

    {{-- Filters Bar --}}
    <x-ui.card class="p-4 mb-4 space-y-4">
        <div class="flex flex-col lg:flex-row gap-4 items-center">
            <div class="flex-1 w-full flex gap-2">
                <div class="flex-1">
                    <x-form.search wire:model.live.debounce.300ms="search" placeholder="Пошук за описом проблеми, інвентарним номером, типом комплектуючої..." />
                </div>
                @if($search !== '' || !empty($filterStatus) || !empty($filterAsset))
                    <button wire:click="resetFilters" class="px-4 py-2 bg-white/5 hover:bg-white/10 text-xs text-gray-400 hover:text-white rounded-xl border border-white/10 transition-colors shrink-0 flex items-center gap-1.5" title="Скинути всі фільтри">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        <span>Скинути</span>
                    </button>
                @endif
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 w-full lg:w-auto">
                <x-form.multi-select label="Статус" :selectedCount="count($filterStatus)">
                    @foreach(['В ремонті', 'Відремонтовано', 'Неможливо відремонтувати'] as $st)
                        <label class="flex items-center gap-2 text-xs text-gray-300 hover:text-white cursor-pointer py-1">
                            <input type="checkbox" value="{{ $st }}" wire:model.live="filterStatus" class="rounded border-white/10 bg-surface-900 text-brand-500 focus:ring-0 focus:ring-offset-0">
                            <span>{{ $st }}</span>
                        </label>
                    @endforeach
                </x-form.multi-select>

                <x-form.multi-select label="Комплектуючі" :selectedCount="count($filterAsset)">
                    @foreach($assetsList as $comp)
                        <label class="flex items-center gap-2 text-xs text-gray-300 hover:text-white cursor-pointer py-1">
                            <input type="checkbox" value="{{ $comp->id }}" wire:model.live="filterAsset" class="rounded border-white/10 bg-surface-900 text-brand-500 focus:ring-0 focus:ring-offset-0">
                            <span class="truncate max-w-[200px]" title="[{{ $comp->equipment->inv_number ?? 'Склад' }}] {{ $comp->componentType->component_name ?? '-' }}">
                                [{{ $comp->equipment->inv_number ?? 'Склад' }}] {{ $comp->componentType->component_name ?? '-' }}
                            </span>
                        </label>
                    @endforeach
                </x-form.multi-select>
            </div>
        </div>
    </x-ui.card>

    @if($isOpen)
    <x-ui.modal title="{{ $form->logId ? 'Редагувати' : 'Додати' }} запис обслуговування" maxWidth="md">
            <div>
                    <x-form.select label="Обладнання (Комплектуюча)" model="form.assets_id">
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
                        <x-form.input label="Дата відправки" model="form.sent_date" type="date" />
                    </div>
                    <div>
                        <x-form.input label="Дата повернення" model="form.return_date" type="date" />
                    </div>
                </div>

                <div>
                    <x-form.select label="Статус" model="form.status">
                        <option value="В ремонті">В ремонті</option>
                        <option value="Відремонтовано">Відремонтовано</option>
                        <option value="Неможливо відремонтувати">Неможливо відремонтувати</option>
                    </x-form.select>
                </div>

                <div>
                    <x-form.textarea label="Опис проблеми (скарга)" model="form.issue_description" placeholder="Опишіть проблему..." rows="3" />
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
