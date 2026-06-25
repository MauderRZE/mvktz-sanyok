<div class="space-y-6">
    {{-- Flash Message --}}
    <x-flash />
{{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <p class="text-sm text-gray-500">Всього записів: <span class="text-gray-300 font-medium">{{ count($equipments) }}</span></p>
        </div>
        <button wire:click="create()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-brand-600 to-brand-500 hover:from-brand-500 hover:to-brand-400 text-white text-sm font-semibold rounded-xl shadow-lg shadow-brand-500/20 hover:shadow-brand-500/30 transition-all duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Додати
        </button>
    </div>

    {{-- Modal --}}
    @if($isOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4" x-data x-init="document.body.style.overflow='hidden'" x-destroy="document.body.style.overflow=''">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeModal()"></div>
        <div class="relative w-full max-w-lg bg-surface-800 border border-white/5 rounded-2xl shadow-2xl fade-in overflow-hidden">
            <div class="px-6 py-4 border-b border-white/5 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white">{{ $equipmentId ? 'Редагувати' : 'Додати' }} обладнання</h3>
                <button wire:click="closeModal()" class="text-gray-500 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Інвентарний номер</label>
                    <input type="text" wire:model="inventory_number" class="w-full px-4 py-2.5 bg-surface-900/60 border border-white/10 rounded-xl text-white text-sm placeholder-gray-600 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                    @error('inventory_number') <span class="text-xs text-red-400 mt-1">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Назва (бухгалтерська)</label>
                    <input type="text" wire:model="accounting_name" class="w-full px-4 py-2.5 bg-surface-900/60 border border-white/10 rounded-xl text-white text-sm placeholder-gray-600 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                    @error('accounting_name') <span class="text-xs text-red-400 mt-1">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Тип</label>
                    <select wire:model="equipment_type_id" class="w-full px-4 py-2.5 bg-surface-900/60 border border-white/10 rounded-xl text-white text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                        <option value="" class="bg-surface-800">— Оберіть тип —</option>
                        @foreach($types as $t)
                            <option value="{{ $t->id }}" class="bg-surface-800">{{ $t->type_name }}</option>
                        @endforeach
                    </select>
                    @error('equipment_type_id') <span class="text-xs text-red-400 mt-1">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Статус</label>
                    <select wire:model="status" class="w-full px-4 py-2.5 bg-surface-900/60 border border-white/10 rounded-xl text-white text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                        <option value="В експлуатації" class="bg-surface-800">В експлуатації</option>
                        <option value="На складі" class="bg-surface-800">На складі</option>
                        <option value="Списано" class="bg-surface-800">Списано</option>
                    </select>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-white/5 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                <button wire:click="closeModal()" class="px-5 py-2.5 text-sm font-medium text-gray-400 hover:text-white bg-white/5 hover:bg-white/10 rounded-xl transition-colors">Скасувати</button>
                <button wire:click.prevent="store()" class="px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-brand-600 to-brand-500 hover:from-brand-500 hover:to-brand-400 rounded-xl shadow-lg shadow-brand-500/20 transition-all">Зберегти</button>
            </div>
        </div>
    </div>
    @endif

    {{-- Desktop Table --}}
    <div class="hidden md:block bg-surface-800/50 border border-white/5 rounded-2xl overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="border-b border-white/5">
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Інв. №</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Назва</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Тип</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Статус</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Дії</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($equipments as $eq)
                <tr class="hover:bg-white/[0.02] transition-colors">
                    <td class="px-5 py-3 text-sm text-gray-500">#{{ $eq->id }}</td>
                    <td class="px-5 py-3 text-sm text-white font-medium">{{ $eq->inventory_number }}</td>
                    <td class="px-5 py-3 text-sm text-gray-300">{{ $eq->accounting_name }}</td>
                    <td class="px-5 py-3 text-sm"><span class="px-2.5 py-1 rounded-lg bg-brand-500/10 text-brand-300 text-xs font-medium">{{ $eq->type->type_name ?? '—' }}</span></td>
                    <td class="px-5 py-3 text-sm">
                        @if($eq->status == 'В експлуатації')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-500/10 text-emerald-400 text-xs font-medium"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>{{ $eq->status }}</span>
                        @elseif($eq->status == 'На складі')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-amber-500/10 text-amber-400 text-xs font-medium"><span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>{{ $eq->status }}</span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-red-500/10 text-red-400 text-xs font-medium"><span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>{{ $eq->status }}</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-right">
                        <x-action-buttons id="{{ $eq->id }}" />
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-10 text-center text-gray-600 text-sm">Немає записів</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile Cards --}}
    <div class="md:hidden space-y-3">
        @forelse($equipments as $eq)
        <div class="bg-surface-800/50 border border-white/5 rounded-xl p-4 space-y-3">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-white font-medium text-sm">{{ $eq->accounting_name }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">Інв. № {{ $eq->inventory_number }}</p>
                </div>
                @if($eq->status == 'В експлуатації')
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg bg-emerald-500/10 text-emerald-400 text-xs font-medium"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>{{ $eq->status }}</span>
                @elseif($eq->status == 'На складі')
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg bg-amber-500/10 text-amber-400 text-xs font-medium"><span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>{{ $eq->status }}</span>
                @else
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg bg-red-500/10 text-red-400 text-xs font-medium"><span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>{{ $eq->status }}</span>
                @endif
            </div>
            <div class="flex items-center justify-between pt-2 border-t border-white/5">
                <span class="px-2 py-0.5 rounded-lg bg-brand-500/10 text-brand-300 text-xs font-medium">{{ $eq->type->type_name ?? '—' }}</span>
                <x-action-buttons id="{{ $eq->id }}" />
            </div>
        </div>
        @empty
        <div class="text-center py-10 text-gray-600 text-sm">Немає записів</div>
        @endforelse
    </div>
</div>
