<div>
    <x-ui.page-wrapper>
        <!-- Tabs -->
        <div class="flex gap-2 bg-surface-900 p-1 rounded-xl mb-4 w-fit">
            <button wire:click="setTab('auth')" class="px-4 py-2 text-sm rounded-lg transition-colors {{ $tab === 'auth' ? 'bg-surface-700 text-white font-medium' : 'text-gray-400 hover:text-white' }}">
                Авторизації
            </button>
            <button wire:click="setTab('audit')" class="px-4 py-2 text-sm rounded-lg transition-colors {{ $tab === 'audit' ? 'bg-surface-700 text-white font-medium' : 'text-gray-400 hover:text-white' }}">
                Аудит даних
            </button>
            <button wire:click="setTab('access')" class="px-4 py-2 text-sm rounded-lg transition-colors {{ $tab === 'access' ? 'bg-surface-700 text-white font-medium' : 'text-gray-400 hover:text-white' }}">
                Доступ (Логи)
            </button>
        </div>

    <div class="space-y-4">
        <!-- Search & Filters -->
        <x-ui.card class="p-4 space-y-4">
            <x-form.search wire:model.live.debounce.300ms="search" placeholder="Глобальний пошук..." />
            
            @if($tab === 'access')
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
            @endif
        </x-ui.card>

        <!-- Table -->
        <x-table.wrapper>
            <x-slot name="headers">
                @if($tab === 'auth')
                    <x-table.th>Користувач</x-table.th>
                    <x-table.th>Подія</x-table.th>
                    <x-table.th>IP / Браузер</x-table.th>
                    <x-table.th>Дата</x-table.th>
                @elseif($tab === 'audit')
                    <x-table.th>Користувач</x-table.th>
                    <x-table.th>Дія</x-table.th>
                    <x-table.th>Об'єкт</x-table.th>
                    <x-table.th>Зміни</x-table.th>
                    <x-table.th>Дата</x-table.th>
                @elseif($tab === 'access')
                    <x-table.th>Користувач</x-table.th>
                    <x-table.th>Метод / Статус</x-table.th>
                    <x-table.th>URL</x-table.th>
                    <x-table.th>IP / Браузер</x-table.th>
                    <x-table.th>Дата</x-table.th>
                @endif
            </x-slot>

            @forelse($logs as $log)
                <x-table.tr>
                    <x-table.td primary>
                        {{ $log->user->name ?? 'Невідомий / Гість' }}
                        <x-table.cell-subtext>{{ $log->user->login ?? '' }}</x-table.cell-subtext>
                    </x-table.td>
                    
                    @if($tab === 'auth')
                        <x-table.td>
                            <span class="px-2 py-1 text-xs rounded-lg {{ $log->event === 'login' ? 'bg-green-500/10 text-green-400' : ($log->event === 'logout' ? 'bg-gray-500/10 text-gray-400' : 'bg-red-500/10 text-red-400') }}">
                                {{ strtoupper($log->event) }}
                            </span>
                        </x-table.td>
                        <x-table.td>
                            {{ $log->ip_address }}
                            <x-table.cell-subtext class="truncate max-w-xs" title="{{ $log->user_agent }}">{{ $log->user_agent }}</x-table.cell-subtext>
                        </x-table.td>

                    @elseif($tab === 'audit')
                        <x-table.td>
                            <span class="px-2 py-1 text-xs rounded-lg {{ $log->event === 'created' ? 'bg-green-500/10 text-green-400' : ($log->event === 'updated' ? 'bg-blue-500/10 text-blue-400' : 'bg-red-500/10 text-red-400') }}">
                                {{ strtoupper($log->event) }}
                            </span>
                        </x-table.td>
                        <x-table.td>
                            {{ class_basename($log->auditable_type) }} #{{ $log->auditable_id }}
                        </x-table.td>
                        <x-table.td>
                            @if($log->event === 'updated')
                                <div class="text-xs text-gray-400 max-w-xs truncate" title="{{ json_encode($log->new_values, JSON_UNESCAPED_UNICODE) }}">
                                    Оновлено полів: {{ count((array)$log->new_values) }}
                                </div>
                            @endif
                        </x-table.td>

                    @elseif($tab === 'access')
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
                        </x-table.td>
                        <x-table.td>
                            {{ $log->ip_address }}
                        </x-table.td>
                    @endif

                    <x-table.td class="text-sm text-gray-400 whitespace-nowrap">
                        {{ $log->created_at->format('d.m.Y H:i:s') }}
                    </x-table.td>
                </x-table.tr>
            @empty
                <x-table.tr>
                    <x-table.td colspan="5" class="text-center text-gray-500 py-8">Дані відсутні</x-table.td>
                </x-table.tr>
            @endforelse
        </x-table.wrapper>

        <div class="mt-4">
            {{ $logs->links() }}
        </div>
    </div>
    </x-ui.page-wrapper>
</div>
