<x-ui.page-wrapper>
    <x-ui.flash />
<x-ui.toolbar :count="count($users)" label="Всього" buttonLabel="Додати" />

    @if($isOpen)
    <x-ui.modal title="{{ $userId ? 'Редагувати' : 'Додати' }} адміністратора" maxWidth="lg">
            <div>
                    <x-form.input label="Ім'я" model="name" type="text" />
                </div>
                <div>
                    <x-form.input label="Логін" model="login" type="text" />
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
                    <x-table.th align="left">Логін</x-table.th>
                    <x-table.th align="right" width="32">Дії</x-table.th>
                </x-slot>
            
                @forelse($users as $user)
                <x-table.tr>
                    <x-table.td align="left" muted>#{{ $user->id }}</x-table.td>
                    <x-table.td align="left">
                    <x-ui.avatar-cell :name="$user->name" :title="$user->name" />
                </x-table.td>
                    <x-table.td align="left" class="text-gray-400">{{ $user->login }}</x-table.td>
                    <x-table.td align="right">
                         <x-ui.action-buttons id="{{ $user->id }}" />
                    </x-table.td>
                </x-table.tr>
                @empty
                <x-table.empty colspan="4" />
                @endforelse
            
        </x-table.wrapper>

    {{-- Mobile --}}
    <x-table.mobile-list>
        @forelse($users as $user)
        <x-table.mobile-card layout="gap-3">
            <x-ui.avatar-cell class="flex-1" size="lg" :name="$user->name" :title="$user->name" :subtitle="$user->login" />
            <x-ui.action-buttons id="{{ $user->id }}" />
        </x-table.mobile-card>
        @empty
        <x-table.mobile-empty />
        @endforelse
    </x-table.mobile-list>
</x-ui.page-wrapper>
