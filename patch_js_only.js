const fs = require('fs');
const file = '/var/www/laravel/inventar/resources/views/livewire/admin/dashboard-manager.blade.php';
let content = fs.readFileSync(file, 'utf8');

const newJs = `        function miniTankGame() {
            return {
                isFocused: true,
                gameOver: false,
                hp: 3,
                tank: { x: window.innerWidth / 2 || 400, y: window.innerHeight / 2 || 300, angle: 0, speed: 4 },
                keys: {},
                bullets: [],
                enemyBullets: [],
                enemyTanks: [],
                explosions: [],
                score: 0,
                nextBulletId: 1,
                animId: null,
                lastShootTime: 0,
                arenaW: 800,
                arenaH: 600,

                initTank() {
                    this.resetGame();
                    this.animId = requestAnimationFrame(() => this.gameLoop());
                    
                    // Періодично спавнимо нових ворогів, якщо їх мало
                    setInterval(() => {
                        if (!this.gameOver && this.enemyTanks.length < 5 + Math.floor(this.score / 500)) {
                            this.spawnEnemyTanks(1);
                        }
                    }, 2000);
                },

                destroy() {
                    if (this.animId) cancelAnimationFrame(this.animId);
                },

                handleKeyDown(e) {
                    if (this.gameOver) return;
                    const k = e.key.toLowerCase();
                    if (['arrowup', 'arrowdown', 'arrowleft', 'arrowright', ' '].includes(k) || ['w','a','s','d'].includes(k)) {
                        e.preventDefault();
                    }
                    this.keys[k] = true;
                    this.keys[e.key] = true;
                    if (e.code === 'Space') this.keys[' '] = true;

                    if (e.code === 'Space' || e.key === ' ') {
                        this.shoot();
                    }
                },

                handleKeyUp(e) {
                    const k = e.key.toLowerCase();
                    this.keys[k] = false;
                    this.keys[e.key] = false;
                    if (e.code === 'Space') this.keys[' '] = false;
                },

                shoot() {
                    if (this.gameOver) return;
                    const now = Date.now();
                    if (now - this.lastShootTime < 220) return; // Кулдаун пострілу
                    this.lastShootTime = now;

                    const rad = (this.tank.angle * Math.PI) / 180;
                    const bx = this.tank.x + Math.cos(rad) * 14;
                    const by = this.tank.y + Math.sin(rad) * 14;

                    this.bullets.push({
                        id: this.nextBulletId++,
                        x: bx,
                        y: by,
                        vx: Math.cos(rad) * 12,
                        vy: Math.sin(rad) * 12
                    });
                },

                spawnEnemyTanks(count) {
                    if (!this.$refs.arena) return;
                    const w = this.$refs.arena.clientWidth;
                    const h = this.$refs.arena.clientHeight;
                    this.arenaW = w;
                    this.arenaH = h;

                    for (let i = 0; i < count; i++) {
                        // Спавнимо ворогів подалі від гравця (біля країв екрану)
                        let ex, ey;
                        if (Math.random() > 0.5) {
                            ex = Math.random() > 0.5 ? 20 : w - 20;
                            ey = Math.random() * h;
                        } else {
                            ex = Math.random() * w;
                            ey = Math.random() > 0.5 ? 20 : h - 20;
                        }

                        this.enemyTanks.push({
                            id: Date.now() + Math.random(),
                            x: ex,
                            y: ey,
                            angle: 0,
                            speed: 1.5 + Math.random() * 1.5 + (this.score / 2000), // Вороги стають швидшими з часом
                            hit: false,
                            lastShoot: Date.now() + Math.random() * 2000 // Рандомізуємо перший постріл
                        });
                    }
                },

                resetGame() {
                    this.gameOver = false;
                    this.hp = 3;
                    this.score = 0;
                    this.bullets = [];
                    this.enemyBullets = [];
                    this.enemyTanks = [];
                    this.explosions = [];
                    
                    if (this.$refs.arena) {
                        this.arenaW = this.$refs.arena.clientWidth;
                        this.arenaH = this.$refs.arena.clientHeight;
                    }
                    this.tank = { x: this.arenaW / 2, y: this.arenaH / 2, angle: 0, speed: 4.5 };
                    
                    this.spawnEnemyTanks(5);
                },

                gameLoop() {
                    this.animId = requestAnimationFrame(() => this.gameLoop());
                    
                    if (this.gameOver) return;

                    const arena = this.$refs.arena;
                    if (arena) {
                        this.arenaW = arena.clientWidth;
                        this.arenaH = arena.clientHeight;
                    }
                    const maxW = this.arenaW - 12;
                    const maxH = this.arenaH - 12;

                    // === Керування танком гравця ===
                    if (this.keys['arrowleft'] || this.keys['a']) this.tank.angle -= 5;
                    if (this.keys['arrowright'] || this.keys['d']) this.tank.angle += 5;

                    let moveDir = 0;
                    if (this.keys['arrowup'] || this.keys['w']) moveDir = 1;
                    if (this.keys['arrowdown'] || this.keys['s']) moveDir = -0.6;

                    if (moveDir !== 0) {
                        const rad = (this.tank.angle * Math.PI) / 180;
                        const nextX = this.tank.x + Math.cos(rad) * (this.tank.speed * moveDir);
                        const nextY = this.tank.y + Math.sin(rad) * (this.tank.speed * moveDir);
                        this.tank.x = Math.max(12, Math.min(maxW, nextX));
                        this.tank.y = Math.max(12, Math.min(maxH, nextY));
                    }

                    // === Логіка ворожих танків ===
                    const now = Date.now();
                    for (let i = 0; i < this.enemyTanks.length; i++) {
                        let et = this.enemyTanks[i];
                        if (et.hit) continue;

                        // Повертаємося до гравця
                        const dx = this.tank.x - et.x;
                        const dy = this.tank.y - et.y;
                        const targetAngle = Math.atan2(dy, dx) * 180 / Math.PI;
                        
                        // Плавний поворот ворога
                        let diff = targetAngle - et.angle;
                        // Нормалізація кута, щоб повертати в найближчу сторону
                        while (diff > 180) diff -= 360;
                        while (diff < -180) diff += 360;
                        
                        if (diff > 2) et.angle += 2;
                        else if (diff < -2) et.angle -= 2;
                        
                        // Рух вперед, якщо дивимося приблизно на гравця
                        if (Math.abs(diff) < 45) {
                            const rad = (et.angle * Math.PI) / 180;
                            et.x += Math.cos(rad) * et.speed;
                            et.y += Math.sin(rad) * et.speed;
                            et.x = Math.max(12, Math.min(maxW, et.x));
                            et.y = Math.max(12, Math.min(maxH, et.y));
                        }

                        // Стрільба ворогів
                        const distToPlayer = Math.hypot(dx, dy);
                        // Якщо гравець в радіусі 400 пікселів і дуло дивиться на нього
                        if (distToPlayer < 400 && Math.abs(diff) < 15 && now - et.lastShoot > 1500) {
                            et.lastShoot = now + Math.random() * 1000; // Рандомізація кулдауну
                            const rad = (et.angle * Math.PI) / 180;
                            this.enemyBullets.push({
                                id: this.nextBulletId++,
                                x: et.x + Math.cos(rad) * 14,
                                y: et.y + Math.sin(rad) * 14,
                                vx: Math.cos(rad) * 7,
                                vy: Math.sin(rad) * 7
                            });
                        }
                    }

                    // === Рух кулей ГРАВЦЯ та зіткнення з ворогами ===
                    for (let i = this.bullets.length - 1; i >= 0; i--) {
                        const b = this.bullets[i];
                        b.x += b.vx;
                        b.y += b.vy;

                        let hitTarget = false;
                        for (let j = 0; j < this.enemyTanks.length; j++) {
                            const et = this.enemyTanks[j];
                            if (!et.hit) {
                                const dist = Math.hypot(b.x - et.x, b.y - et.y);
                                if (dist < 18) { // Радіус танка
                                    et.hit = true;
                                    hitTarget = true;
                                    this.score += 100;
                                    
                                    this.explosions.push({ id: Date.now() + Math.random(), x: et.x, y: et.y, color: '#f59e0b' });
                                    setTimeout(() => {
                                        this.enemyTanks = this.enemyTanks.filter(item => item.id !== et.id);
                                    }, 200);
                                    break;
                                }
                            }
                        }

                        if (hitTarget || b.x < 0 || b.x > maxW + 20 || b.y < 0 || b.y > maxH + 20) {
                            this.bullets.splice(i, 1);
                        }
                    }

                    // === Рух кулей ВОРОГІВ та зіткнення з гравцем ===
                    for (let i = this.enemyBullets.length - 1; i >= 0; i--) {
                        const b = this.enemyBullets[i];
                        b.x += b.vx;
                        b.y += b.vy;

                        let hitPlayer = false;
                        const distToPlayer = Math.hypot(b.x - this.tank.x, b.y - this.tank.y);
                        if (distToPlayer < 18) {
                            hitPlayer = true;
                            this.hp -= 1;
                            this.explosions.push({ id: Date.now() + Math.random(), x: this.tank.x, y: this.tank.y, color: '#ef4444' }); // Червоний вибух
                            
                            if (this.hp <= 0) {
                                this.gameOver = true;
                            }
                        }

                        if (hitPlayer || b.x < 0 || b.x > maxW + 20 || b.y < 0 || b.y > maxH + 20) {
                            this.enemyBullets.splice(i, 1);
                        }
                    }

                    // Очищення вибухів
                    if (this.explosions.length > 5) {
                        this.explosions.shift();
                    }
                }
            };
        }`;

content = content.replace(/function miniTankGame\(\) \{[\s\S]*?<\/script>/, newJs + '\n    </script>');

fs.writeFileSync(file, content);
