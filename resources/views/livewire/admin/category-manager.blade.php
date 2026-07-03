<div>
<x-ui.page-wrapper>
    <x-ui.flash />
<x-ui.toolbar :count="count($categories)" label="Всього" buttonLabel="Додати" />

    @if($isOpen)
    <x-ui.modal title="{{ $categoryId ? 'Редагувати' : 'Додати' }} категорію" maxWidth="md">
            <x-form.input label="Назва категорії" model="category_name" type="text" />
        </x-ui.modal>
    @endif

    {{-- Desktop --}}
    <x-table.wrapper>
            <x-slot name="headers">
                    <x-table.th align="left" width="20">ID</x-table.th>
                    <x-table.th align="left">Назва</x-table.th>
                    <x-table.th align="right" width="32">Дії</x-table.th>
                </x-slot>
            
                @forelse($categories as $cat)
                <x-table.tr>
                    <x-table.td align="left" muted>#{{ $cat->id }}</x-table.td>
                    <x-table.td align="left" primary>{{ $cat->category_name }}</x-table.td>
                    <x-table.td align="right">
                        <x-ui.action-buttons id="{{ $cat->id }}" />
                    </x-table.td>
                </x-table.tr>
                @empty
                <x-table.empty colspan="3" />
                @endforelse
            
        </x-table.wrapper>

    {{-- Mobile --}}
    <x-table.mobile-list>
        @forelse($categories as $cat)
        <x-table.mobile-card>
            <x-ui.text-block title="{{ $cat->category_name }}" subtitle="ID: {{ $cat->id }}" />
            <x-ui.action-buttons id="{{ $cat->id }}" />
        </x-table.mobile-card>
        @empty
        <x-table.mobile-empty />
        @endforelse
    </x-table.mobile-list>
</x-ui.page-wrapper>
</div>
