<div>
<x-ui.page-wrapper>
    <x-ui.flash />
<x-ui.toolbar :count="count($materials)" label="Всього позицій" buttonLabel="Додати МШП" />

    @if($isOpen)
    <x-ui.modal title="{{ $materialId ? 'Редагувати' : 'Додати' }} МШП" maxWidth="lg">
        <div class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-end">
                    <div>
                        <x-form.input label="Облікова назва матеріалу" model="material_account_name" type="text" />
                    </div>
                    <div>
                        <x-form.input label="Кількість" model="count" type="number" />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
                    <div>
                        <x-form.input label="Ціна (грн)" model="price" type="number" step="0.01" />
                    </div>
                    <div>
                        <x-form.input label="Номенклатурний номер" model="nomenklature_number" type="text" />
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
        </div>
        </x-ui.modal>
    @endif

    {{-- Desktop --}}
    <x-table.wrapper>
            <x-slot name="headers">
                    <x-table.th align="left">Матеріал (МШП)</x-table.th>
                    <x-table.th align="left">Номенклатурний №</x-table.th>
                    <x-table.th align="left">Ціна</x-table.th>
                    <x-table.th align="left">К-сть</x-table.th>
                    <x-table.th align="left">Договір</x-table.th>
                    <x-table.th align="right" width="24">Дії</x-table.th>
                </x-slot>
            
                @forelse($materials as $m)
                <x-table.tr>
                    <x-table.td align="left" primary>
                        {{ $m->material_account_name ?? '-' }}
                    </x-table.td>
                    <x-table.td align="left" class="text-gray-400 font-mono text-xs">
                        @if($m->nomenklature_number)
                            <x-table.cell-subtext>Ном: {{ $m->nomenklature_number }}</x-table.cell-subtext>
                        @else
                            -
                        @endif
                    </x-table.td>
                    <x-table.td align="left" class="text-emerald-400 font-medium">
                        {{ $m->price ? number_format($m->price, 2) . ' грн' : '-' }}
                    </x-table.td>
                    <x-table.td align="left" class="text-gray-300 font-semibold">
                        <div>{{ $m->count }} шт.</div>
                    </x-table.td>
                    <x-table.td align="left" class="text-gray-400 text-xs">
                        @if($m->contract)
                            Договір №{{ $m->contract->contract_number }}
                            <x-table.cell-subtext>{{ $m->contract->contract_date }}</x-table.cell-subtext>
                        @else
                            -
                        @endif
                    </x-table.td>
                    <x-table.td align="right">
                        <x-ui.action-buttons id="{{ $m->id }}" />
                    </x-table.td>
                </x-table.tr>
                @empty
                <x-table.empty colspan="6" />
                @endforelse
            
        </x-table.wrapper>

    {{-- Mobile --}}
    <x-table.mobile-list>
        @forelse($materials as $m)
        <x-table.mobile-card layout="y-2">
            <x-table.mobile-card-header align="start">
                <div>
                    <span class="text-xs text-gray-500">Кількість: {{ $m->count }} шт.</span>
                    <x-ui.text-block 
                        title="{{ $m->material_account_name ?? '-' }}" 
                        subtitleClass="text-brand-400 font-normal mt-1"
                    />
                    @if($m->nomenklature_number)
                        <p class="text-[11px] text-gray-400 font-mono mt-1">
                            Ном: {{ $m->nomenklature_number }}
                        </p>
                    @endif
                    @if($m->price)
                        <p class="text-sm text-emerald-400 mt-1 font-semibold">
                            {{ number_format($m->price, 2) }} грн
                        </p>
                    @endif
                </div>
                <x-ui.action-buttons id="{{ $m->id }}" />
            </x-table.mobile-card-header>
        </x-table.mobile-card>
        @empty
        <x-table.mobile-empty />
        @endforelse
    </x-table.mobile-list>
</x-ui.page-wrapper>
</div>
