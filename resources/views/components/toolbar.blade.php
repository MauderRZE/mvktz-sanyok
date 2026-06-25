@props(['count', 'label' => 'Всього', 'buttonLabel' => 'Додати'])

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <p class="text-sm text-gray-500">{{ $label }}: <span class="text-gray-300 font-medium">{{ $count }}</span></p>
    <button wire:click="create()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-brand-600 to-brand-500 hover:from-brand-500 hover:to-brand-400 text-white text-sm font-semibold rounded-xl shadow-lg shadow-brand-500/20 transition-all">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        {{ $buttonLabel }}
    </button>
</div>
