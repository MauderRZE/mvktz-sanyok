<div>
<x-ui.page-wrapper>
    <x-ui.flash />
<x-ui.toolbar :count="count($licenses)" label="Всього ліцензій" buttonLabel="Додати ліцензію" />

    @if($isOpen)
    <x-ui.modal title="{{ $licenseId ? 'Редагувати' : 'Додати' }} ліцензію ПЗ" maxWidth="md">
            <div>
                    <x-form.input label="Назва програмного забезпечення" model="software_name" type="text" placeholder="напр. Windows 11 Pro, Office 2021" />
                </div>

                <div>
                    <x-form.input label="Ліцензійний ключ / Сертифікат" model="license_key" type="text" />
                </div>

                <div>
                    <x-form.select label="Встановлено на комплектуюче (ПК)" model="component_id">
                            <option value="">Оберіть компонент...</option>
                        @foreach($componentsList as $comp)
                            <option value="{{ $comp->id }}">
                                [{{ $comp->equipment->inventory_number ?? 'Склад' }}] {{ $comp->componentType->component_name ?? '-' }} ({{ $comp->brand_model }})
                            </option>
                        @endforeach
                        </x-form.select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <x-form.select label="Статус ліцензії" model="license_status">
                            <option value="Активна">Активна</option>
                            <option value="Прострочена">Прострочена</option>
                            <option value="Призупинена">Призупинена</option>
                        </x-form.select>
                    </div>
                    <div>
                        <x-form.input label="Дата закінчення" model="expiration_date" type="date" />
                    </div>
                </div>
        </x-ui.modal>
    @endif

    {{-- Desktop --}}
    <x-table.wrapper>
            <x-slot name="headers">
                    <x-table.th align="left">Назва ПЗ</x-table.th>
                    <x-table.th align="left">Ключ ліцензії</x-table.th>
                    <x-table.th align="left">Встановлено на ПК (Компонент)</x-table.th>
                    <x-table.th align="left">Термін дії</x-table.th>
                    <x-table.th align="left">Статус</x-table.th>
                    <x-table.th align="right" width="32">Дії</x-table.th>
                </x-slot>
            
                @forelse($licenses as $lic)
                <x-table.tr>
                    <x-table.td align="left" primary>{{ $lic->software_name }}</x-table.td>
                    <x-table.td align="left" class="text-gray-400 font-mono text-xs">{{ $lic->license_key ?? '-' }}</x-table.td>
                    <x-table.td align="left" class="text-gray-300 text-xs">
                        @if($lic->component)
                            <span class="text-brand-400 font-medium">[{{ $lic->component->equipment->inventory_number ?? 'Склад' }}]</span> 
                            {{ $lic->component->componentType->component_name ?? '-' }} ({{ $lic->component->brand_model }})
                        @else
                            -
                        @endif
                    </x-table.td>
                    <x-table.td align="left" class="text-gray-400 whitespace-nowrap">
                        @if($lic->expiration_date)
                            {{ $lic->expiration_date }}
                        @else
                            <span class="text-gray-600">Безстрокова</span>
                        @endif
                    </x-table.td>
                    <x-table.td align="left">
                        <x-ui.badge status="{{ $lic->license_status }}" />
                    </x-table.td>
                    <x-table.td align="right">
                        <x-ui.action-buttons id="{{ $lic->id }}" />
                    </x-table.td>
                </x-table.tr>
                @empty
                <x-table.empty colspan="6" />
                @endforelse
            
        </x-table.wrapper>

    {{-- Mobile --}}
    <x-table.mobile-list>
        @forelse($licenses as $lic)
        <x-table.mobile-card layout="y-2">
            <x-table.mobile-card-header align="start">
                <div>
                    <span class="text-xs text-brand-400 font-semibold uppercase tracking-wider block mb-1">Ліцензія ПЗ</span>
                    <x-ui.text-block 
                        title="{{ $lic->software_name }}" 
                        subtitle="Ключ: {{ $lic->license_key ?: '-' }}" 
                        subtitleClass="text-xs text-gray-400 font-mono"
                    />
                </div>
                <x-ui.action-buttons id="{{ $lic->id }}" />
            </x-table.mobile-card-header>
            
            <x-table.mobile-card-footer>
                Встановлено: 
                @if($lic->component)
                    [{{ $lic->component->equipment->inventory_number ?? 'Склад' }}] {{ $lic->component->componentType->component_name ?? '-' }} ({{ $lic->component->brand_model }})
                @else
                    -
                @endif
            </x-table.mobile-card-footer>

            <x-table.mobile-card-footer flex="true">
                <span class="text-gray-500 font-semibold">Термін:</span>
                <span class="text-gray-300">{{ $lic->expiration_date ?: 'Безстрокова' }}</span>
                <x-ui.badge status="{{ $lic->license_status }}" :dot="false" />
            </x-table.mobile-card-footer>
        </x-table.mobile-card>
        @empty
        <x-table.mobile-empty />
        @endforelse
    </x-table.mobile-list>
</x-ui.page-wrapper>
</div>
