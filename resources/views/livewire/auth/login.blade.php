<div class="relative z-10 w-full max-w-md mx-auto">
    <div class="bg-surface-800/80 backdrop-blur-xl border border-white/5 rounded-2xl shadow-2xl glow p-8 sm:p-10">
        <!-- Logo -->
        <div class="flex flex-col items-center mb-8">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-brand-500 to-brand-700 flex items-center justify-center shadow-lg shadow-brand-500/30 mb-4">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
            <h1 class="text-2xl font-bold text-white">Інвентар МВКТЗ</h1>
            <p class="text-sm text-gray-500 mt-1">Увійдіть у систему обліку</p>
        </div>

        <form wire:submit.prevent="login" class="space-y-5">
            <!-- Email -->
            <div>
                <label for="email-address" class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Email</label>
                <input wire:model="email" id="email-address" type="email" required autocomplete="email"
                       class="w-full px-4 py-3 bg-surface-900/60 border border-white/10 rounded-xl text-white text-sm placeholder-gray-600 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors"
                       placeholder="your@email.com">
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Пароль</label>
                <input wire:model="password" id="password" type="password" required autocomplete="current-password"
                       class="w-full px-4 py-3 bg-surface-900/60 border border-white/10 rounded-xl text-white text-sm placeholder-gray-600 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors"
                       placeholder="••••••••">
            </div>

            <!-- Error -->
            @error('email')
                <div class="flex items-center gap-2 px-4 py-3 rounded-xl bg-red-500/10 border border-red-500/20">
                    <svg class="w-4 h-4 text-red-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                    <span class="text-sm text-red-300">{{ $message }}</span>
                </div>
            @enderror

            <!-- Remember & Submit -->
            <div class="flex items-center">
                <input wire:model="remember" id="remember_me" type="checkbox"
                       class="w-4 h-4 rounded bg-surface-900 border-white/20 text-brand-500 focus:ring-brand-500 focus:ring-offset-0">
                <label for="remember_me" class="ml-2 text-sm text-gray-400">Запам'ятати мене</label>
            </div>

            <button type="submit" class="w-full py-3 px-4 bg-gradient-to-r from-brand-600 to-brand-500 hover:from-brand-500 hover:to-brand-400 text-white font-semibold rounded-xl shadow-lg shadow-brand-500/25 hover:shadow-brand-500/40 transition-all duration-200 text-sm">
                <span wire:loading.remove wire:target="login">Увійти</span>
                <span wire:loading wire:target="login" class="flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                    Вхід...
                </span>
            </button>
        </form>
    </div>
</div>
