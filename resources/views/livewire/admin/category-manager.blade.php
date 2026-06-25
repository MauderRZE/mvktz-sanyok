<div class="w-full">
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
        @if (session()->has('message'))
            <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md my-3">
                <p class="text-sm">{{ session('message') }}</p>
            </div>
        @endif

        <button wire:click="create()" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded my-3">
            Додати Категорію
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
                          <label for="category_name" class="block text-gray-700 text-sm font-bold mb-2">Назва Категорії:</label>
                          <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="category_name" wire:model="category_name">
                          @error('category_name') <span class="text-red-500">{{ $message }}</span>@enderror
                      </div>
                  </div>
                  <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click.prevent="store()" type="button" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-green-600 text-base leading-6 font-bold text-white shadow-sm hover:bg-green-500 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
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
                    <th class="px-4 py-2 border">Назва</th>
                    <th class="px-4 py-2 w-48 border">Дії</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                <tr>
                    <td class="border px-4 py-2">{{ $category->id }}</td>
                    <td class="border px-4 py-2">{{ $category->category_name }}</td>
                    <td class="border px-4 py-2">
                        <button wire:click="edit({{ $category->id }})" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded">Edit</button>
                        <button wire:click="delete({{ $category->id }})" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded">Del</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
