<div>
    <x-ui.page-wrapper>
        <x-ui.flash />
        <!-- Tabs & Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
            <div class="flex gap-2 bg-surface-900 p-1 rounded-xl w-fit">
                <button wire:click="setTab('auth')" class="px-4 py-2 text-sm rounded-lg transition-colors {{ $tab === 'auth' ? 'bg-surface-700 text-white font-medium' : 'text-gray-400 hover:text-white' }}">
                    Авторизації
                </button>
                <button wire:click="setTab('audit')" class="px-4 py-2 text-sm rounded-lg transition-colors {{ $tab === 'audit' ? 'bg-surface-700 text-white font-medium' : 'text-gray-400 hover:text-white' }}">
                    Аудит даних
                </button>
                <button wire:click="setTab('access')" class="px-4 py-2 text-sm rounded-lg transition-colors {{ $tab === 'access' ? 'bg-surface-700 text-white font-medium' : 'text-gray-400 hover:text-white' }}">
                    Доступ (Логи)
                </button>
                <button wire:click="setTab('stats')" class="px-4 py-2 text-sm rounded-lg transition-colors {{ $tab === 'stats' ? 'bg-surface-700 text-white font-medium' : 'text-gray-400 hover:text-white' }}">
                    Статистика
                </button>
            </div>
            <div>
                <x-ui.button variant="danger" wire:click="openClearModal" class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    <span>Очистити базу</span>
                </x-ui.button>
            </div>
        </div>

    <div class="space-y-4">
        <!-- Search & Filters separated per tab to prevent DOM morphing -->
        @if($tab === 'access')
            <x-ui.card wire:key="search-card-access" class="p-4 space-y-4">
                <x-form.search wire:model.live.debounce.300ms="search" placeholder="Глобальний пошук..." />
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4 pt-4 border-t border-white/5">
                    <div>
                        <x-form.select label="Користувач" model="filterUser" :live="true" placeholder="Всі користувачі">
                            <option value="">Всі користувачі</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->login }})</option>
                            @endforeach
                        </x-form.select>
                    </div>
                    <div>
                        <x-form.select label="Метод" model="filterMethod" :live="true" placeholder="Всі методи">
                            <option value="">Всі методи</option>
                            <option value="GET">GET</option>
                            <option value="POST">POST</option>
                            <option value="PUT">PUT</option>
                            <option value="DELETE">DELETE</option>
                        </x-form.select>
                    </div>
                    <div>
                        <x-form.input label="Статус" type="number" model="filterStatus" :live="true" debounce="300ms" placeholder="Напр. 200, 404" />
                    </div>
                    <div>
                        <x-form.input label="URL / Контролер" type="text" model="filterController" :live="true" debounce="300ms" placeholder="Частина URL..." />
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <x-form.input label="З дати" type="date" model="filterDateFrom" :live="true" class="[color-scheme:dark]" />
                        </div>
                        <div>
                            <x-form.input label="По дату" type="date" model="filterDateTo" :live="true" class="[color-scheme:dark]" />
                        </div>
                    </div>
                </div>
            </x-ui.card>
        @else
            <x-ui.card wire:key="search-card-other" class="p-4 space-y-4">
                <x-form.search wire:model.live.debounce.300ms="search" placeholder="Глобальний пошук..." />
            </x-ui.card>
        @endif

        @if($tab === 'stats')
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <x-ui.card class="p-4">
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Розмір бази</p>
                    <p class="text-2xl font-bold text-white">{{ $logs['db_size'] }}</p>
                </x-ui.card>
                <x-ui.card class="p-4">
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Авторизації</p>
                    <p class="text-2xl font-bold text-white">{{ $logs['total_auth'] }} <span class="text-xs font-normal text-gray-500">записів</span></p>
                </x-ui.card>
                <x-ui.card class="p-4">
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Аудит даних</p>
                    <p class="text-2xl font-bold text-white">{{ $logs['total_audit'] }} <span class="text-xs font-normal text-gray-500">записів</span></p>
                </x-ui.card>
                <x-ui.card class="p-4">
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Логи доступу</p>
                    <p class="text-2xl font-bold text-white">{{ $logs['total_access'] }} <span class="text-xs font-normal text-gray-500">записів</span></p>
                </x-ui.card>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <x-ui.card class="p-4">
                    <h3 class="text-sm font-semibold text-white mb-4">Топ-5 відвідувачів</h3>
                    <div class="space-y-3">
                        @foreach($logs['top_users_access'] as $item)
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-300">{{ $item->user->name ?? 'Невідомий' }}</span>
                                <span class="text-brand-400 font-bold">{{ $item->count }} запитів</span>
                            </div>
                        @endforeach
                    </div>
                </x-ui.card>

                <x-ui.card class="p-4">
                    <h3 class="text-sm font-semibold text-white mb-4">Топ-5 популярних сторінок</h3>
                    <div class="space-y-3">
                        @foreach($logs['top_urls'] as $item)
                            <div class="flex justify-between items-center text-sm gap-4">
                                <span class="text-gray-300 truncate">{{ $item->url }}</span>
                                <span class="text-brand-400 font-bold whitespace-nowrap">{{ $item->count }}</span>
                            </div>
                        @endforeach
                    </div>
                </x-ui.card>

                <x-ui.card class="p-4">
                    <h3 class="text-sm font-semibold text-white mb-4">Статистика статусів</h3>
                    <div class="space-y-3">
                        @foreach($logs['status_stats'] as $item)
                            <div class="flex justify-between items-center text-sm">
                                <span class="px-2 py-0.5 rounded text-xs font-bold 
                                    {{ $item->status_code >= 200 && $item->status_code < 300 ? 'bg-green-500/10 text-green-400' : 
                                       ($item->status_code >= 300 && $item->status_code < 400 ? 'bg-blue-500/10 text-blue-400' : 
                                       ($item->status_code >= 400 && $item->status_code < 500 ? 'bg-yellow-500/10 text-yellow-400' : 'bg-red-500/10 text-red-400')) }}">
                                    {{ $item->status_code }}
                                </span>
                                <span class="text-brand-400 font-bold">{{ $item->count }} запитів</span>
                            </div>
                        @endforeach
                    </div>
                </x-ui.card>

                <x-ui.card class="p-4">
                    <h3 class="text-sm font-semibold text-white mb-4">Авторизації</h3>
                    <div class="space-y-3">
                        @foreach($logs['auth_stats'] as $event => $count)
                            <div class="flex justify-between items-center text-sm">
                                <span class="px-2 py-0.5 rounded text-xs {{ $event === 'login' ? 'bg-green-500/10 text-green-400' : ($event === 'logout' ? 'bg-gray-500/10 text-gray-400' : 'bg-red-500/10 text-red-400') }}">
                                    {{ strtoupper($event) }}
                                </span>
                                <span class="text-brand-400 font-bold">{{ $count }}</span>
                            </div>
                        @endforeach
                    </div>
                </x-ui.card>
            </div>
        @else
            <!-- Tables separated to prevent Livewire slot morphing bugs -->
            @if($tab === 'auth')
                <x-table.wrapper wire:key="table-auth">
                    <x-slot name="headers">
                        <x-table.th class="w-1/4">Користувач</x-table.th>
                        <x-table.th class="w-1/6">Подія</x-table.th>
                        <x-table.th class="w-1/3">IP / Браузер</x-table.th>
                        <x-table.th class="w-1/4">Дата</x-table.th>
                    </x-slot>
                    @forelse($logs as $log)
                        <x-table.tr wire:key="auth-{{ $log->id }}">
                            <x-table.td primary>
                                {{ $log->user->name ?? 'Невідомий / Гість' }}
                                <x-table.cell-subtext>{{ $log->user->login ?? '' }}</x-table.cell-subtext>
                            </x-table.td>
                            <x-table.td>
                                <span class="px-2 py-1 text-xs rounded-lg {{ $log->event === 'login' ? 'bg-green-500/10 text-green-400' : ($log->event === 'logout' ? 'bg-gray-500/10 text-gray-400' : 'bg-red-500/10 text-red-400') }}">
                                    {{ strtoupper($log->event) }}
                                </span>
                            </x-table.td>
                            <x-table.td>
                                {{ $log->ip_address }}
                                <x-table.cell-subtext class="truncate max-w-xs" title="{{ $log->user_agent }}">{{ $log->user_agent }}</x-table.cell-subtext>
                            </x-table.td>
                            <x-table.td class="text-sm text-gray-400 whitespace-nowrap">
                                {{ $log->created_at->timezone('Europe/Kyiv')->format('d.m.Y H:i:s') }}
                            </x-table.td>
                        </x-table.tr>
                    @empty
                        <x-table.tr><x-table.td colspan="4" class="text-center text-gray-500 py-8">Дані відсутні</x-table.td></x-table.tr>
                    @endforelse
                </x-table.wrapper>
            @elseif($tab === 'audit')
                <x-table.wrapper wire:key="table-audit">
                    <x-slot name="headers">
                        <x-table.th class="w-[20%]">Користувач</x-table.th>
                        <x-table.th class="w-[15%]">Дія</x-table.th>
                        <x-table.th class="w-[20%]">Об'єкт</x-table.th>
                        <x-table.th class="w-[30%]">Зміни</x-table.th>
                        <x-table.th class="w-[15%]">Дата</x-table.th>
                    </x-slot>
                    @forelse($logs as $log)
                        <x-table.tr wire:key="audit-{{ $log->id }}">
                            <x-table.td primary>
                                {{ $log->user->name ?? 'Невідомий / Гість' }}
                                <x-table.cell-subtext>{{ $log->user->login ?? '' }}</x-table.cell-subtext>
                            </x-table.td>
                            <x-table.td>
                                <span class="px-2 py-1 text-xs rounded-lg {{ $log->event === 'created' ? 'bg-green-500/10 text-green-400' : ($log->event === 'updated' ? 'bg-blue-500/10 text-blue-400' : 'bg-red-500/10 text-red-400') }}">
                                    {{ strtoupper($log->event) }}
                                </span>
                            </x-table.td>
                            <x-table.td>
                                {{ class_basename($log->auditable_type) }} #{{ $log->auditable_id }}
                            </x-table.td>
                            <x-table.td class="w-1/3">
                                @if($log->event === 'updated')
                                    <div class="text-xs space-y-1 max-h-32 overflow-y-auto pr-2 scrollbar-thin scrollbar-thumb-surface-700 scrollbar-track-transparent">
                                        @foreach((array)$log->new_values as $key => $newValue)
                                            @php
                                                $oldValue = ((array)$log->old_values)[$key] ?? null;
                                                $displayOld = is_string($oldValue) || is_numeric($oldValue) ? $oldValue : json_encode($oldValue, JSON_UNESCAPED_UNICODE);
                                                $displayNew = is_string($newValue) || is_numeric($newValue) ? $newValue : json_encode($newValue, JSON_UNESCAPED_UNICODE);
                                            @endphp
                                            <div class="flex flex-col gap-0.5 bg-surface-900/50 p-1.5 rounded border border-white/5">
                                                <span class="text-gray-300 font-medium">{{ $key }}</span>
                                                <div class="flex items-center gap-2 text-[10px] break-all">
                                                    <span class="text-red-400 line-through opacity-75" title="Старе значення">{{ $displayOld ?: 'пусто' }}</span>
                                                    <span class="text-gray-500 shrink-0">➔</span>
                                                    <span class="text-green-400" title="Нове значення">{{ $displayNew ?: 'пусто' }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @elseif($log->event === 'created')
                                    <div class="text-xs text-gray-400 truncate max-w-xs" title="{{ json_encode($log->new_values, JSON_UNESCAPED_UNICODE) }}">
                                        Створено (полів: {{ count((array)$log->new_values) }})
                                    </div>
                                @elseif($log->event === 'deleted')
                                    <div class="text-xs text-gray-400 truncate max-w-xs" title="{{ json_encode($log->old_values, JSON_UNESCAPED_UNICODE) }}">
                                        Видалено (полів: {{ count((array)$log->old_values) }})
                                    </div>
                                @endif
                            </x-table.td>
                            <x-table.td class="text-sm text-gray-400 whitespace-nowrap">
                                {{ $log->created_at->timezone('Europe/Kyiv')->format('d.m.Y H:i:s') }}
                            </x-table.td>
                        </x-table.tr>
                    @empty
                        <x-table.tr><x-table.td colspan="5" class="text-center text-gray-500 py-8">Дані відсутні</x-table.td></x-table.tr>
                    @endforelse
                </x-table.wrapper>
            @elseif($tab === 'access')
                <x-table.wrapper wire:key="table-access">
                    <x-slot name="headers">
                        <x-table.th class="w-[20%]">Користувач</x-table.th>
                        <x-table.th class="w-[15%]">Метод / Статус</x-table.th>
                        <x-table.th class="w-[35%]">URL</x-table.th>
                        <x-table.th class="w-[15%]">IP / Браузер</x-table.th>
                        <x-table.th class="w-[15%]">Дата</x-table.th>
                    </x-slot>
                    @forelse($logs as $log)
                        <x-table.tr wire:key="access-{{ $log->id }}">
                            <x-table.td primary>
                                {{ $log->user->name ?? 'Невідомий / Гість' }}
                                <x-table.cell-subtext>{{ $log->user->login ?? '' }}</x-table.cell-subtext>
                            </x-table.td>
                            <x-table.td>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-mono {{ $log->method === 'GET' ? 'text-blue-400' : 'text-orange-400' }}">{{ $log->method }}</span>
                                    @if($log->status_code)
                                        <span class="px-1.5 py-0.5 rounded text-[10px] font-bold 
                                            {{ $log->status_code >= 200 && $log->status_code < 300 ? 'bg-green-500/10 text-green-400' : 
                                               ($log->status_code >= 300 && $log->status_code < 400 ? 'bg-blue-500/10 text-blue-400' : 
                                               ($log->status_code >= 400 && $log->status_code < 500 ? 'bg-yellow-500/10 text-yellow-400' : 'bg-red-500/10 text-red-400')) }}">
                                            {{ $log->status_code }}
                                        </span>
                                    @endif
                                </div>
                            </x-table.td>
                            <x-table.td>
                                <span class="text-xs break-all max-w-md {{ $log->status_code >= 400 ? 'text-red-400' : '' }}">{{ $log->url }}</span>
                                @if($log->error_text)
                                    <x-table.cell-subtext class="text-red-400 mt-1 line-clamp-2" title="{{ $log->error_text }}">
                                        Помилка: {{ $log->error_text }}
                                    </x-table.cell-subtext>
                                @endif
                            </x-table.td>
                            <x-table.td>
                                {{ $log->ip_address }}
                            </x-table.td>
                            <x-table.td class="text-sm text-gray-400 whitespace-nowrap">
                                {{ $log->created_at->timezone('Europe/Kyiv')->format('d.m.Y H:i:s') }}
                            </x-table.td>
                        </x-table.tr>
                    @empty
                        <x-table.tr><x-table.td colspan="5" class="text-center text-gray-500 py-8">Дані відсутні</x-table.td></x-table.tr>
                    @endforelse
                </x-table.wrapper>
            @endif

            <div class="mt-4">
                {{ $logs->links() }}
            </div>
        @endif

        @if($isClearModalOpen)
            <x-ui.modal title="Очищення бази логів/аудиту" maxWidth="md">
                <div class="space-y-4">
                    <x-form.select label="Тип логів" model="clearLogType" :live="true">
                        <option value="all">Всі логи (аудит, авторизації, доступ)</option>
                        <option value="audit">Лише аудит даних</option>
                        <option value="auth">Лише авторизації</option>
                        <option value="access">Лише логи доступу</option>
                    </x-form.select>

                    <x-form.select label="Користувач" model="clearUserId" :live="true">
                        <option value="">Всі користувачі</option>
                        <option value="guest">Невідомий користувач / Гість</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->login }})</option>
                        @endforeach
                    </x-form.select>

                    <x-form.select label="Період видалення" model="clearTimeframe" :live="true">
                        <option value="30_days">Старіше ніж 30 днів</option>
                        <option value="90_days">Старіше ніж 90 днів</option>
                        <option value="180_days">Старіше ніж 180 днів</option>
                        <option value="365_days">Старіше ніж 1 рік</option>
                        <option value="all_time">За весь час (видалити все)</option>
                        <option value="custom">Власна дата (видалити до...)</option>
                    </x-form.select>

                    @if($clearTimeframe === 'custom')
                        <x-form.input label="Видалити записи до дати" type="date" model="clearBeforeDate" class="[color-scheme:dark]" />
                    @endif
                </div>

                <x-slot name="footer">
                    <x-ui.button variant="secondary" wire:click="closeModal()">Скасувати</x-ui.button>
                    <x-ui.button variant="danger" wire:click="clearLogs()" onclick="confirm('Ви впевнені, що хочете безповоротно видалити ці логи?') || event.stopImmediatePropagation()">
                        Підтвердити очищення
                    </x-ui.button>
                </x-slot>
            </x-ui.modal>
        @endif
    </div>
    </x-ui.page-wrapper>
</div>
