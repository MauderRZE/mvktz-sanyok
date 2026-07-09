<div>
<x-ui.page-wrapper>
    <!-- Header -->
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Дашборд</h1>
            <p class="text-sm text-gray-400 mt-1">Огляд стану системи інвентаризації</p>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 mb-6">
        <!-- Total Equipment -->
        <div class="bg-surface-800 rounded-2xl border border-white/5 p-4 flex flex-col aspect-square">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-blue-500/10 text-blue-400 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-widest font-semibold">Всього обладнання</p>
                    <h3 class="text-2xl font-bold text-white leading-none mt-1">{{ $stats['totalEquipment'] }}</h3>
                </div>
            </div>
            
            <div class="mt-auto space-y-2 pt-3 border-t border-white/5">
                <div class="flex justify-between items-center text-xs">
                    <span class="text-gray-400">В експлуатації</span>
                    <span class="text-emerald-400 font-medium">{{ $stats['equipmentInUse'] }}</span>
                </div>
                <div class="flex justify-between items-center text-xs">
                    <span class="text-gray-400">В аренді</span>
                    <span class="text-amber-400 font-medium">{{ $stats['equipmentStored'] }}</span>
                </div>
                <div class="flex justify-between items-center text-xs">
                    <span class="text-gray-400">Списано</span>
                    <span class="text-rose-400 font-medium">{{ $stats['equipmentWrittenOff'] }}</span>
                </div>
            </div>
        </div>

        <!-- Assets -->
        <div class="bg-surface-800 rounded-2xl border border-white/5 p-4 flex flex-col aspect-square">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-fuchsia-500/10 text-fuchsia-400 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-widest font-semibold">Активи</p>
                    <h3 class="text-2xl font-bold text-white leading-none mt-1">{{ $stats['totalAssets'] }}</h3>
                </div>
            </div>
            
            <div class="mt-auto space-y-2 pt-3 border-t border-white/5">
                <div class="flex justify-between items-center text-xs">
                    <span class="text-gray-400">Із серійником</span>
                    <span class="text-fuchsia-400 font-medium">{{ $stats['assetsWithSerial'] }}</span>
                </div>
                <div class="flex justify-between items-center text-xs">
                    <span class="text-gray-400">Списано</span>
                    <span class="text-rose-400 font-medium">{{ $stats['assetsWrittenOff'] }}</span>
                </div>
            </div>
        </div>

        <!-- Low Value Materials -->
        <div class="bg-surface-800 rounded-2xl border border-white/5 p-4 flex flex-col aspect-square">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-lime-500/10 text-lime-400 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-widest font-semibold">МШП</p>
                    <h3 class="text-2xl font-bold text-white leading-none mt-1">{{ $stats['totalLVM'] }}</h3>
                </div>
            </div>
            
            <div class="mt-auto space-y-2 pt-3 border-t border-white/5">
                <div class="flex justify-between items-center text-xs">
                    <span class="text-gray-400">Загалом одиниць</span>
                    <span class="text-lime-400 font-medium">{{ $stats['lvmCountSum'] }}</span>
                </div>
                <div class="flex justify-between items-center text-xs">
                    <span class="text-gray-400">Видів матеріалів</span>
                    <span class="text-white font-medium">{{ $stats['totalLVM'] }}</span>
                </div>
            </div>
        </div>

        <div class="bg-surface-800 rounded-2xl border border-white/5 p-4 flex flex-col aspect-square">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-purple-500/10 text-purple-400 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-widest font-semibold">Співробітники</p>
                    <h3 class="text-2xl font-bold text-white leading-none mt-1">{{ $stats['totalEmployees'] }}</h3>
                </div>
            </div>
            <div class="mt-auto space-y-2 pt-3 border-t border-white/5">
                <div class="flex justify-between items-center text-xs">
                    <span class="text-gray-400">Працюють</span>
                    <span class="text-emerald-400 font-medium">{{ $stats['employeesWorking'] }}</span>
                </div>
                <div class="flex justify-between items-center text-xs">
                    <span class="text-gray-400">З контактами</span>
                    <span class="text-purple-400 font-medium">{{ $stats['employeesWithPhones'] }}</span>
                </div>
            </div>
        </div>

        <!-- Contracts -->
        <div class="bg-surface-800 rounded-2xl border border-white/5 p-4 flex flex-col aspect-square">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-widest font-semibold">Договори</p>
                    <h3 class="text-2xl font-bold text-white leading-none mt-1">{{ $stats['totalContracts'] }}</h3>
                </div>
            </div>
            <div class="mt-auto space-y-2 pt-3 border-t border-white/5">
                <div class="flex justify-between items-center text-xs">
                    <span class="text-gray-400">Зі сканами</span>
                    <span class="text-emerald-400 font-medium">{{ $stats['contractsWithLink'] }}</span>
                </div>
                <div class="flex justify-between items-center text-xs">
                    <span class="text-gray-400">Без копії</span>
                    <span class="text-rose-400 font-medium">{{ $stats['totalContracts'] - $stats['contractsWithLink'] }}</span>
                </div>
            </div>
        </div>

        <!-- Licenses -->
        <div class="bg-surface-800 rounded-2xl border border-white/5 p-4 flex flex-col aspect-square">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-400 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-widest font-semibold">Ліцензії ПЗ</p>
                    <h3 class="text-2xl font-bold text-white leading-none mt-1">{{ $stats['totalLicenses'] }}</h3>
                </div>
            </div>
            <div class="mt-auto space-y-2 pt-3 border-t border-white/5">
                <div class="flex justify-between items-center text-xs">
                    <span class="text-gray-400">Постачальник вказаний</span>
                    <span class="text-amber-400 font-medium">{{ $stats['licensesWithVendor'] }}</span>
                </div>
                <div class="flex justify-between items-center text-xs">
                    <span class="text-gray-400">Без постачальника</span>
                    <span class="text-gray-500 font-medium">{{ $stats['totalLicenses'] - $stats['licensesWithVendor'] }}</span>
                </div>
            </div>
        </div>
        
        <!-- Movements -->
        <div class="bg-surface-800 rounded-2xl border border-white/5 p-4 flex flex-col aspect-square">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-cyan-500/10 text-cyan-400 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-widest font-semibold">Переміщення</p>
                    <h3 class="text-2xl font-bold text-white leading-none mt-1">{{ $stats['totalMovements'] }}</h3>
                </div>
            </div>
            <div class="mt-auto space-y-2 pt-3 border-t border-white/5">
                <div class="flex justify-between items-center text-xs mb-2">
                    <span class="text-gray-400">Цього місяця</span>
                    <span class="text-cyan-400 font-medium">{{ $stats['movementsThisMonth'] }}</span>
                </div>
                <a href="{{ route('admin.movements') }}" class="text-xs text-cyan-400 hover:text-cyan-300 transition-colors flex items-center justify-center w-full bg-cyan-500/10 py-1.5 rounded-lg">Переглянути всі</a>
            </div>
        </div>

        <!-- Locations -->
        <div class="bg-surface-800 rounded-2xl border border-white/5 p-4 flex flex-col aspect-square">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-teal-500/10 text-teal-400 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.242-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-widest font-semibold">Локації</p>
                    <h3 class="text-2xl font-bold text-white leading-none mt-1">{{ $stats['totalLocations'] }}</h3>
                </div>
            </div>
            <div class="mt-auto space-y-2 pt-3 border-t border-white/5">
                <div class="flex justify-between items-center text-xs">
                    <span class="text-gray-400">Задіяні</span>
                    <span class="text-teal-400 font-medium">{{ $stats['locationsInUse'] }}</span>
                </div>
                <div class="flex justify-between items-center text-xs">
                    <span class="text-gray-400">Вільні</span>
                    <span class="text-gray-500 font-medium">{{ $stats['totalLocations'] - $stats['locationsInUse'] }}</span>
                </div>
            </div>
        </div>

        <!-- Departments -->
        <div class="bg-surface-800 rounded-2xl border border-white/5 p-4 flex flex-col aspect-square">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-500/10 text-indigo-400 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-widest font-semibold">Відділи</p>
                    <h3 class="text-2xl font-bold text-white leading-none mt-1">{{ $stats['totalDepartments'] }}</h3>
                </div>
            </div>
            <div class="mt-auto space-y-2 pt-3 border-t border-white/5">
                <div class="flex justify-between items-center text-xs">
                    <span class="text-gray-400">Зі співробітниками</span>
                    <span class="text-indigo-400 font-medium">{{ $stats['departmentsWithEmployees'] }}</span>
                </div>
                <div class="flex justify-between items-center text-xs">
                    <span class="text-gray-400">Порожні</span>
                    <span class="text-gray-500 font-medium">{{ $stats['totalDepartments'] - $stats['departmentsWithEmployees'] }}</span>
                </div>
            </div>
        </div>

        <!-- Suppliers -->
        <div class="bg-surface-800 rounded-2xl border border-white/5 p-4 flex flex-col aspect-square">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-sky-500/10 text-sky-400 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-widest font-semibold">Постачальники</p>
                    <h3 class="text-2xl font-bold text-white leading-none mt-1">{{ $stats['totalSuppliers'] }}</h3>
                </div>
            </div>
            <div class="mt-auto space-y-2 pt-3 border-t border-white/5">
                <div class="flex justify-between items-center text-xs">
                    <span class="text-gray-400">ТОВ</span>
                    <span class="text-sky-400 font-medium">{{ $stats['tovSuppliers'] }}</span>
                </div>
                <div class="flex justify-between items-center text-xs">
                    <span class="text-gray-400">ФОП</span>
                    <span class="text-sky-400 font-medium">{{ $stats['fopSuppliers'] }}</span>
                </div>
            </div>
        </div>

        <!-- WriteOffs -->
        <div class="bg-surface-800 rounded-2xl border border-white/5 p-4 flex flex-col aspect-square">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-rose-500/10 text-rose-400 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-widest font-semibold">Списання</p>
                    <h3 class="text-2xl font-bold text-white leading-none mt-1">{{ $stats['totalWriteOffs'] }}</h3>
                </div>
            </div>
            <div class="mt-auto space-y-2 pt-3 border-t border-white/5">
                <div class="flex justify-between items-center text-xs mb-2">
                    <span class="text-gray-400">Цього року</span>
                    <span class="text-rose-400 font-medium">{{ $stats['writeOffsThisYear'] }}</span>
                </div>
                <a href="{{ route('admin.write-off-acts') }}" class="text-xs text-rose-400 hover:text-rose-300 transition-colors flex items-center justify-center w-full bg-rose-500/10 py-1.5 rounded-lg">Переглянути всі</a>
            </div>
        </div>

        <!-- Maintenance -->
        <div class="bg-surface-800 rounded-2xl border border-white/5 p-4 flex flex-col aspect-square">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-orange-500/10 text-orange-400 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-widest font-semibold">Записи ТО</p>
                    <h3 class="text-2xl font-bold text-white leading-none mt-1">{{ $stats['totalMaintenance'] }}</h3>
                </div>
            </div>
            <div class="mt-auto space-y-2 pt-3 border-t border-white/5">
                <div class="flex justify-between items-center text-xs">
                    <span class="text-gray-400">В процесі</span>
                    <span class="text-orange-400 font-medium">{{ $stats['maintenanceActive'] }}</span>
                </div>
                <div class="flex justify-between items-center text-xs">
                    <span class="text-gray-400">Завершено</span>
                    <span class="text-emerald-400 font-medium">{{ $stats['maintenanceCompleted'] }}</span>
                </div>
            </div>
        </div>
    </div>
    <!-- Lists Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Chart Assets -->
        <div class="bg-surface-800 rounded-2xl border border-white/5 flex flex-col overflow-hidden">
            <div class="px-5 py-4 border-b border-white/5 flex items-center justify-between bg-surface-800/50">
                <h3 class="text-sm font-semibold text-white">Нові активи (за останні 6 місяців)</h3>
            </div>
            <div class="p-4 flex-1">
                <canvas id="assetsLineChart"></canvas>
            </div>
        </div>

        <!-- Chart Equipment -->
        <div class="bg-surface-800 rounded-2xl border border-white/5 flex flex-col overflow-hidden">
            <div class="px-5 py-4 border-b border-white/5 flex items-center justify-between bg-surface-800/50">
                <h3 class="text-sm font-semibold text-white">Нове обладнання (за останні 6 місяців)</h3>
            </div>
            <div class="p-4 flex-1">
                <canvas id="equipmentLineChart"></canvas>
            </div>
        </div>

        <!-- Recent Maintenance -->
        <div class="bg-surface-800 rounded-2xl border border-white/5 flex flex-col overflow-hidden">
            <div class="px-5 py-4 border-b border-white/5 flex items-center justify-between bg-surface-800/50">
                <h3 class="text-sm font-semibold text-white">Журнал ТО</h3>
                <a href="{{ route('admin.maintenance-logs') }}" class="text-xs text-brand-400 hover:text-brand-300 transition-colors">Весь журнал</a>
            </div>
            <div class="divide-y divide-white/5">
                @forelse($recentMaintenance as $log)
                <div class="p-4 flex items-start gap-4 hover:bg-white/[0.02] transition-colors">
                    <div class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center shrink-0 mt-1">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-white font-medium truncate">
                            {{ $log->asset->model->model_name ?? $log->asset->equipment->account_name ?? 'Обладнання' }} 
                            <span class="text-xs text-gray-500 font-normal ml-1">({{ $log->asset->equipment->inv_number ?? '—' }})</span>
                        </p>
                        <p class="text-xs text-gray-400 mt-1 truncate">{{ $log->issue_description }}</p>
                    </div>
                    <div class="text-xs text-gray-500 whitespace-nowrap">
                        {{ $log->sent_date ? \Carbon\Carbon::parse($log->sent_date)->format('d.m.Y') : '—' }}
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-gray-500 text-sm">Немає записів ТО</div>
                @endforelse
            </div>
        </div>

    </div>
    
