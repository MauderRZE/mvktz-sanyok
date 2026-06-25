<div class="hidden md:block bg-surface-800/50 border border-white/5 rounded-2xl overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="border-b border-white/5">
                {{ $headers }}
            </tr>
        </thead>
        <tbody class="divide-y divide-white/5">
            {{ $slot }}
        </tbody>
    </table>
</div>
