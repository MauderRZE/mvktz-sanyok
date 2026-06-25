<div class="space-y-6">
    <x-flash />
<x-toolbar :count="count($suppliers)" label="Всього" buttonLabel="Додати" />

    @if($isOpen)
    <x-modal title="{{ $supplierId ? 'Редагувати' : 'Додати' }} постачальника" maxWidth="md">
            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Назва постачальника</label>
                <input type="text" wire:model="supplier_name" class="w-full px-4 py-2.5 bg-surface-900/60 border border-white/10 rounded-xl text-white text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
                @error('supplier_name') <span class="text-xs text-red-400 mt-1">{{ $message }}</span>@enderror
        </x-modal>
    @endif

    {{-- Desktop --}}
    <div class="hidden md:block bg-surface-800/50 border border-white/5 rounded-2xl overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="border-b border-white/5">
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-20">ID</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Назва</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider w-32">Дії</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($suppliers as $sup)
                <tr class="hover:bg-white/[0.02] transition-colors">
                    <td class="px-5 py-3 text-sm text-gray-500">#{{ $sup->id }}</td>
                    <td class="px-5 py-3 text-sm text-white font-medium">{{ $sup->supplier_name }}</td>
                    <td class="px-5 py-3 text-right">
                        <x-action-buttons id="{{ $sup->id }}" />
                    </td>
                </tr>
                @empty
                <tr><td colspan="3" class="px-5 py-10 text-center text-gray-600 text-sm">Немає записів</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile --}}
    <div class="md:hidden space-y-3">
        @forelse($suppliers as $sup)
        <div class="bg-surface-800/50 border border-white/5 rounded-xl p-4 flex items-center justify-between">
            <div>
                <p class="text-sm text-white font-medium">{{ $sup->supplier_name }}</p>
                <p class="text-xs text-gray-500">ID: {{ $sup->id }}</p>
            </div>
            <x-action-buttons id="{{ $sup->id }}" />
        </div>
        @empty
        <div class="text-center py-10 text-gray-600 text-sm">Немає записів</div>
        @endforelse
    </div>
</div>
