<!DOCTYPE html>
<html lang="uk">
<x-layout.head />
<body class="bg-surface-900 font-sans text-gray-200 antialiased">

<div class="flex min-h-screen" x-data="{ sidebarOpen: false }">

    <!-- Global Loading Spinner -->
    <div id="global-spinner" style="display: none;" class="fixed inset-0 z-[9999] items-center justify-center bg-black/50 backdrop-blur-sm">
        <div class="bg-surface-800 p-6 rounded-2xl shadow-2xl flex flex-col items-center gap-4 border border-white/10">
            <svg class="w-10 h-10 text-brand-500 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-base font-medium text-white tracking-wide">Завантаження...</span>
        </div>
    </div>

    <!-- Mobile Overlay -->
    <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-out duration-300"
         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-200"
         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false"
         class="sidebar-overlay md:hidden" x-cloak></div>

    <x-layout.sidebar />

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-w-0">

        <x-layout.header />

        <!-- Page Content -->
        <main class="flex-1 p-4 sm:p-6 fade-in">
            {{ $slot }}
        </main>
    </div>
</div>

@livewireScripts
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const checkModals = () => {
            if (document.querySelector('[aria-modal="true"]')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        };
        
        // Перевіряємо при завантаженні
        checkModals();
        
        // Слідкуємо за змінами в DOM (Livewire оновлення)
        const observer = new MutationObserver(checkModals);
        observer.observe(document.body, { childList: true, subtree: true });
    });

    document.addEventListener('livewire:init', () => {
        let requestCount = 0;
        let spinnerTimeout;
        const spinner = document.getElementById('global-spinner');

        if (spinner) {
            Livewire.hook('commit', ({ succeed, fail }) => {
                requestCount++;
                if (requestCount === 1) {
                    spinnerTimeout = setTimeout(() => {
                        spinner.style.display = 'flex';
                    }, 200); // 200ms delay to prevent flashing on fast requests
                }
                
                const done = () => {
                    requestCount--;
                    if(requestCount <= 0) {
                        requestCount = 0;
                        clearTimeout(spinnerTimeout);
                        spinner.style.display = 'none';
                    }
                };

                succeed(done);
                fail(done);
            });
        }
    });
</script>
</body>
</html>
