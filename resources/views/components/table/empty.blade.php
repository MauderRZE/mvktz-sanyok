@props(['colspan'])

<x-table.tr>
    <x-table.td colspan="{{ $colspan }}" class="px-5 py-10 text-center text-gray-600 text-sm">
        {{ $slot->isEmpty() ? 'Немає записів' : $slot }}
    </x-table.td>
</x-table.tr>
