<div>
<x-ui.page-wrapper>
    {{-- Flash Message --}}
    <x-ui.flash />

    {{-- Дочірні Livewire v3 компоненти --}}
    <livewire:admin.equipment.equipment-form />
    <livewire:admin.equipment.equipment-detail />

    {{-- Header & Buttons --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <p class="text-sm text-gray-500">Всього записів: <span class="text-gray-300 font-medium">{{ $equipments->total() }}</span></p>
        </div>
        <x-ui.button wire:click="create()">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Додати
        </x-ui.button>
    </div>

    {{-- Filters Bar --}}
    <x-ui.card class="p-4 space-y-4">
        <div class="flex flex-col lg:flex-row gap-4 items-center">
            <div class="flex-1 w-full flex gap-2">
                <div class="flex-1">
                    <x-form.search wire:model.live.debounce.300ms="search" placeholder="Пошук за інвентарним номером або назвою..." />
                </div>
                @if($search !== '' || !empty($filterType) || !empty($filterStatus) || !empty($filterLocation) || !empty($filterEmployee) || !empty($filterCategory) || !empty($filterBrand) || !empty($filterDepartment) || !empty($filterOrganization))
                    <button wire:click="resetFilters" class="px-4 py-2 bg-white/5 hover:bg-white/10 text-xs text-gray-400 hover:text-white rounded-xl border border-white/10 transition-colors shrink-0 flex items-center gap-1.5" title="Скинути всі фільтри">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        <span>Скинути</span>
                    </button>
                @endif
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-3 w-full lg:w-auto">
                <x-form.multi-select label="Категорії" :selectedCount="count($filterCategory)">
                    @foreach($categoriesList as $cat)
                        <label class="flex items-center gap-2 text-xs text-gray-300 hover:text-white cursor-pointer py-1">
                            <input type="checkbox" value="{{ $cat->id }}" wire:model.live="filterCategory" class="rounded border-white/10 bg-surface-900 text-brand-500 focus:ring-0 focus:ring-offset-0">
                            <span>{{ $cat->category_name }}</span>
                        </label>
                    @endforeach
                </x-form.multi-select>

                <x-form.multi-select label="Бренди" :selectedCount="count($filterBrand)">
                    @foreach($brandsList as $b)
                        <label class="flex items-center gap-2 text-xs text-gray-300 hover:text-white cursor-pointer py-1">
                            <input type="checkbox" value="{{ $b->id }}" wire:model.live="filterBrand" class="rounded border-white/10 bg-surface-900 text-brand-500 focus:ring-0 focus:ring-offset-0">
                            <span>{{ $b->brandtz_name }}</span>
                        </label>
                    @endforeach
                </x-form.multi-select>

                <x-form.multi-select label="Моделі" :selectedCount="count($filterType)">
                    @foreach($types as $t)
                        <label class="flex items-center gap-2 text-xs text-gray-300 hover:text-white cursor-pointer py-1">
                            <input type="checkbox" value="{{ $t->id }}" wire:model.live="filterType" class="rounded border-white/10 bg-surface-900 text-brand-500 focus:ring-0 focus:ring-offset-0">
                            <span>{{ $t->model_name }}</span>
                        </label>
                    @endforeach
                </x-form.multi-select>

                <x-form.multi-select label="Статуси" :selectedCount="count($filterStatus)">
                    @foreach(['В експлуатації', 'На складі', 'Списано'] as $status)
                        <label class="flex items-center gap-2 text-xs text-gray-300 hover:text-white cursor-pointer py-1">
                            <input type="checkbox" value="{{ $status }}" wire:model.live="filterStatus" class="rounded border-white/10 bg-surface-900 text-brand-500 focus:ring-0 focus:ring-offset-0">
                            <span>{{ $status }}</span>
                        </label>
                    @endforeach
                </x-form.multi-select>

                <x-form.multi-select label="Локації" :selectedCount="count($filterLocation)">
                    @foreach($locationsList as $loc)
                        <label class="flex items-center gap-2 text-xs text-gray-300 hover:text-white cursor-pointer py-1">
                            <input type="checkbox" value="{{ $loc->id }}" wire:model.live="filterLocation" class="rounded border-white/10 bg-surface-900 text-brand-500 focus:ring-0 focus:ring-offset-0">
                            <span>Каб. {{ $loc->room_number }}</span>
                        </label>
                    @endforeach
                </x-form.multi-select>

                <x-form.multi-select label="Співробітники" :selectedCount="count($filterEmployee)">
                    @foreach($employeesList as $emp)
                        <label class="flex items-center gap-2 text-xs text-gray-300 hover:text-white cursor-pointer py-1">
                            <input type="checkbox" value="{{ $emp->id }}" wire:model.live="filterEmployee" class="rounded border-white/10 bg-surface-900 text-brand-500 focus:ring-0 focus:ring-offset-0">
                            <span>{{ $emp->last_name }} {{ mb_substr($emp->first_name, 0, 1) }}.</span>
                        </label>
                    @endforeach
                </x-form.multi-select>

                <x-form.multi-select label="Відділи" :selectedCount="count($filterDepartment)">
                    @foreach($departmentsList as $dep)
                        <label class="flex items-center gap-2 text-xs text-gray-300 hover:text-white cursor-pointer py-1">
                            <input type="checkbox" value="{{ $dep->id }}" wire:model.live="filterDepartment" class="rounded border-white/10 bg-surface-900 text-brand-500 focus:ring-0 focus:ring-offset-0">
                            <span>{{ $dep->name }}</span>
                        </label>
                    @endforeach
                </x-form.multi-select>

                <x-form.multi-select label="Організації" :selectedCount="count($filterOrganization)">
                    @foreach($organizationsList as $org)
                        <label class="flex items-center gap-2 text-xs text-gray-300 hover:text-white cursor-pointer py-1">
                            <input type="checkbox" value="{{ $org->id }}" wire:model.live="filterOrganization" class="rounded border-white/10 bg-surface-900 text-brand-500 focus:ring-0 focus:ring-offset-0">
                            <span>{{ $org->org_name }}</span>
                        </label>
                    @endforeach
                </x-form.multi-select>
            </div>
        </div>
    </x-ui.card>

    {{-- Desktop Table --}}
    <x-table.wrapper>
        <x-slot name="headers">
            <x-table.th align="left" wire:click="sortBy('id')" class="cursor-pointer hover:bg-white/5">
                <div class="flex items-center gap-1">ID @if($sortField === 'id') <span class="text-brand-400">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif</div>
            </x-table.th>
            <x-table.th align="left" wire:click="sortBy('inv_number')" class="cursor-pointer hover:bg-white/5">
                <div class="flex items-center gap-1">Інв. № @if($sortField === 'inv_number') <span class="text-brand-400">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif</div>
            </x-table.th>
            <x-table.th align="left" wire:click="sortBy('account_name')" class="cursor-pointer hover:bg-white/5">
                <div class="flex items-center gap-1">Назва @if($sortField === 'account_name') <span class="text-brand-400">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif</div>
            </x-table.th>
            <x-table.th align="left">Комплектуючі</x-table.th>
            <x-table.th align="left">Розташування / Відпов.</x-table.th>
            <x-table.th align="left">Статистика</x-table.th>
            <x-table.th align="left" wire:click="sortBy('status')" class="cursor-pointer hover:bg-white/5">
                <div class="flex items-center gap-1">Статус @if($sortField === 'status') <span class="text-brand-400">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif</div>
            </x-table.th>
            <x-table.th align="right">Дії</x-table.th>
        </x-slot>

        @forelse($equipments as $eq)
        <x-table.tr>
            <x-table.td align="left" muted>#{{ $eq->id }}</x-table.td>
            <x-table.td align="left" primary>{{ $eq->inv_number }}</x-table.td>
            <x-table.td align="left">{{ $eq->account_name }}</x-table.td>
            <x-table.td align="left" class="text-xs text-gray-300">
                @if($eq->assets->count() > 0)
                    <div class="font-medium text-gray-200">{{ $eq->assets->count() }} од.</div>
                    <x-table.cell-subtext class="max-w-[150px] truncate" title="{{ $eq->assets->map(fn($c) => ($c->componentType->component_name ?? '') . ($c->brand_model ? ' (' . $c->brand_model . ')' : ''))->implode(', ') }}">
                        {{ $eq->assets->map(fn($c) => $c->componentType->component_name ?? '')->unique()->implode(', ') }}
                    </x-table.cell-subtext>
                @else
                    <span class="text-gray-600">—</span>
                @endif
            </x-table.td>
            <x-table.td align="left" class="text-xs text-gray-300">
                @php $latestMove = $eq->movements->sortByDesc('move_date')->first(); @endphp
                @if($latestMove)
                    <div class="font-medium text-brand-300">Каб. {{ $latestMove->location->room_number ?? '—' }}</div>
                    <x-table.cell-subtext>
                        {{ $latestMove->employee ? ($latestMove->employee->last_name . ' ' . mb_substr($latestMove->employee->first_name, 0, 1) . '.') : '—' }}
                    </x-table.cell-subtext>
                @else
                    <span class="text-gray-600">Немає руху</span>
                @endif
            </x-table.td>
            <x-table.td align="left" class="text-xs">
                <div class="flex items-center gap-1.5">
                    @if($eq->buy_price)
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-gray-700/50 text-gray-300 border border-white/10" title="Ціна">
                            💰 {{ number_format($eq->buy_price, 0) }}
                        </span>
                    @endif
                    @if($eq->purchase_id)
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-blue-500/10 text-blue-400 border border-blue-500/20" title="Є договір">📄</span>
                    @endif
                    @if($eq->retirement_act_id)
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-red-500/10 text-red-400 border border-red-500/20" title="Акт списання">🗑</span>
                    @endif
                    @if(!$eq->buy_price && !$eq->purchase_id && !$eq->retirement_act_id)
                        <span class="text-gray-600">—</span>
                    @endif
                </div>
            </x-table.td>
            <x-table.td align="left">
                <x-ui.badge status="{{ $eq->status }}" />
            </x-table.td>
            <x-table.td align="right">
                <x-ui.action-buttons id="{{ $eq->id }}" :viewAction="true" />
            </x-table.td>
        </x-table.tr>
        @empty
        <x-table.empty colspan="8" />
        @endforelse
    </x-table.wrapper>

    {{-- Mobile Cards --}}
    <x-table.mobile-list>
        @forelse($equipments as $eq)
        <x-table.mobile-card layout="y-3">
            <x-table.mobile-card-header align="start">
                <x-ui.text-block title="{{ $eq->account_name }}" subtitle="Інв. № {{ $eq->inv_number }}" />
                <x-ui.badge status="{{ $eq->status }}" />
            </x-table.mobile-card-header>
            <x-table.mobile-card-footer flex="true">
                <span class="px-2 py-0.5 rounded-lg bg-brand-500/10 text-brand-300 text-xs font-medium">
                    {{ $eq->assets->first()?->componentType?->component_name ?? 'Обладнання' }}
                </span>
                <x-ui.action-buttons id="{{ $eq->id }}" :viewAction="true" />
            </x-table.mobile-card-footer>
        </x-table.mobile-card>
        @empty
        <x-table.mobile-empty />
        @endforelse
    </x-table.mobile-list>

    <div class="mt-4">
        {{ $equipments->links() }}
    </div>
</x-ui.page-wrapper>
</div>
