<div>
<x-ui.page-wrapper>
    <x-ui.flash />
<x-ui.toolbar :count="count($types)" label="Всього" buttonLabel="Додати" />

    @if($isOpen)
    <x-ui.modal title="{{ $typeId ? 'Редагувати' : 'Додати' }} модель" maxWidth="md">
            <div class="space-y-4">
                <x-form.select label="Бренд" model="brand_id">
                    <option value="">Оберіть бренд</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}">{{ $brand->brandtz_name }}</option>
                    @endforeach
                </x-form.select>
                <x-form.input label="Назва моделі" model="model_name" type="text" />
            </div>
        </x-ui.modal>
    @endif

    {{-- Desktop --}}
    <x-table.wrapper>
            <x-slot name="headers">
                    <x-table.th align="left" width="20">ID</x-table.th>
                    <x-table.th align="left">Бренд</x-table.th>
                    <x-table.th align="left">Назва</x-table.th>
                    <x-table.th align="right" width="32">Дії</x-table.th>
                </x-slot>
            
                @forelse($types as $type)
                <x-table.tr>
                    <x-table.td align="left" muted>#{{ $type->id }}</x-table.td>
                    <x-table.td align="left" class="text-gray-400 text-sm">
                        {{ $type->brand->brandtz_name ?? '-' }}
                    </x-table.td>
                    <x-table.td align="left" primary>
                        <span class="inline-flex items-center gap-2">
                            <svg class="w-4 h-4 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                            {{ $type->model_name }}
                        </span>
                    </x-table.td>
                    <x-table.td align="right">
                        <x-ui.action-buttons id="{{ $type->id }}" />
                    </x-table.td>
                </x-table.tr>
                @empty
                <x-table.empty colspan="4" />
                @endforelse
            
        </x-table.wrapper>

    {{-- Mobile --}}
    <x-table.mobile-list>
        @forelse($types as $type)
        <x-table.mobile-card>
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-brand-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                <x-ui.text-block title="{{ $type->model_name }}" subtitle="Бренд: {{ $type->brand->brandtz_name ?? '-' }}" />
            </div>
            <x-ui.action-buttons id="{{ $type->id }}" />
        </x-table.mobile-card>
        @empty
        <x-table.mobile-empty />
        @endforelse
    </x-table.mobile-list>
</x-ui.page-wrapper>
</div>
