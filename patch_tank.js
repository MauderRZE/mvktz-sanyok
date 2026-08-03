const fs = require('fs');
const file = '/var/www/laravel/inventar/resources/views/livewire/admin/dashboard-manager.blade.php';
let content = fs.readFileSync(file, 'utf8');

// 1. Add wire:ignore and hover events
content = content.replace(
    /@keyup\.window="handleKeyUp\(\$event\)"\n\s*class="/g,
    `@keyup.window="handleKeyUp($event)"\n             @mouseenter="isFocused = true"\n             @mouseleave="isFocused = false; keys = {}"\n             wire:ignore\n             class="hover:border-emerald-400/60 transition-colors `
);

// 2. Add isFocused overlay and ping logic
content = content.replace(
    /<span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"><\/span>/g,
    `<span class="w-2 h-2 rounded-full bg-emerald-400" :class="isFocused ? 'animate-ping' : 'opacity-50'"></span>`
);

content = content.replace(
    /{{-- Поле бою --}}\n\s*<div x-ref="arena" class="relative flex-1 my-1 overflow-hidden rounded bg-black\/40 border border-emerald-950\/60">/g,
    `{{-- Поле бою --}}
             <div x-ref="arena" class="relative flex-1 my-1 overflow-hidden rounded bg-black/40 border border-emerald-950/60 cursor-crosshair">
                 {{-- Підказка при неактивності --}}
                 <div x-show="!isFocused" class="absolute inset-0 z-20 flex items-center justify-center bg-black/50 backdrop-blur-sm">
                     <span class="text-emerald-400/80 font-mono text-[10px] uppercase tracking-wider bg-black/80 px-2 py-1 rounded">Наведіть курсор для гри</span>
                 </div>`
);

// 3. Remove CSS transition from the tank itself
content = content.replace(
    /<div class="absolute transition-transform duration-75 ease-linear"/g,
    `<div class="absolute"`
);

// 4. Update JavaScript logic
const newJs = `        function miniTankGame() {
            return {
                isFocused: false,
                tank: { x: 40, y: 40, angle: 0, speed: 2.5 },
                keys: {},
                bullets: [],
                targets: [],
                explosions: [],
                score: 0,
                nextBulletId: 1,
                nextTargetId: 1,
                animId: null,
                lastShootTime: 0,

                initTank() {
                    this.spawnTargets(4);
                    this.animId = requestAnimationFrame(() => this.gameLoop());
                },

                destroy() {
                    if (this.animId) cancelAnimationFrame(this.animId);
                },

                handleKeyDown(e) {
                    if (!this.isFocused) return;
                    const k = e.key.toLowerCase();
                    if (['arrowup', 'arrowdown', 'arrowleft', 'arrowright', ' '].includes(k) || ['w','a','s','d'].includes(k)) {
                        e.preventDefault();
                    }
                    this.keys[k] = true;
                    this.keys[e.key] = true;

                    if (e.key === ' ') {
                        this.shoot();
                    }
                },

                handleKeyUp(e) {
                    const k = e.key.toLowerCase();
                    this.keys[k] = false;
                    this.keys[e.key] = false;
                },

                shoot() {
                    if (!this.isFocused) return;
                    const now = Date.now();
                    if (now - this.lastShootTime < 220) return; // Кулдаун пострілу
                    this.lastShootTime = now;

                    const rad = (this.tank.angle * Math.PI) / 180;
                    // Дуло виходить із центру танка з офсетом
                    const bx = this.tank.x + Math.cos(rad) * 14;
                    const by = this.tank.y + Math.sin(rad) * 14;

                    this.bullets.push({
                        id: this.nextBulletId++,
                        x: bx,
                        y: by,
                        vx: Math.cos(rad) * 5.5,
                        vy: Math.sin(rad) * 5.5
                    });
                },

                spawnTargets(count) {
                    const arena = this.$refs.arena;
                    const w = arena ? arena.clientWidth : 440;
                    const h = arena ? arena.clientHeight : 75;

                    for (let i = 0; i < count; i++) {
                        this.targets.push({
                            id: this.nextTargetId++,
                            x: Math.random() * (w - 40) + 20,
                            y: Math.random() * (h - 30) + 15,
                            hit: false
                        });
                    }
                },

                resetGame() {
                    this.score = 0;
                    this.bullets = [];
                    this.targets = [];
                    this.explosions = [];
                    this.tank = { x: 40, y: 40, angle: 0, speed: 2.5 };
                    this.spawnTargets(4);
                },

                gameLoop() {
                    this.animId = requestAnimationFrame(() => this.gameLoop());
                    if (!this.isFocused) return;

                    const arena = this.$refs.arena;
                    const maxW = arena ? arena.clientWidth - 12 : 440;
                    const maxH = arena ? arena.clientHeight - 12 : 75;

                    // Керування танком
                    if (this.keys['arrowleft'] || this.keys['a']) {
                        this.tank.angle -= 5;
                    }
                    if (this.keys['arrowright'] || this.keys['d']) {
                        this.tank.angle += 5;
                    }

                    let moveDir = 0;
                    if (this.keys['arrowup'] || this.keys['w']) moveDir = 1;
                    if (this.keys['arrowdown'] || this.keys['s']) moveDir = -0.6;

                    if (moveDir !== 0) {
                        const rad = (this.tank.angle * Math.PI) / 180;
                        const nextX = this.tank.x + Math.cos(rad) * (this.tank.speed * moveDir);
                        const nextY = this.tank.y + Math.sin(rad) * (this.tank.speed * moveDir);

                        // Обмеження арени
                        this.tank.x = Math.max(12, Math.min(maxW, nextX));
                        this.tank.y = Math.max(12, Math.min(maxH, nextY));
                    }

                    // Рух кулей
                    for (let i = this.bullets.length - 1; i >= 0; i--) {
                        const b = this.bullets[i];
                        b.x += b.vx;
                        b.y += b.vy;

                        // Перевірка влучання в мішені
                        let hitTarget = false;
                        for (let j = 0; j < this.targets.length; j++) {
                            const t = this.targets[j];
                            if (!t.hit) {
                                const dist = Math.hypot(b.x - t.x, b.y - t.y);
                                if (dist < 14) {
                                    t.hit = true;
                                    hitTarget = true;
                                    this.score += 100;
                                    
                                    // Вибух
                                    this.explosions.push({ id: Date.now() + Math.random(), x: t.x, y: t.y });
                                    setTimeout(() => {
                                        this.targets = this.targets.filter(item => item.id !== t.id);
                                        this.spawnTargets(1);
                                    }, 200);
                                    break;
                                }
                            }
                        }

                        // Видалення снаряда за межами арени або при влучанні
                        if (hitTarget || b.x < 0 || b.x > maxW + 20 || b.y < 0 || b.y > maxH + 20) {
                            this.bullets.splice(i, 1);
                        }
                    }

                    // Очищення вибухів
                    if (this.explosions.length > 5) {
                        this.explosions.shift();
                    }
                }
            };
        }`;

content = content.replace(/function miniTankGame\(\) \{[\s\S]*?requestAnimationFrame\(\(\) => this\.gameLoop\(\)\);\n                \}\n            \};\n        \}/, newJs);

fs.writeFileSync(file, content);
