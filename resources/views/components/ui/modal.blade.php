@props(['title', 'maxWidth' => 'md'])

@php
$maxWidthClass = [
    'sm' => 'max-w-sm',
    'md' => 'max-w-md',
    'lg' => 'max-w-lg',
    'xl' => 'max-w-xl',
    '2xl' => 'max-w-2xl',
][$maxWidth] ?? 'max-w-md';
@endphp

<!-- Modal Wrapper -->
<template x-teleport="body">
<div x-data x-init="document.body.style.overflow='hidden'; $cleanup(() => document.body.style.overflow='')" {{ $attributes->merge(["class" => "fixed inset-0 z-50 flex items-center justify-center p-4"]) }}>
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeModal()" x-on:click="document.body.style.overflow=''"></div>
    <div class="relative w-full {{ $maxWidthClass }} bg-surface-800 border border-white/5 rounded-2xl shadow-2xl fade-in overflow-hidden">
        <div class="px-6 py-4 border-b border-white/5 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-white">{{ $title }}</h3>
            <button wire:click="closeModal()" x-on:click="document.body.style.overflow=''" class="text-gray-500 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="p-6 space-y-4">
            {{ $slot }}
        </div>
        @if(isset($footer))
            <div class="px-6 py-4 border-t border-white/5 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                {{ $footer }}
            </div>
        @else
            <div class="px-6 py-4 border-t border-white/5 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                <x-ui.button variant="secondary" wire:click="closeModal()" x-on:click="document.body.style.overflow=''">Скасувати</x-ui.button>
                <x-ui.button wire:click.prevent="store()">Зберегти</x-ui.button>
            </div>
        @endif
    </div>
</div>
</template>
