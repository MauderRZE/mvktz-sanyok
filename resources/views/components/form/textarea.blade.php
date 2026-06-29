@props(['label', 'model', 'rows' => '3', 'placeholder' => ''])

<div>
    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">{{ $label }}</label>
    <textarea wire:model="{{ $model }}" rows="{{ $rows }}" placeholder="{{ $placeholder }}" {{ $attributes->merge(['class' => 'w-full px-4 py-2.5 bg-surface-900/60 border border-white/10 rounded-xl text-white text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors']) }}></textarea>
    @error($model) <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span> @enderror
</div>
