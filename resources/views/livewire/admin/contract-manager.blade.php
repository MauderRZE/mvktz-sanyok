<div class="space-y-6">
    <x-flash />
<x-toolbar :count="count($contracts)" label="Всього" buttonLabel="Додати" />

    @if($isOpen)
    <x-modal title="{{ $contractId ? 'Редагувати' : 'Додати' }} договір" maxWidth="md">
            <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Номер договору</label>
                    <input type="text" wire:model="contract_number" class="w-full px-4 py-2.5 bg-surface-900/60 border border-white/10 rounded-xl text-white text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                    @error('contract_number') <span class="text-xs text-red-400 mt-1">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Дата договору</label>
                    <input type="date" wire:model="contract_date" class="w-full px-4 py-2.5 bg-surface-900/60 border border-white/10 rounded-xl text-white text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                    @error('contract_date') <span class="text-xs text-red-400 mt-1">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Постачальник</label>
                    <select wire:model="supplier_id" class="w-full px-4 py-2.5 bg-surface-900/60 border border-white/10 rounded-xl text-white text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                        <option value="">Оберіть постачальника...</option>
                        @foreach($suppliersList as $sup)
                            <option value="{{ $sup->id }}">{{ $sup->supplier_name }}</option>
                        @endforeach
                    </select>
                    @error('supplier_id') <span class="text-xs text-red-400 mt-1">{{ $message }}</span>@enderror
                </div>
        </x-modal>
    @endif

    {{-- Desktop --}}
    <div class="hidden md:block bg-surface-800/50 border border-white/5 rounded-2xl overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="border-b border-white/5">
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-20">ID</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Номер</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Дата</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Постачальник</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider w-32">Дії</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($contracts as $c)
                <tr class="hover:bg-white/[0.02] transition-colors">
                    <td class="px-5 py-3 text-sm text-gray-500">#{{ $c->id }}</td>
                    <td class="px-5 py-3 text-sm text-white font-medium">{{ $c->contract_number }}</td>
                    <td class="px-5 py-3 text-sm text-gray-300">{{ $c->contract_date }}</td>
                    <td class="px-5 py-3 text-sm text-gray-400">{{ $c->supplier->supplier_name ?? '-' }}</td>
                    <td class="px-5 py-3 text-right">
                        <x-action-buttons id="{{ $c->id }}" />
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-5 py-10 text-center text-gray-600 text-sm">Немає записів</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile --}}
    <div class="md:hidden space-y-3">
        @forelse($contracts as $c)
        <div class="bg-surface-800/50 border border-white/5 rounded-xl p-4 flex flex-col gap-2">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-white font-medium">Договір №{{ $c->contract_number }}</p>
                    <p class="text-xs text-gray-500">ID: {{ $c->id }} | Дата: {{ $c->contract_date }}</p>
                </div>
                <x-action-buttons id="{{ $c->id }}" />
            </div>
            <div class="text-xs text-gray-400 border-t border-white/5 pt-2">
                Постачальник: {{ $c->supplier->supplier_name ?? '-' }}
            </div>
        </div>
        @empty
        <div class="text-center py-10 text-gray-600 text-sm">Немає записів</div>
        @endforelse
    </div>
</div>
