<div class="w-full">
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
        @if (session()->has('message'))
            <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md my-3" role="alert">
                <div class="flex">
                    <div>
                        <p class="text-sm">{{ session('message') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <button wire:click="create()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded my-3">
            Додати Співробітника
        </button>

        @if($isOpen)
            @include('livewire.admin.employee-modal')
        @endif

        <table class="table-fixed w-full mt-4 bg-white border border-gray-200">
            <thead class="bg-gray-100">
                <tr class="text-left">
                    <th class="px-4 py-2 w-20 border">ID</th>
                    <th class="px-4 py-2 border">Прізвище</th>
                    <th class="px-4 py-2 border">Ім'я</th>
                    <th class="px-4 py-2 border">Посада</th>
                    <th class="px-4 py-2 border">Відділ</th>
                    <th class="px-4 py-2 w-48 border">Дії</th>
                </tr>
            </thead>
            <tbody>
                @foreach($employees as $employee)
                <tr>
                    <td class="border px-4 py-2">{{ $employee->id }}</td>
                    <td class="border px-4 py-2">{{ $employee->last_name }}</td>
                    <td class="border px-4 py-2">{{ $employee->first_name }}</td>
                    <td class="border px-4 py-2">{{ $employee->position }}</td>
                    <td class="border px-4 py-2">{{ $employee->department }}</td>
                    <td class="border px-4 py-2">
                        <button wire:click="edit({{ $employee->id }})" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded">Edit</button>
                        <button wire:click="delete({{ $employee->id }})" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded">Del</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
