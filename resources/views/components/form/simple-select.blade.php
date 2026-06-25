<select {{ $attributes->merge(['class' => 'w-full bg-surface-900 border border-white/10 rounded-xl px-4 py-2 text-sm text-white focus:ring-2 focus:ring-brand-500/50 focus:border-brand-500 transition-colors']) }}>
    {{ $slot }}
</select>
