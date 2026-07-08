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
    <div class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-5 gap-4 mb-6">
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

        <!-- Employees -->
        <div class="bg-surface-800 rounded-2xl border border-white/5 p-4 flex flex-col aspect-square">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-purple-500/10 text-purple-400 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-widest font-semibold">Співробітники</p>
                    <h3 class="text-2xl font-bold text-white leading-none mt-1">{{ $stats['totalEmployees'] }}</h3>
                </div>
            </div>
        </div>

        <!-- Contracts -->
        <div class="bg-surface-800 rounded-2xl border border-white/5 p-4 flex flex-col aspect-square">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-widest font-semibold">Договори</p>
                    <h3 class="text-2xl font-bold text-white leading-none mt-1">{{ $stats['totalContracts'] }}</h3>
                </div>
            </div>
        </div>

        <!-- Licenses -->
        <div class="bg-surface-800 rounded-2xl border border-white/5 p-4 flex flex-col aspect-square">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-400 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-widest font-semibold">Ліцензії ПЗ</p>
                    <h3 class="text-2xl font-bold text-white leading-none mt-1">{{ $stats['totalLicenses'] }}</h3>
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
            <div class="mt-auto space-y-2 pt-3 border-t border-white/5 text-center">
                <a href="{{ route('admin.movements') }}" class="text-xs text-cyan-400 hover:text-cyan-300 transition-colors">Переглянути всі</a>
            </div>
        </div>
    </div>

    <!-- Lists Grid -->
    <div class="grid grid-cols-1 gap-6">

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
</div>
