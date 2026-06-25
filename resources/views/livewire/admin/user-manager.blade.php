<div class="space-y-6">
    <x-ui.flash />
<x-ui.toolbar :count="count($users)" label="Всього" buttonLabel="Додати" />

    @if($isOpen)
    <x-ui.modal title="{{ $userId ? 'Редагувати' : 'Додати' }} адміністратора" maxWidth="lg">
            <div>
                    <x-form.input label="Ім'я" model="name" type="text" />
                </div>
                <div>
                    <x-form.input label="Email" model="email" type="email" />
                </div>
                <div>
                    <x-form.input label="Пароль {{ $userId ? '(залиште пустим, якщо не змінюєте)' : '' }}" model="password" type="password" />
                </div>
        </x-ui.modal>
    @endif

    {{-- Desktop --}}
    <x-table.wrapper>
            <x-slot name="headers">
                    <x-table.th align="left" width="20">ID</x-table.th>
                    <x-table.th align="left">Ім'я</x-table.th>
                    <x-table.th align="left">Email</x-table.th>
                    <x-table.th align="right" width="32">Дії</x-table.th>
                </x-slot>
            <tbody class="divide-y divide-white/5">
                @forelse($users as $user)
                <tr class="hover:bg-white/[0.02] transition-colors">
                    <x-table.td align="left" class="text-gray-500">#{{ $user->id }}</x-table.td>
                    <x-table.td align="left">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-brand-400 to-brand-600 flex items-center justify-center text-xs font-bold text-white shrink-0">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                            <span class="text-sm text-white font-medium">{{ $user->name }}</span>
                        </div>
                    </x-table.td>
                    <x-table.td align="left" class="text-gray-400">{{ $user->email }}</x-table.td>
                    <x-table.td align="right">
                        <x-ui.action-buttons id="{{ $user->id }}" />
                    </x-table.td>
                </tr>
                @empty
                <tr><x-table.td colspan="4" class="px-5 py-10 text-center text-gray-600 text-sm">Немає записів</x-table.td></tr>
                @endforelse
            </tbody>
        </x-table.wrapper>

    {{-- Mobile --}}
    <x-table.mobile-list>
        @forelse($users as $user)
        <div class="bg-surface-800/50 border border-white/5 rounded-xl p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-brand-400 to-brand-600 flex items-center justify-center text-sm font-bold text-white shrink-0">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
            <div class="flex-1 min-w-0">
                <p class="text-sm text-white font-medium truncate">{{ $user->name }}</p>
                <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
            </div>
            <div class="flex gap-1 shrink-0">
                <button wire:click="edit({{ $user->id }})" class="p-2 rounded-lg text-gray-500 hover:text-brand-400 hover:bg-brand-500/10 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                <button wire:click="delete({{ $user->id }})" class="p-2 rounded-lg text-gray-500 hover:text-red-400 hover:bg-red-500/10 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
            </div>
        </div>
        @empty
        <div class="text-center py-10 text-gray-600 text-sm">Немає записів</div>
        @endforelse
    </x-table.mobile-list>
</div>