</x-ui.page-wrapper>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('livewire:initialized', () => {
        // assets line chart
        const ctxAssets = document.getElementById('assetsLineChart');
        if (ctxAssets) {
            new Chart(ctxAssets, {
                type: 'line',
                data: {
                    labels: @json($chartLabels),
                    datasets: [{
                        label: 'Нові активи',
                        data: @json($assetsChartData),
                        backgroundColor: 'rgba(192, 132, 252, 0.1)', // fuchsia-400 with opacity
                        borderColor: 'rgba(192, 132, 252, 1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true,
                        pointBackgroundColor: 'rgba(192, 132, 252, 1)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, ticks: { precision: 0, color: '#9ca3af' }, grid: { color: 'rgba(255, 255, 255, 0.05)' } },
                        x: { ticks: { color: '#9ca3af' }, grid: { display: false } }
                    }
                }
            });
        }

        // equipment line chart
        const ctxEquipment = document.getElementById('equipmentLineChart');
        if (ctxEquipment) {
            new Chart(ctxEquipment, {
                type: 'line',
                data: {
                    labels: @json($chartLabels),
                    datasets: [{
                        label: 'Нове обладнання',
                        data: @json($equipmentChartData),
                        backgroundColor: 'rgba(56, 189, 248, 0.1)', // sky-400 with opacity
                        borderColor: 'rgba(56, 189, 248, 1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true,
                        pointBackgroundColor: 'rgba(56, 189, 248, 1)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, ticks: { precision: 0, color: '#9ca3af' }, grid: { color: 'rgba(255, 255, 255, 0.05)' } },
                        x: { ticks: { color: '#9ca3af' }, grid: { display: false } }
                    }
                }
            });
        }
    });
</script>
</div>
