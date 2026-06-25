<div class="space-y-6">
    <x-ui.flash />
<x-ui.toolbar :count="count($materials)" label="Всього" buttonLabel="Додати" />

    @if($isOpen)
    <x-ui.modal title="{{ $materialId ? 'Редагувати' : 'Додати' }} матеріал" maxWidth="md">
            <x-form.input label="Назва матеріалу" model="material_name" type="text" />
        </x-ui.modal>
    @endif

    {{-- Desktop --}}
    <x-table.wrapper>
            <x-slot name="headers">
                    <x-table.th align="left" width="20">ID</x-table.th>
                    <x-table.th align="left">Назва</x-table.th>
                    <x-table.th align="right" width="32">Дії</x-table.th>
                </x-slot>
            
                @forelse($materials as $mat)
                <x-table.tr>
                    <x-table.td align="left" muted>#{{ $mat->id }}</x-table.td>
                    <x-table.td align="left" primary class="text-white font-medium">{{ $mat->material_name }}</x-table.td>
                    <x-table.td align="right">
                        <x-ui.action-buttons id="{{ $mat->id }}" />
                    </x-table.td>
                </x-table.tr>
                @empty
                <x-table.tr><x-table.td colspan="3" class="px-5 py-10 text-center text-gray-600 text-sm">Немає записів</x-table.td></x-table.tr>
                @endforelse
            
        </x-table.wrapper>

    {{-- Mobile --}}
    <x-table.mobile-list>
        @forelse($materials as $mat)
        <x-table.mobile-card>
            <div>
                <p class="text-sm text-white font-medium">{{ $mat->material_name }}</p>
                <p class="text-xs text-gray-500">ID: {{ $mat->id }}</p>
            </div>
            <x-ui.action-buttons id="{{ $mat->id }}" />
        </x-table.mobile-card>
        @empty
        <div class="text-center py-10 text-gray-600 text-sm">Немає записів</div>
        @endforelse
    </x-table.mobile-list>
</div>
