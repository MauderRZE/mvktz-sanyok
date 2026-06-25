<div class="w-full">
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
        @if (session()->has('message'))
            <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md my-3">
                <p class="text-sm">{{ session('message') }}</p>
            </div>
        @endif

        <button wire:click="create()" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded my-3">
            Додати Адміністратора
        </button>

        @if($isOpen)
            <div class="fixed z-50 inset-0 overflow-y-auto ease-out duration-400">
              <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity">
                  <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                  <form>
                  <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                      <div class="mb-4">
                          <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Ім'я:</label>
                          <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" wire:model="name">
                          @error('name') <span class="text-red-500">{{ $message }}</span>@enderror
                      </div>
                      <div class="mb-4">
                          <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
                          <input type="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="email" wire:model="email">
                          @error('email') <span class="text-red-500">{{ $message }}</span>@enderror
                      </div>
                      <div class="mb-4">
                          <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Пароль (залиште пустим, якщо не змінюєте):</label>
                          <input type="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="password" wire:model="password">
                          @error('password') <span class="text-red-500">{{ $message }}</span>@enderror
                      </div>
                  </div>
                  <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click.prevent="store()" type="button" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-indigo-600 text-base leading-6 font-bold text-white shadow-sm hover:bg-indigo-500 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                      Зберегти
                    </button>
                    <button wire:click="closeModal()" type="button" class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto sm:text-sm border-gray-300 px-4 py-2 bg-white text-gray-700 hover:text-gray-500 border">
                      Скасувати
                    </button>
                  </div>
                  </form>
                </div>
              </div>
            </div>
        @endif

        <table class="table-fixed w-full mt-4 bg-white border border-gray-200">
            <thead class="bg-gray-100">
                <tr class="text-left">
                    <th class="px-4 py-2 w-20 border">ID</th>
                    <th class="px-4 py-2 border">Ім'я</th>
                    <th class="px-4 py-2 border">Email</th>
                    <th class="px-4 py-2 w-48 border">Дії</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td class="border px-4 py-2">{{ $user->id }}</td>
                    <td class="border px-4 py-2">{{ $user->name }}</td>
                    <td class="border px-4 py-2">{{ $user->email }}</td>
                    <td class="border px-4 py-2">
                        <button wire:click="edit({{ $user->id }})" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded">Edit</button>
                        <button wire:click="delete({{ $user->id }})" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded">Del</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
