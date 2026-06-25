<!DOCTYPE html>
<html lang="uk">
<x-layout.head />
<body class="bg-surface-900 font-sans text-gray-200 antialiased">

<div class="flex min-h-screen" x-data="{ sidebarOpen: false }">

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

<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@livewireScripts
</body>
</html>
