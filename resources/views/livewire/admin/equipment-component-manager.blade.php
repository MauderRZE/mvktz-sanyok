<x-ui.page-wrapper>
    <x-ui.flash />
<x-ui.toolbar :count="count($components)" label="Всього" buttonLabel="Додати" />

    @if($isOpen)
    <x-ui.modal title="{{ $componentId ? 'Редагувати' : 'Додати' }} комплектуюче" maxWidth="lg">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <x-form.select label="Обладнання (ПК / Пристрій)" model="equipment_id">
                            <option value="">Оберіть обладнання...</option>
                            @foreach($equipmentList as $eq)
                                <option value="{{ $eq->id }}">{{ $eq->inventory_number }} - {{ $eq->accounting_name }}</option>
                            @endforeach
                        </x-form.select>
                    </div>
                    <div>
                        <x-form.select label="Базовий компонент" model="component_type_id">
                            <option value="">Оберіть компонент...</option>
                            @foreach($baseComponentsList as $bc)
                                <option value="{{ $bc->id }}">{{ $bc->component_name }}</option>
                            @endforeach
                        </x-form.select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <x-form.input label="Виробник / Модель" model="brand_model" type="text" placeholder="напр. Kingston DDR4 16GB" />
                    </div>
                    <div>
                        <x-form.input label="Серійний номер" model="serial_number" type="text" />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <x-form.input label="Модель картриджа (якщо є)" model="cartridge_model" type="text" />
                    </div>
                    <div>
                        <x-form.select label="Стан роботи" model="status">
                            <option value="Працює">Працює</option>
                            <option value="Знято">Знято</option>
                            <option value="Зламано">Зламано</option>
                            <option value="В ремонті">В ремонті</option>
                        </x-form.select>
                    </div>
                </div>

                <div class="border-t border-white/5 pt-4">
                    <x-form.checkbox label="Мережевий пристрій / інтерфейс" model="has_network" />

                    @if($has_network)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 fade-in">
                        <div>
                            <x-form.input label="IP-Адреса" model="ip_address" type="text" placeholder="192.168.1.50" />
                        </div>
                        <div>
                            <x-form.input label="MAC-Адреса" model="mac_address" type="text" placeholder="AA:BB:CC:DD:EE:FF" />
                        </div>
                    </div>
                    @endif
                </div>
        </x-ui.modal>
    @endif

    {{-- Desktop --}}
    <x-table.wrapper>
            <x-slot name="headers">
                    <x-table.th align="left">Пристрій (Інв. №)</x-table.th>
                    <x-table.th align="left">Тип компонента</x-table.th>
                    <x-table.th align="left">Модель</x-table.th>
                    <x-table.th align="left">Серійний</x-table.th>
                    <x-table.th align="left">Мережа</x-table.th>
                    <x-table.th align="left">Статус</x-table.th>
                    <x-table.th align="right" width="24">Дії</x-table.th>
                </x-slot>
            
                @forelse($components as $c)
                <x-table.tr>
                    <x-table.td align="left" primary>
                        {{ $c->equipment->inventory_number ?? '-' }}
                        <x-table.cell-subtext>{{ $c->equipment->accounting_name ?? '' }}</x-table.cell-subtext>
                    </x-table.td>
                    <x-table.td align="left">{{ $c->componentType->component_name ?? '-' }}</x-table.td>
                    <x-table.td align="left">
                        {{ $c->brand_model ?? '-' }}
                        @if($c->cartridge_model)
                            <x-table.cell-subtext class="text-brand-400">Картридж: {{ $c->cartridge_model }}</x-table.cell-subtext>
                        @endif
                    </x-table.td>
                    <x-table.td align="left" class="text-gray-400 font-mono text-xs">{{ $c->serial_number ?? '-' }}</x-table.td>
                    <x-table.td align="left" class="text-gray-400">
                        @if($c->has_network)
                            <span class="text-xs text-brand-400 block font-mono">{{ $c->ip_address }}</span>
                            <x-table.cell-subtext class="font-mono">{{ $c->mac_address }}</x-table.cell-subtext>
                        @else
                            <span class="text-gray-600">-</span>
                        @endif
                    </x-table.td>
                    <x-table.td align="left">
                        <x-ui.badge status="{{ $c->status }}" />
                    </x-table.td>
                    <x-table.td align="right">
                        <x-ui.action-buttons id="{{ $c->id }}" />
                    </x-table.td>
                </x-table.tr>
                @empty
                <x-table.empty colspan="7" />
                @endforelse
            
        </x-table.wrapper>

    {{-- Mobile --}}
    <x-table.mobile-list>
        @forelse($components as $c)
        <x-table.mobile-card layout="y-2">
            <x-table.mobile-card-header align="start">
                <div>
                    <span class="text-xs text-brand-400 font-semibold uppercase tracking-wider block mb-1">{{ $c->componentType->component_name ?? '-' }}</span>
                    <x-ui.text-block 
                        title="{{ $c->brand_model ?? '-' }}" 
                        subtitle="Пристрій: {{ $c->equipment->inventory_number ?? '-' }}" 
                    />
                </div>
                <x-ui.action-buttons id="{{ $c->id }}" />
            </x-table.mobile-card-header>
            
            <x-table.mobile-card-footer class="grid grid-cols-2 gap-2 font-mono">
                <div>
                    <x-table.cell-subtext>Серійний:</x-table.cell-subtext>
                    {{ $c->serial_number ?: '-' }}
                </div>
                @if($c->has_network)
                <div>
                    <x-table.cell-subtext>IP / MAC:</x-table.cell-subtext>
                    {{ $c->ip_address }}<br><span class="text-[9px]">{{ $c->mac_address }}</span>
                </div>
                @endif
            </x-table.mobile-card-footer>

            <x-table.mobile-card-footer flex="true">
                <span class="text-xs text-gray-500">Статус:</span>
                <x-ui.badge status="{{ $c->status }}" :dot="false" />
            </x-table.mobile-card-footer>
        </x-table.mobile-card>
        @empty
        <x-table.mobile-empty />
        @endforelse
    </x-table.mobile-list>
</x-ui.page-wrapper>
