<div>
<x-ui.page-wrapper>
    <x-ui.flash />
<x-ui.toolbar :count="count($licenses)" label="Всього ліцензій" buttonLabel="Додати ліцензію" />

    @if($isOpen)
    <x-ui.modal title="{{ $licenseId ? 'Редагувати' : 'Додати' }} ліцензію ПЗ" maxWidth="md">
        <div class="space-y-4">
            <div>
                    <x-form.input label="Назва ліцензії" model="license_name" type="text" placeholder="напр. Windows 11 Pro" />
                </div>

                <div>
                    <x-form.input label="Тип ліцензії" model="license_type" type="text" />
                </div>

                <div>
                    <x-form.input label="Дата придбання" model="purchase_date" type="date" />
                </div>
        </div>
        </x-ui.modal>
    @endif

    {{-- Desktop --}}
    <x-table.wrapper>
            <x-slot name="headers">
                    <x-table.th align="left">ID</x-table.th>
                    <x-table.th align="left">Назва ліцензії</x-table.th>
                    <x-table.th align="left">Тип ліцензії</x-table.th>
                    <x-table.th align="left">Дата придбання</x-table.th>
                    <x-table.th align="right" width="32">Дії</x-table.th>
                </x-slot>
            
                @forelse($licenses as $lic)
                <x-table.tr>
                    <x-table.td align="left" class="text-gray-400 font-mono text-xs">#{{ $lic->id }}</x-table.td>
                    <x-table.td align="left" primary>{{ $lic->license_name }}</x-table.td>
                    <x-table.td align="left" class="text-gray-300 text-xs">{{ $lic->license_type ?? '-' }}</x-table.td>
                    <x-table.td align="left" class="text-gray-400 whitespace-nowrap">{{ $lic->purchase_date ?? '-' }}</x-table.td>
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
                        title="{{ $lic->license_name }}" 
                        subtitle="Тип: {{ $lic->license_type ?: '-' }}" 
                        subtitleClass="text-xs text-gray-400 font-mono"
                    />
                </div>
                <x-ui.action-buttons id="{{ $lic->id }}" />
            </x-table.mobile-card-header>
            
            <x-table.mobile-card-footer flex="true">
                <span class="text-gray-500 font-semibold">Дата придбання:</span>
                <span class="text-gray-300">{{ $lic->purchase_date ?: '-' }}</span>
            </x-table.mobile-card-footer>
        </x-table.mobile-card>
        @empty
        <x-table.mobile-empty />
        @endforelse
    </x-table.mobile-list>
</x-ui.page-wrapper>
</div>
