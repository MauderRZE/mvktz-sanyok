@props(['title', 'maxWidth' => 'md'])

@php
$maxWidthClass = [
    'sm' => 'max-w-sm',
    'md' => 'max-w-md',
    'lg' => 'max-w-lg',
    'xl' => 'max-w-xl',
    '2xl' => 'max-w-2xl',
    '3xl' => 'max-w-3xl',
    '4xl' => 'max-w-4xl',
    'full' => 'max-w-full',
][$maxWidth] ?? 'max-w-md';
@endphp

<div class="fixed inset-0 z-50 overflow-hidden" aria-labelledby="slide-over-title" role="dialog" aria-modal="true" x-data x-init="document.body.style.overflow='hidden'; $cleanup(() => document.body.style.overflow='')">
    <div class="absolute inset-0 overflow-hidden">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" wire:click="close()" x-on:click="document.body.style.overflow=''"></div>

        <div class="fixed inset-y-0 right-0 max-w-full flex">
            <!-- Slide-over panel -->
            <div class="w-screen {{ $maxWidthClass }} fade-in-right transform transition ease-in-out duration-500 sm:duration-700">
                <div class="h-full flex flex-col bg-surface-900 border-l border-white/5 shadow-2xl">
                    <div class="px-6 py-4 border-b border-white/5 flex items-center justify-between bg-surface-800">
                        <h2 class="text-xl font-semibold text-white" id="slide-over-title">{{ $title }}</h2>
                        <button wire:click="close()" x-on:click="document.body.style.overflow=''" class="text-gray-500 hover:text-white transition-colors">
                            <span class="sr-only">Закрити панель</span>
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="relative flex-1 px-4 sm:px-6 py-6 overflow-y-auto">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .fade-in-right {
        animation: fadeInRight 0.3s ease-out forwards;
    }
    @keyframes fadeInRight {
        from {
            opacity: 0;
            transform: translateX(100%);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
</style>
