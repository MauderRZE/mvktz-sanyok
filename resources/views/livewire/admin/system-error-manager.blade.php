<div>
<x-ui.page-wrapper>
    <x-ui.flash />
    <x-ui.toolbar :count="count($errorsList)" label="Всього помилок" />

    @if($isOpen)
    <x-ui.modal title="{{ $errorId ? 'Редагувати' : 'Додати' }} помилку сайту" maxWidth="lg">
        <div class="space-y-4">
            <div>
                <x-form.select label="Сторінка / Місце" model="page_type">
                    <option value="">Оберіть сторінку...</option>
                    <option value="Авторизація (Login)">Авторизація (Login)</option>
                    <option value="Обладнання (Equipment)">Обладнання (Equipment)</option>
                    <option value="Комплектуючі (Components)">Комплектуючі (Components)</option>
                    <option value="Ліцензії ПЗ (Software)">Ліцензії ПЗ (Software)</option>
                    <option value="Малоцінка (Low Value)">Малоцінка (Low Value)</option>
                    <option value="Співробітники (Employees)">Співробітники (Employees)</option>
                    <option value="Журнал переміщень (Movements)">Журнал переміщень (Movements)</option>
                    <option value="Журнал ТО (Maintenance)">Журнал ТО (Maintenance)</option>
                    <option value="Договори (Contracts)">Договори (Contracts)</option>
                    <option value="Постачальники (Suppliers)">Постачальники (Suppliers)</option>
                    <option value="Адміністратори (Users)">Адміністратори (Users)</option>
                    <option value="помилку сайту (System Errors)">помилку сайту (System Errors)</option>
                    <option value="Довідники (Dictionaries)">Довідники (Dictionaries)</option>
                    <option value="Інше (Other)">Інше (Other)</option>
                </x-form.select>
            </div>
            <div>
                <x-form.select label="Тип помилки" model="error_type">
                    <option value="">Оберіть тип помилки...</option>
                    <option value="400 Bad Request">400 Bad Request</option>
                    <option value="401 Unauthorized">401 Unauthorized (Неавторизовано)</option>
                    <option value="403 Forbidden">403 Forbidden (Заборонено)</option>
                    <option value="404 Not Found">404 Not Found (Не знайдено)</option>
                    <option value="405 Method Not Allowed">405 Method Not Allowed (Метод не дозволено)</option>
                    <option value="419 Page Expired">419 Page Expired (CSRF Token)</option>
                    <option value="422 Unprocessable Entity">422 Unprocessable Entity (Помилка валідації)</option>
                    <option value="429 Too Many Requests">429 Too Many Requests (Забагато запитів)</option>
                    <option value="500 Internal Server Error">500 Internal Server Error (Внутрішня помилка)</option>
                    <option value="503 Service Unavailable">503 Service Unavailable (Сервіс недоступний)</option>
                    <option value="Виключення (Exception)">Виключення (Exception)</option>
                    <option value="Помилка БД (Database Error)">Помилка БД (Database Error)</option>
                    <option value="Інше (Other)">Інше (Other)</option>
                </x-form.select>
            </div>
            <div>
                <x-form.textarea label="Текст помилки" model="error_text" rows="4" />
            </div>
            
            @if($errorId)
            <div class="flex items-center">
                <input type="checkbox" id="is_resolved" wire:model="is_resolved" class="w-4 h-4 text-brand-600 bg-surface-800 border-white/10 rounded focus:ring-brand-500">
                <label for="is_resolved" class="ml-2 text-sm text-gray-300">Помилка вирішена</label>
            </div>
            @endif
        </div>
    </x-ui.modal>
    @endif

    {{-- Desktop Table --}}
    <x-table.wrapper>
        <x-slot name="headers">
            <x-table.th align="left">ID / Час</x-table.th>
            <x-table.th align="left">Сторінка / Тип</x-table.th>
            <x-table.th align="left">Текст помилки</x-table.th>
            <x-table.th align="left">Статус</x-table.th>
            <x-table.th align="right">Дії</x-table.th>
        </x-slot>
    
        @forelse($errorsList as $error)
        <x-table.tr>
            <x-table.td align="left" class="text-xs text-gray-400">
                #{{ $error->id }}<br>
                {{ $error->created_at }}
            </x-table.td>
            <x-table.td align="left">
                <span class="text-white">{{ $error->page_type }}</span><br>
                <span class="text-red-400 text-xs">{{ $error->error_type }}</span>
            </x-table.td>
            <x-table.td align="left" class="max-w-md truncate whitespace-pre-wrap text-sm text-gray-300">
                {{ $error->error_text }}
            </x-table.td>
            <x-table.td align="left">
                <button wire:click="toggleResolved({{ $error->id }})" class="px-2.5 py-1 rounded-lg text-xs font-medium {{ $error->is_resolved ? 'bg-emerald-500/10 text-emerald-400' : 'bg-red-500/10 text-red-400' }}">
                    {{ $error->is_resolved ? 'Вирішено' : 'Активно' }}
                </button>
            </x-table.td>
            <x-table.td align="right" class="flex items-center justify-end">
                <button wire:click="edit({{ $error->id }})" class="p-2 text-gray-500 hover:text-brand-400 transition-colors" title="Редагувати">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </button>
                <button wire:confirm="Ви впевнені, що хочете видалити цей запис?" wire:click="delete({{ $error->id }})" class="p-2 text-gray-500 hover:text-red-400 transition-colors" title="Видалити">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </x-table.td>
        </x-table.tr>
        @empty
        <x-table.empty colspan="5" />
        @endforelse
    </x-table.wrapper>

    {{-- Mobile Cards --}}
    <x-table.mobile-list>
        @forelse($errorsList as $error)
        <x-table.mobile-card layout="gap-3">
            <div>
                <div class="flex items-center justify-between mb-1">
                    <span class="text-xs text-gray-500">{{ $error->created_at }}</span>
                    <button wire:click="toggleResolved({{ $error->id }})" class="text-[10px] uppercase tracking-wider font-semibold {{ $error->is_resolved ? 'text-emerald-400' : 'text-red-400' }}">
                        {{ $error->is_resolved ? 'Вирішено' : 'Активно' }}
                    </button>
                </div>
                <h4 class="text-sm font-medium text-white mb-1">{{ $error->page_type }} - <span class="text-red-400">{{ $error->error_type }}</span></h4>
                <p class="text-xs text-gray-400 whitespace-pre-wrap">{{ $error->error_text }}</p>
            </div>
            <div class="flex justify-end mt-2 pt-2 border-t border-white/5">
                <button wire:click="edit({{ $error->id }})" class="p-2 text-gray-500 hover:text-brand-400 transition-colors" title="Редагувати">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </button>
                <button wire:confirm="Ви впевнені, що хочете видалити цей запис?" wire:click="delete({{ $error->id }})" class="p-2 text-gray-500 hover:text-red-400 transition-colors" title="Видалити">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </div>
        </x-table.mobile-card>
        @empty
        <x-table.mobile-empty />
        @endforelse
    </x-table.mobile-list>

</x-ui.page-wrapper>
</div>
