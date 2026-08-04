<div>
<x-ui.page-wrapper>
    <x-ui.flash />
<x-ui.toolbar :count="$licenses->total()" label="Всього ліцензій" buttonLabel="Додати ліцензію" />

    {{-- Filters Bar --}}
    <x-ui.card class="p-4 mb-4 space-y-4">
        <div class="flex flex-col lg:flex-row gap-4 items-center">
            <div class="flex-1 w-full flex gap-2">
                <div class="flex-1">
                    <x-form.search wire:model.live.debounce.300ms="search" placeholder="Пошук за назвою ліцензії, постачальником..." />
                </div>
                @if($search !== '' || !empty($filterType) || !empty($filterVendor))
                    <button wire:click="resetFilters" class="px-4 py-2 bg-white/5 hover:bg-white/10 text-xs text-gray-400 hover:text-white rounded-xl border border-white/10 transition-colors shrink-0 flex items-center gap-1.5" title="Скинути всі фільтри">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        <span>Скинути</span>
                    </button>
                @endif
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 w-full lg:w-auto">
                <x-form.multi-select label="Тип ліцензії" :selectedCount="count($filterType)">
                    <label class="flex items-center gap-2 text-xs font-semibold text-amber-400 cursor-pointer py-1">
                        <input type="checkbox" value="null" wire:model.live="filterType" class="rounded border-white/10 bg-surface-900 text-brand-500 focus:ring-0 focus:ring-offset-0">
                        <span>[Не вказано / Null]</span>
                    </label>
                    @foreach(['OEM', 'Retail', 'Корпоративна', 'Підписка', 'Безкоштовна'] as $t)
                        <label class="flex items-center gap-2 text-xs text-gray-300 hover:text-white cursor-pointer py-1">
                            <input type="checkbox" value="{{ $t }}" wire:model.live="filterType" class="rounded border-white/10 bg-surface-900 text-brand-500 focus:ring-0 focus:ring-offset-0">
                            <span>{{ $t }}</span>
                        </label>
                    @endforeach
                </x-form.multi-select>

                <x-form.multi-select label="Постачальники" :selectedCount="count($filterVendor)">
                    <label class="flex items-center gap-2 text-xs font-semibold text-amber-400 cursor-pointer py-1">
                        <input type="checkbox" value="null" wire:model.live="filterVendor" class="rounded border-white/10 bg-surface-900 text-brand-500 focus:ring-0 focus:ring-offset-0">
                        <span>[Не вказано / Null]</span>
                    </label>
                    @foreach($vendorsList as $vendor)
                        <label class="flex items-center gap-2 text-xs text-gray-300 hover:text-white cursor-pointer py-1">
                            <input type="checkbox" value="{{ $vendor->id }}" wire:model.live="filterVendor" class="rounded border-white/10 bg-surface-900 text-brand-500 focus:ring-0 focus:ring-offset-0">
                            <span>{{ $vendor->supplier_name }}</span>
                        </label>
                    @endforeach
                </x-form.multi-select>
            </div>
        </div>
    </x-ui.card>

    @if($isOpen)
    <x-ui.modal title="{{ $form->licenseId ? 'Редагувати' : 'Додати' }} ліцензію ПЗ" maxWidth="md">
        <div class="space-y-4">
            <div>
                    <x-form.input label="Назва ліцензії" model="form.license_name" type="text" placeholder="напр. Windows 11 Pro" />
                </div>

                <div>
                    <x-form.select label="Виробник/Постачальник" model="form.vendor_id">
                        <option value="">Оберіть постачальника</option>
                        @foreach($vendorsList as $vendor)
                            <option value="{{ $vendor->id }}">{{ $vendor->supplier_name }}</option>
                        @endforeach
                    </x-form.select>
                </div>

                <div>
                    <x-form.select label="Тип ліцензії" model="form.license_type" live="true">
                        <option value="">Оберіть тип ліцензії</option>
                        <option value="OEM">OEM</option>
                        <option value="Retail">Retail</option>
                        <option value="Корпоративна">Корпоративна</option>
                        <option value="Підписка">Підписка</option>
                        <option value="Безкоштовна">Безкоштовна</option>
                        <option value="Інше">Інше</option>
                    </x-form.select>
                </div>

                @if($form->license_type === 'Інше')
                <div>
                    <x-form.input label="Свій тип ліцензії" model="form.custom_license_type" type="text" placeholder="напр. Донгл, Тріальна" />
                </div>
                @endif

                <div>
                    <x-form.input label="Дата придбання" model="form.purchase_date" type="date" />
                </div>
        </div>
        </x-ui.modal>
    @endif

    {{-- Desktop --}}
    <x-table.wrapper>
            <x-slot name="headers">
                    <x-table.th align="left">ID</x-table.th>
                    <x-table.th align="left">Назва ліцензії</x-table.th>
                    <x-table.th align="left">Виробник/Постачальник</x-table.th>
                    <x-table.th align="left">Тип ліцензії</x-table.th>
                    <x-table.th align="left">Дата придбання</x-table.th>
                    <x-table.th align="right" width="32">Дії</x-table.th>
                </x-slot>
            
                @forelse($licenses as $lic)
                <x-table.tr>
                    <x-table.td align="left" class="text-gray-400 font-mono text-xs">#{{ $lic->id }}</x-table.td>
                    <x-table.td align="left" primary>{{ $lic->license_name }}</x-table.td>
                    <x-table.td align="left" class="text-gray-300 text-xs">{{ $lic->vendor?->supplier_name ?? '-' }}</x-table.td>
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
                        subtitle="Виробник: {{ $lic->vendor?->supplier_name ?? '-' }} | Тип: {{ $lic->license_type ?: '-' }}" 
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

    <div class="mt-4">
        {{ $licenses->links() }}
    </div>
</x-ui.page-wrapper>
</div>
