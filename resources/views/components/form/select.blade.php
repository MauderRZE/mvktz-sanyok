@props(['label', 'model', 'live' => false])

<div>
    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">{{ $label }}</label>
    <select wire:model{{ $live ? '.live' : '' }}="{{ $model }}" class="w-full h-[42px] px-4 bg-surface-900 border border-white/10 rounded-xl text-white text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
        {{ $slot }}
    </select>
    @error($model) <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span> @enderror
</div>
