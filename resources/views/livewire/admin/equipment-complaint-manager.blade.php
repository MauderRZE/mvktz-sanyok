<div class="space-y-6">
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
                <div class="fade-in">
                    <x-form.input label="Дата вирішення" model="resolution_date" type="date" />
                </div>
                @endif

                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Опис несправності / проблеми</label>
                    <textarea wire:model="issue_description" rows="3" class="w-full px-4 py-2.5 bg-surface-900/60 border border-white/10 rounded-xl text-white text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors" placeholder="Детально опишіть проблему..."></textarea>
                    @error('issue_description') <span class="text-xs text-red-400 mt-1">{{ $message }}</span>@enderror
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
                <x-table.tr class="text-sm">
                    <x-table.td align="left" class="text-gray-400 whitespace-nowrap">{{ $c->complaint_date }}</x-table.td>
                    <x-table.td align="left" primary class="text-white font-medium">
                        {{ $c->equipment->inventory_number ?? '-' }}
                        <span class="block text-[10px] text-gray-500">{{ $c->equipment->accounting_name ?? '' }}</span>
                    </x-table.td>
                    <x-table.td align="left" class="text-gray-300">{{ $c->employee->fullName ?? 'Не вказано' }}</x-table.td>
                    <x-table.td class="max-w-xs truncate" title="{{ $c->issue_description }}">{{ $c->issue_description }}</x-table.td>
                    <x-table.td align="left">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium 
                            @if($c->resolution_status == 'Вирішено') bg-emerald-500/10 text-emerald-400
                            @elseif($c->resolution_status == 'В роботі') bg-amber-500/10 text-amber-400
                            @else bg-red-500/10 text-red-400 @endif">
                            <span class="w-1.5 h-1.5 rounded-full 
                                @if($c->resolution_status == 'Вирішено') bg-emerald-400
                                @elseif($c->resolution_status == 'В роботі') bg-amber-400
                                @else bg-red-400 @endif"></span>
                            {{ $c->resolution_status }}
                            @if($c->resolution_date && $c->resolution_status == 'Вирішено')
                                <span class="text-[10px] text-emerald-500 ml-1">({{ $c->resolution_date }})</span>
                            @endif
                        </span>
                    </x-table.td>
                    <x-table.td align="right">
                        <x-ui.action-buttons id="{{ $c->id }}" />
                    </x-table.td>
                </x-table.tr>
                @empty
                <x-table.tr><x-table.td colspan="6" class="px-5 py-10 text-center text-gray-600">Немає записів</x-table.td></x-table.tr>
                @endforelse
            
        </x-table.wrapper>

    {{-- Mobile --}}
    <x-table.mobile-list>
        @forelse($complaints as $c)
        <x-table.mobile-card layout="y-2">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-xs text-gray-500">{{ $c->complaint_date }}</span>
                    <p class="text-sm text-white font-medium">Обладнання: {{ $c->equipment->inventory_number ?? '-' }}</p>
                    <p class="text-xs text-gray-400">Скаржник: {{ $c->employee->fullName ?? 'Не вказано' }}</p>
                </div>
                <div class="flex gap-1 shrink-0">
                    <button wire:click="edit({{ $c->id }})" class="p-2 rounded-lg text-gray-500 hover:text-brand-400 hover:bg-brand-500/10 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                    <button wire:click="delete({{ $c->id }})" class="p-2 rounded-lg text-gray-500 hover:text-red-400 hover:bg-red-500/10 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                </div>
            </div>
            
            <p class="text-xs text-gray-300 bg-surface-900/60 p-2.5 rounded-lg border border-white/5">
                {{ $c->issue_description }}
            </p>

            <div class="flex items-center justify-between border-t border-white/5 pt-2">
                <span class="text-xs text-gray-500">Статус:</span>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium 
                    @if($c->resolution_status == 'Вирішено') bg-emerald-500/10 text-emerald-400
                    @elseif($c->resolution_status == 'В роботі') bg-amber-500/10 text-amber-400
                    @else bg-red-500/10 text-red-400 @endif">
                    {{ $c->resolution_status }}
                </span>
            </div>
        </x-table.mobile-card>
        @empty
        <div class="text-center py-10 text-gray-600 text-sm">Немає записів</div>
        @endforelse
    </x-table.mobile-list>
</div>
