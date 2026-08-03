const fs = require('fs');
const file = '/var/www/laravel/inventar/resources/views/livewire/admin/dashboard-manager.blade.php';
let content = fs.readFileSync(file, 'utf8');

// 1. Remove all dashboard elements after the script
content = content.replace(/<\/script>[\s\S]*$/, '</script>\n</x-ui.page-wrapper>\n</div>\n');

// 2. Make the tank game container fullscreen
content = content.replace(
    /style="height: 140px; max-width: 480px;"\n\s*class="hover:border-emerald-400\/60 transition-colors relative bg-surface-900 border border-emerald-500\/30 rounded-xl p-3 overflow-hidden shadow-lg select-none w-full flex flex-col justify-between"/g,
    `style="height: calc(100vh - 150px); width: 100%;"
             class="hover:border-emerald-400/60 transition-colors relative bg-surface-900 border border-emerald-500/30 rounded-xl p-3 overflow-hidden shadow-lg select-none w-full flex flex-col"`
);

// 3. Update the header container to fill available height properly
content = content.replace(
    /class="mb-6 flex flex-col lg:flex-row lg:items-center justify-between gap-4"/g,
    `class="mb-6 flex flex-col gap-4 h-full"`
);

content = content.replace(
    /<h1 class="text-2xl font-bold text-white tracking-tight flex items-center gap-2">\n\s*Дашборд/g,
    `<h1 class="text-2xl font-bold text-white tracking-tight flex items-center gap-2">\n                Танковий полігон`
);

content = content.replace(
    /Огляд стану системи інвентаризації/g,
    `Знищуй цілі та заробляй бали!`
);

// 4. Update JavaScript to spawn more targets, maybe faster tank
content = content.replace(/tank: \{ x: 40, y: 40, angle: 0, speed: 2\.5 \}/g, 'tank: { x: 40, y: 40, angle: 0, speed: 3.5 }');
content = content.replace(/this\.spawnTargets\(4\)/g, 'this.spawnTargets(15)');
content = content.replace(/this\.spawnTargets\(1\)/g, 'this.spawnTargets(1)');

// 5. Allow focus by clicking anywhere, and start focused if we want
// Let's remove the "isFocused" check for pausing completely or make it always true.
// Actually, it's better to just keep hover.

fs.writeFileSync(file, content);
