@props(['label', 'model', 'live' => false])

<div class="flex flex-col">
    <label class="flex items-center gap-2 text-sm text-gray-300 cursor-pointer">
        <input type="checkbox" wire:model{{ $live ? '.live' : '' }}="{{ $model }}" class="rounded bg-surface-900 border-white/10 text-brand-600 focus:ring-brand-500">
        {{ $label }}
    </label>
    @error($model) <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span> @enderror
</div>
