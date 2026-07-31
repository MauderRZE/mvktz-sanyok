<div class="demon-realm">

{{-- 🔥 Демонічні стилі — тільки для цієї сторінки --}}
<style>
    /* ═══ ДЕМОНІЧНА ПАЛІТРА ═══ */
    .demon-realm {
        --demon-blood: #8b0000;
        --demon-crimson: #dc143c;
        --demon-fire: #ff4500;
        --demon-ember: #ff6347;
        --demon-lava: #ff2400;
        --demon-dark: #0a0000;
        --demon-abyss: #1a0505;
        --demon-shadow: #2d0a0a;
        --demon-smoke: #3d1515;
        --demon-ash: #6b3030;
        --demon-bone: #d4a373;
        --demon-glow: 0 0 20px rgba(220, 20, 60, 0.4), 0 0 60px rgba(139, 0, 0, 0.2);
        --demon-glow-strong: 0 0 30px rgba(255, 69, 0, 0.5), 0 0 80px rgba(220, 20, 60, 0.3), 0 0 120px rgba(139, 0, 0, 0.15);
    }

    .demon-realm {
        position: relative;
        min-height: 100vh;
        isolation: isolate;
    }

    /* ═══ ФОНОВИЙ ВОГОНЬ ═══ */
    .demon-realm::before {
        content: '';
        position: fixed;
        inset: 0;
        background: 
            radial-gradient(ellipse at 20% 80%, rgba(139, 0, 0, 0.15) 0%, transparent 50%),
            radial-gradient(ellipse at 80% 20%, rgba(220, 20, 60, 0.1) 0%, transparent 50%),
            radial-gradient(ellipse at 50% 50%, rgba(255, 69, 0, 0.05) 0%, transparent 70%);
        pointer-events: none;
        z-index: 0;
        animation: demon-pulse 8s ease-in-out infinite alternate;
    }

    @keyframes demon-pulse {
        0% { opacity: 0.6; }
        50% { opacity: 1; }
        100% { opacity: 0.7; }
    }

    /* ═══ ЗАГОЛОВОК ═══ */
    .demon-header {
        position: relative;
        padding: 1.5rem 2rem;
        background: linear-gradient(135deg, var(--demon-abyss), var(--demon-shadow), var(--demon-abyss));
        border: 1px solid rgba(220, 20, 60, 0.3);
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: var(--demon-glow);
    }

    .demon-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, transparent, var(--demon-crimson), var(--demon-fire), var(--demon-crimson), transparent);
        animation: demon-scan 3s linear infinite;
    }

    @keyframes demon-scan {
        0% { opacity: 0.5; filter: hue-rotate(0deg); }
        50% { opacity: 1; filter: hue-rotate(15deg); }
        100% { opacity: 0.5; filter: hue-rotate(0deg); }
    }

    .demon-title {
        font-family: 'Inter', sans-serif;
        font-size: 1.5rem;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        background: linear-gradient(135deg, var(--demon-ember), var(--demon-crimson), var(--demon-fire));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        text-shadow: none;
        filter: drop-shadow(0 0 10px rgba(220, 20, 60, 0.5));
    }

    .demon-subtitle {
        color: var(--demon-ash);
        font-size: 0.75rem;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        margin-top: 0.25rem;
    }

    .demon-count {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.375rem 1rem;
        background: rgba(139, 0, 0, 0.3);
        border: 1px solid rgba(220, 20, 60, 0.4);
        border-radius: 9999px;
        color: var(--demon-ember);
        font-size: 0.8rem;
        font-weight: 600;
        box-shadow: inset 0 0 15px rgba(139, 0, 0, 0.2);
    }

    /* ═══ КНОПКА ДОДАТИ ═══ */
    .demon-btn-add {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1.5rem;
        background: linear-gradient(135deg, var(--demon-blood), var(--demon-crimson));
        color: #fff;
        font-size: 0.875rem;
        font-weight: 700;
        border-radius: 0.75rem;
        border: 1px solid rgba(255, 69, 0, 0.3);
        box-shadow: var(--demon-glow), inset 0 1px 0 rgba(255, 255, 255, 0.1);
        transition: all 0.3s ease;
        cursor: pointer;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .demon-btn-add:hover {
        background: linear-gradient(135deg, var(--demon-crimson), var(--demon-fire));
        box-shadow: var(--demon-glow-strong);
        transform: translateY(-1px);
    }

    /* ═══ ФІЛЬТРИ ═══ */
    .demon-filters {
        background: linear-gradient(180deg, var(--demon-abyss), rgba(26, 5, 5, 0.8));
        border: 1px solid rgba(220, 20, 60, 0.2);
        border-radius: 1rem;
        padding: 1.25rem;
        box-shadow: inset 0 0 30px rgba(139, 0, 0, 0.1);
    }

    .demon-filters input[type="text"],
    .demon-filters input[type="search"] {
        background: rgba(10, 0, 0, 0.6) !important;
        border-color: rgba(220, 20, 60, 0.25) !important;
        color: var(--demon-ember) !important;
    }

    .demon-filters input[type="text"]:focus,
    .demon-filters input[type="search"]:focus {
        border-color: var(--demon-crimson) !important;
        box-shadow: 0 0 0 2px rgba(220, 20, 60, 0.2), var(--demon-glow) !important;
    }

    .demon-filters input[type="text"]::placeholder,
    .demon-filters input[type="search"]::placeholder {
        color: var(--demon-ash) !important;
    }

    .demon-reset-btn {
        padding: 0.5rem 1rem;
        background: rgba(139, 0, 0, 0.2);
        border: 1px solid rgba(220, 20, 60, 0.3);
        color: var(--demon-ember);
        font-size: 0.75rem;
        border-radius: 0.75rem;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 0.375rem;
        flex-shrink: 0;
    }

    .demon-reset-btn:hover {
        background: rgba(220, 20, 60, 0.3);
        color: #fff;
        box-shadow: var(--demon-glow);
    }

    /* ═══ ТАБЛИЦЯ ═══ */
    .demon-table-wrap {
        border: 1px solid rgba(220, 20, 60, 0.2);
        border-radius: 1rem;
        overflow: hidden;
        background: linear-gradient(180deg, var(--demon-abyss), var(--demon-dark));
        box-shadow: var(--demon-glow);
    }

    .demon-table-wrap table {
        width: 100%;
    }

    .demon-table-wrap thead tr {
        background: linear-gradient(90deg, var(--demon-shadow), var(--demon-abyss), var(--demon-shadow));
        border-bottom: 2px solid rgba(220, 20, 60, 0.3);
    }

    .demon-table-wrap thead th {
        padding: 0.75rem 1rem;
        text-align: left;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: var(--demon-crimson);
        white-space: nowrap;
        cursor: pointer;
        transition: color 0.2s;
    }

    .demon-table-wrap thead th:hover {
        color: var(--demon-fire);
    }

    .demon-table-wrap tbody tr {
        border-bottom: 1px solid rgba(139, 0, 0, 0.15);
        transition: all 0.2s ease;
    }

    .demon-table-wrap tbody tr:hover {
        background: rgba(139, 0, 0, 0.1);
        box-shadow: inset 0 0 30px rgba(220, 20, 60, 0.05);
    }

    .demon-table-wrap tbody td {
        padding: 0.75rem 1rem;
        font-size: 0.85rem;
        color: #c4a0a0;
    }

    .demon-table-wrap tbody td .demon-id {
        color: var(--demon-ash);
        font-size: 0.75rem;
    }

    .demon-table-wrap tbody td .demon-link-equip {
        color: var(--demon-crimson);
        font-weight: 600;
    }

    .demon-table-wrap tbody td .demon-link-material {
        color: var(--demon-fire);
        font-weight: 600;
    }

    .demon-table-wrap tbody td .demon-attr {
        color: var(--demon-ember);
        font-weight: 500;
    }

    .demon-table-wrap tbody td .demon-unlinked {
        color: var(--demon-smoke);
        font-style: italic;
    }

    /* ═══ МОБІЛЬНІ КАРТКИ ═══ */
    .demon-mobile-card {
        background: linear-gradient(135deg, var(--demon-abyss), var(--demon-shadow));
        border: 1px solid rgba(220, 20, 60, 0.2);
        border-radius: 0.75rem;
        padding: 1rem;
        margin-bottom: 0.75rem;
        box-shadow: inset 0 0 20px rgba(139, 0, 0, 0.08);
        transition: all 0.2s;
    }

    .demon-mobile-card:hover {
        border-color: rgba(220, 20, 60, 0.4);
        box-shadow: var(--demon-glow);
    }

    /* ═══ СОРТУВАННЯ ═══ */
    .demon-sort-arrow {
        color: var(--demon-fire);
        font-weight: 700;
        text-shadow: 0 0 8px rgba(255, 69, 0, 0.6);
    }

    /* ═══ ПЕНТАГРАМА ДЕКОР ═══ */
    .demon-pentagram {
        position: absolute;
        right: 1.5rem;
        top: 50%;
        transform: translateY(-50%);
        opacity: 0.08;
        animation: demon-rotate 30s linear infinite;
    }

    @keyframes demon-rotate {
        from { transform: translateY(-50%) rotate(0deg); }
        to { transform: translateY(-50%) rotate(360deg); }
    }

    /* ═══ ІСКРИ / ЧАСТИНКИ ═══ */
    .demon-embers {
        position: absolute;
        inset: 0;
        pointer-events: none;
        overflow: hidden;
        border-radius: 1rem;
    }

    .demon-ember-particle {
        position: absolute;
        width: 2px;
        height: 2px;
        border-radius: 50%;
        background: var(--demon-fire);
        box-shadow: 0 0 6px var(--demon-crimson), 0 0 12px rgba(255, 69, 0, 0.4);
        animation: ember-rise linear infinite;
        opacity: 0;
    }

    @keyframes ember-rise {
        0% { opacity: 0; transform: translateY(100%) scale(0.5); }
        20% { opacity: 1; }
        80% { opacity: 0.6; }
        100% { opacity: 0; transform: translateY(-20px) scale(0); }
    }

    .demon-ember-particle:nth-child(1) { left: 10%; animation-duration: 3s; animation-delay: 0s; }
    .demon-ember-particle:nth-child(2) { left: 25%; animation-duration: 4s; animation-delay: 0.5s; }
    .demon-ember-particle:nth-child(3) { left: 40%; animation-duration: 3.5s; animation-delay: 1s; }
    .demon-ember-particle:nth-child(4) { left: 55%; animation-duration: 4.5s; animation-delay: 1.5s; }
    .demon-ember-particle:nth-child(5) { left: 70%; animation-duration: 3s; animation-delay: 2s; }
    .demon-ember-particle:nth-child(6) { left: 85%; animation-duration: 4s; animation-delay: 0.3s; }
    .demon-ember-particle:nth-child(7) { left: 95%; animation-duration: 3.2s; animation-delay: 1.8s; }
    .demon-ember-particle:nth-child(8) { left: 5%; animation-duration: 5s; animation-delay: 2.5s; }

    /* ═══ ПОРОЖНІЙ СТАН ═══ */
    .demon-empty {
        text-align: center;
        padding: 3rem;
        color: var(--demon-ash);
        font-style: italic;
    }

    .demon-empty-icon {
        font-size: 2.5rem;
        margin-bottom: 0.75rem;
        filter: drop-shadow(0 0 15px rgba(220, 20, 60, 0.5));
    }

    /* ═══ ACTIONS ═══ */
    .demon-realm .demon-table-wrap button,
    .demon-mobile-card button {
        transition: all 0.2s;
    }

    .demon-realm .demon-table-wrap button:hover,
    .demon-mobile-card button:hover {
        filter: brightness(1.3);
        text-shadow: 0 0 8px rgba(220, 20, 60, 0.5);
    }

    /* ═══ ЧЕКБОКСИ ФІЛЬТРІВ ═══ */
    .demon-filters input[type="checkbox"] {
        background: rgba(10, 0, 0, 0.6) !important;
        border-color: rgba(220, 20, 60, 0.4) !important;
    }

    .demon-filters input[type="checkbox"]:checked {
        background-color: var(--demon-crimson) !important;
        border-color: var(--demon-crimson) !important;
    }

    .demon-filters label {
        color: #c4a0a0 !important;
        transition: color 0.2s;
    }

    .demon-filters label:hover {
        color: var(--demon-ember) !important;
    }

    /* ═══ ДЕКОРАТИВНА РАМКА ═══ */
    .demon-ornament-top, .demon-ornament-bottom {
        height: 3px;
        background: linear-gradient(90deg, transparent 5%, var(--demon-blood) 20%, var(--demon-crimson) 35%, var(--demon-fire) 50%, var(--demon-crimson) 65%, var(--demon-blood) 80%, transparent 95%);
        border-radius: 2px;
        opacity: 0.6;
        margin: 0.5rem 0;
    }

    /* ═══ ДЕМОНІЧНА СЦЕНА (ЗАДНІЙ ФОН) ═══ */
    .demon-background-scene {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        height: 100vh;
        pointer-events: none;
        z-index: 10;
        overflow: hidden;
    }

    /* --- Підлога пекла --- */
    .hell-ground {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 80px;
        background: linear-gradient(0deg, rgba(80,0,0,0.35) 0%, transparent 100%);
    }

    /* --- Бігаючий демон 1 --- */
    .demon-runner-1 {
        position: absolute;
        bottom: 30px;
        animation: demon-run-across-1 18s linear infinite;
        opacity: 0.85;
        filter: drop-shadow(0 0 12px #ff2400);
    }
    @keyframes demon-run-across-1 {
        0%   { transform: translateX(-200px) scaleX(1); }
        49%  { transform: translateX(calc(100vw + 200px)) scaleX(1); }
        50%  { transform: translateX(calc(100vw + 200px)) scaleX(-1); }
        99%  { transform: translateX(-200px) scaleX(-1); }
        100% { transform: translateX(-200px) scaleX(1); }
    }

    /* --- Бігаючий демон 2 (швидший, інша висота) --- */
    .demon-runner-2 {
        position: absolute;
        bottom: 55px;
        animation: demon-run-across-2 12s linear infinite 4s;
        opacity: 0.75;
        filter: drop-shadow(0 0 10px #dc143c);
    }
    @keyframes demon-run-across-2 {
        0%   { transform: translateX(calc(100vw + 150px)) scaleX(-1); }
        49%  { transform: translateX(-150px) scaleX(-1); }
        50%  { transform: translateX(-150px) scaleX(1); }
        99%  { transform: translateX(calc(100vw + 150px)) scaleX(1); }
        100% { transform: translateX(calc(100vw + 150px)) scaleX(-1); }
    }

    /* --- Маленький демон 3 (бігає з вилами) --- */
    .demon-runner-3 {
        position: absolute;
        bottom: 20px;
        animation: demon-run-across-3 22s linear infinite 8s;
        opacity: 0.75;
        filter: drop-shadow(0 0 12px #ff4500);
    }
    @keyframes demon-run-across-3 {
        0%   { transform: translateX(-120px) scaleX(1); }
        49%  { transform: translateX(calc(100vw + 120px)) scaleX(1); }
        50%  { transform: translateX(calc(100vw + 120px)) scaleX(-1); }
        99%  { transform: translateX(-120px) scaleX(-1); }
        100% { transform: translateX(-120px) scaleX(1); }
    }

    /* Анімація ніг демона */
    .demon-legs { animation: leg-run 0.35s steps(2) infinite; transform-origin: top center; }
    @keyframes leg-run {
        0%  { transform: skewX(-8deg); }
        50% { transform: skewX(8deg); }
        100%{ transform: skewX(-8deg); }
    }
    .demon-tail { animation: tail-wag 0.6s ease-in-out infinite alternate; transform-origin: left center; }
    @keyframes tail-wag {
        from { transform: rotate(-15deg); }
        to   { transform: rotate(15deg); }
    }
    .demon-pitchfork { animation: pitchfork-jab 0.7s ease-in-out infinite alternate; transform-origin: bottom center; }
    @keyframes pitchfork-jab {
        from { transform: rotate(-10deg) translateY(0px); }
        to   { transform: rotate(5deg) translateY(-4px); }
    }

    /* --- Сцена з вилами (ліворуч) --- */
    .demon-pitchfork-scene {
        position: absolute;
        left: 6%;
        bottom: 30px;
        opacity: 0.85;
        filter: drop-shadow(0 0 15px rgba(255,69,0,0.8));
        animation: scene-float 4s ease-in-out infinite alternate;
    }
    @keyframes scene-float {
        from { transform: translateY(0); }
        to   { transform: translateY(-6px); }
    }

    /* --- Чавун (праворуч) --- */
    .demon-cauldron-scene {
        position: absolute;
        right: 8%;
        bottom: 20px;
        opacity: 0.85;
        filter: drop-shadow(0 0 16px rgba(255,36,0,0.8));
    }

    /* Бульбашки чавуна */
    .cauldron-bubble {
        animation: bubble-rise 2s ease-in infinite;
        transform-origin: center bottom;
    }
    .cauldron-bubble:nth-child(2) { animation-delay: 0.7s; }
    .cauldron-bubble:nth-child(3) { animation-delay: 1.4s; }
    @keyframes bubble-rise {
        0%   { transform: translateY(0) scale(0.5); opacity: 0.8; }
        60%  { opacity: 0.9; }
        100% { transform: translateY(-28px) scale(1.3); opacity: 0; }
    }
    /* Людина в чавуні */
    .cauldron-victim { animation: victim-bob 1.2s ease-in-out infinite alternate; }
    @keyframes victim-bob {
        from { transform: translateY(0); }
        to   { transform: translateY(-4px); }
    }
    /* Демон-кухар помішує */
    .demon-stir { animation: stir-anim 1s ease-in-out infinite alternate; transform-origin: 10px 50px; }
    @keyframes stir-anim {
        from { transform: rotate(-18deg); }
        to   { transform: rotate(12deg); }
    }

    /* --- Другий бігун з вилами --- */
    .demon-pitchfork-scene-right {
        position: absolute;
        right: 22%;
        bottom: 28px;
        opacity: 0.75;
        filter: drop-shadow(0 0 12px rgba(220,20,60,0.7));
        animation: scene-float 5.5s ease-in-out infinite alternate-reverse;
    }

    /* --- Полум'я знизу --- */
    .hell-flame {
        position: absolute;
        bottom: 0;
        animation: flame-flicker 1.5s ease-in-out infinite alternate;
        opacity: 0.85;
    }
    .hell-flame:nth-child(2) { animation-delay: 0.3s; left: 15% !important; }
    .hell-flame:nth-child(3) { animation-delay: 0.6s; left: 30% !important; }
    .hell-flame:nth-child(4) { animation-delay: 0.9s; left: 50% !important; }
    .hell-flame:nth-child(5) { animation-delay: 1.1s; left: 65% !important; }
    .hell-flame:nth-child(6) { animation-delay: 0.4s; left: 80% !important; }
    @keyframes flame-flicker {
        from { transform: scaleY(1) scaleX(1); opacity: 0.18; }
        to   { transform: scaleY(1.25) scaleX(0.88); opacity: 0.28; }
    }
</style>

    {{-- ════════════════════════════════════════════════════
     🔥 ДЕМОНІЧНА СЦЕНА ЗАДНЬОГО ФОНУ
     Демони бігають, накалюють людей на вила, варять у чавунах
     ════════════════════════════════════════════════════ --}}
<template x-teleport="body">
    <div class="demon-background-scene" aria-hidden="true">

    {{-- Полум'я по підлозі --}}
    <svg class="hell-flame" style="left:5%" width="40" height="60" viewBox="0 0 40 60">
        <path d="M20 58 Q8 45 12 30 Q6 38 10 20 Q15 30 18 15 Q20 28 24 12 Q26 28 30 22 Q32 38 28 30 Q34 45 20 58Z" fill="url(#fg1)"/>
        <defs><radialGradient id="fg1" cx="50%" cy="80%"><stop offset="0%" stop-color="#ff6600"/><stop offset="100%" stop-color="#8b0000" stop-opacity="0"/></radialGradient></defs>
    </svg>
    <svg class="hell-flame" style="left:15%" width="30" height="45" viewBox="0 0 30 45">
        <path d="M15 43 Q5 33 8 22 Q4 28 7 14 Q11 22 13 10 Q15 20 18 8 Q20 20 23 16 Q25 28 20 22 Q24 33 15 43Z" fill="url(#fg2)"/>
        <defs><radialGradient id="fg2" cx="50%" cy="80%"><stop offset="0%" stop-color="#ff4500"/><stop offset="100%" stop-color="#dc143c" stop-opacity="0"/></radialGradient></defs>
    </svg>
    <svg class="hell-flame" style="left:30%" width="50" height="70" viewBox="0 0 50 70">
        <path d="M25 68 Q10 52 14 36 Q7 46 12 24 Q18 36 22 18 Q25 34 30 14 Q32 34 38 28 Q42 46 35 36 Q42 52 25 68Z" fill="url(#fg3)"/>
        <defs><radialGradient id="fg3" cx="50%" cy="80%"><stop offset="0%" stop-color="#ff2400"/><stop offset="100%" stop-color="#8b0000" stop-opacity="0"/></radialGradient></defs>
    </svg>
    <svg class="hell-flame" style="left:50%" width="35" height="55" viewBox="0 0 35 55">
        <path d="M17 53 Q6 40 9 27 Q4 34 8 18 Q13 28 15 13 Q17 25 21 10 Q23 25 27 20 Q29 34 24 27 Q29 40 17 53Z" fill="url(#fg4)"/>
        <defs><radialGradient id="fg4" cx="50%" cy="80%"><stop offset="0%" stop-color="#ff6600"/><stop offset="100%" stop-color="#dc143c" stop-opacity="0"/></radialGradient></defs>
    </svg>
    <svg class="hell-flame" style="left:65%" width="45" height="65" viewBox="0 0 45 65">
        <path d="M22 63 Q9 49 12 33 Q6 42 11 22 Q16 34 20 16 Q22 30 27 12 Q29 30 34 25 Q37 42 30 33 Q37 49 22 63Z" fill="url(#fg5)"/>
        <defs><radialGradient id="fg5" cx="50%" cy="80%"><stop offset="0%" stop-color="#ff4500"/><stop offset="100%" stop-color="#8b0000" stop-opacity="0"/></radialGradient></defs>
    </svg>
    <svg class="hell-flame" style="left:80%" width="38" height="58" viewBox="0 0 38 58">
        <path d="M19 56 Q7 43 10 29 Q5 37 9 20 Q14 30 17 14 Q19 27 23 11 Q25 27 29 22 Q31 37 26 29 Q32 43 19 56Z" fill="url(#fg6)"/>
        <defs><radialGradient id="fg6" cx="50%" cy="80%"><stop offset="0%" stop-color="#ff2400"/><stop offset="100%" stop-color="#dc143c" stop-opacity="0"/></radialGradient></defs>
    </svg>

    <div class="hell-ground"></div>

    {{-- ══ СЦЕНА 1: Демон накалює людину на вила (ліворуч) ══ --}}
    <div class="demon-pitchfork-scene">
        <svg width="130" height="120" viewBox="0 0 130 120">
            {{-- Людина на вилах --}}
            {{-- тіло жертви --}}
            <g transform="translate(20, 10)">
                {{-- голова --}}
                <circle cx="20" cy="8" r="7" fill="#c4a373" stroke="#8b5e3c" stroke-width="1"/>
                {{-- рот відкритий (кричить) --}}
                <ellipse cx="20" cy="10" rx="3" ry="2" fill="#3d0000"/>
                {{-- руки вгору (жах) --}}
                <line x1="20" y1="15" x2="5" y2="5" stroke="#c4a373" stroke-width="3" stroke-linecap="round"/>
                <line x1="20" y1="15" x2="35" y2="5" stroke="#c4a373" stroke-width="3" stroke-linecap="round"/>
                {{-- тулуб --}}
                <rect x="14" y="15" width="12" height="18" rx="3" fill="#8b6355" stroke="#5c3d2e" stroke-width="1"/>
                {{-- ноги звисають --}}
                <line x1="17" y1="33" x2="13" y2="50" stroke="#8b6355" stroke-width="4" stroke-linecap="round"/>
                <line x1="23" y1="33" x2="27" y2="48" stroke="#8b6355" stroke-width="4" stroke-linecap="round"/>
            </g>

            {{-- Вила (проходять крізь тіло) --}}
            <g class="demon-pitchfork" transform="translate(25,0)">
                {{-- держак --}}
                <rect x="34" y="28" width="4" height="80" rx="2" fill="#5c3d1e"/>
                {{-- зубці вил --}}
                <rect x="24" y="10" width="3" height="30" rx="1.5" fill="#888"/>
                <rect x="34" y="10" width="3" height="30" rx="1.5" fill="#aaa"/>
                <rect x="44" y="10" width="3" height="30" rx="1.5" fill="#888"/>
                {{-- поперечина --}}
                <rect x="22" y="35" width="28" height="3" rx="1.5" fill="#666"/>
                {{-- кров --}}
                <ellipse cx="36" cy="36" rx="4" ry="2" fill="#8b0000" opacity="0.7"/>
                <line x1="35" y1="36" x2="33" y2="50" stroke="#8b0000" stroke-width="1.5" opacity="0.6"/>
            </g>

            {{-- Демон з вилами --}}
            <g transform="translate(60, 20)">
                {{-- тіло демона --}}
                <ellipse cx="22" cy="40" rx="16" ry="20" fill="#2d0a0a"/>
                <rect x="8" y="30" width="28" height="30" rx="5" fill="#3d1010"/>
                {{-- голова --}}
                <ellipse cx="22" cy="18" rx="14" ry="13" fill="#2d0a0a"/>
                {{-- роги --}}
                <polygon points="12,8 8,0 16,10" fill="#8b0000"/>
                <polygon points="32,8 36,0 28,10" fill="#8b0000"/>
                {{-- очі --}}
                <ellipse cx="16" cy="17" rx="4" ry="4" fill="#ff4500"/>
                <ellipse cx="28" cy="17" rx="4" ry="4" fill="#ff4500"/>
                <circle cx="16" cy="17" r="2" fill="#ff0000"/>
                <circle cx="28" cy="17" r="2" fill="#ff0000"/>
                {{-- зуби --}}
                <path d="M14 24 L16 28 L18 24 L20 28 L22 24 L24 28 L26 24 L28 28 L30 24" stroke="#ccc" stroke-width="1" fill="none"/>
                {{-- хвіст --}}
                <g class="demon-tail" transform="translate(0,40)">
                    <path d="M8 20 Q-5 25 -8 35 Q-5 30 0 35" stroke="#8b0000" stroke-width="3" fill="none" stroke-linecap="round"/>
                    <polygon points="-8,35 -4,38 -3,32" fill="#dc143c"/>
                </g>
                {{-- руки --}}
                <line x1="8" y1="35" x2="-5" y2="55" stroke="#2d0a0a" stroke-width="5" stroke-linecap="round"/>
                <line x1="36" y1="35" x2="48" y2="50" stroke="#2d0a0a" stroke-width="5" stroke-linecap="round"/>
                {{-- ноги --}}
                <g class="demon-legs">
                    <line x1="14" y1="60" x2="10" y2="85" stroke="#2d0a0a" stroke-width="6" stroke-linecap="round"/>
                    <line x1="30" y1="60" x2="34" y2="85" stroke="#2d0a0a" stroke-width="6" stroke-linecap="round"/>
                    {{-- копита --}}
                    <ellipse cx="10" cy="85" rx="6" ry="3" fill="#1a0000"/>
                    <ellipse cx="34" cy="85" rx="6" ry="3" fill="#1a0000"/>
                </g>
                {{-- крила --}}
                <path d="M8 30 Q-15 15 -20 5 Q-10 15 8 35" fill="#1a0505" stroke="#8b0000" stroke-width="1"/>
                <path d="M36 30 Q55 15 60 5 Q52 15 36 35" fill="#1a0505" stroke="#8b0000" stroke-width="1"/>
            </g>
        </svg>
    </div>

    {{-- ══ СЦЕНА 2: Демон варить людину в чавуні (праворуч) ══ --}}
    <div class="demon-cauldron-scene">
        <svg width="180" height="140" viewBox="0 0 180 140">

            {{-- Чавун --}}
            <g transform="translate(20, 50)">
                {{-- вогонь під чавуном --}}
                <ellipse cx="55" cy="82" rx="35" ry="8" fill="#ff4500" opacity="0.5"/>
                <path d="M30 80 Q35 60 40 70 Q45 50 50 65 Q55 45 60 62 Q65 50 70 68 Q75 60 80 78" fill="#ff2400" opacity="0.6"/>
                <path d="M35 80 Q40 65 45 72 Q50 55 55 68 Q60 52 65 70 Q70 58 75 78" fill="#ff6600" opacity="0.5"/>

                {{-- ніжки --}}
                <rect x="28" y="74" width="6" height="12" rx="2" fill="#333"/>
                <rect x="75" y="74" width="6" height="12" rx="2" fill="#333"/>

                {{-- тіло чавуна --}}
                <path d="M15 35 Q10 70 20 78 L90 78 Q100 70 95 35 Z" fill="#2a2a2a" stroke="#444" stroke-width="2"/>
                <ellipse cx="55" cy="35" rx="42" ry="12" fill="#333" stroke="#444" stroke-width="1.5"/>

                {{-- кипляча рідина --}}
                <ellipse cx="55" cy="35" rx="38" ry="9" fill="#8b0000" opacity="0.8"/>
                <path d="M18 34 Q30 28 42 34 Q54 28 66 34 Q78 28 90 34" stroke="#dc143c" stroke-width="2" fill="none" opacity="0.6"/>

                {{-- Бульбашки --}}
                <circle class="cauldron-bubble" cx="35" cy="33" r="4" fill="#ff4500" opacity="0.7"/>
                <circle class="cauldron-bubble" cx="55" cy="30" r="5" fill="#ff2400" opacity="0.6"/>
                <circle class="cauldron-bubble" cx="72" cy="33" r="3" fill="#ff6600" opacity="0.7"/>

                {{-- Жертва в чавуні (голова і руки стирчать) --}}
                <g class="cauldron-victim">
                    {{-- голова --}}
                    <circle cx="55" cy="26" r="9" fill="#c4a373" stroke="#8b5e3c" stroke-width="1"/>
                    {{-- очі (жах) --}}
                    <circle cx="51" cy="24" r="2" fill="white"/>
                    <circle cx="59" cy="24" r="2" fill="white"/>
                    <circle cx="51" cy="25" r="1" fill="#000"/>
                    <circle cx="59" cy="25" r="1" fill="#000"/>
                    {{-- відкритий рот --}}
                    <path d="M50 30 Q55 35 60 30" stroke="#3d0000" stroke-width="1.5" fill="none"/>
                    <ellipse cx="55" cy="31" rx="4" ry="2.5" fill="#3d0000" opacity="0.8"/>
                    {{-- руки --}}
                    <line x1="47" y1="30" x2="30" y2="22" stroke="#c4a373" stroke-width="3" stroke-linecap="round"/>
                    <line x1="63" y1="30" x2="80" y2="22" stroke="#c4a373" stroke-width="3" stroke-linecap="round"/>
                    {{-- долоні --}}
                    <circle cx="30" cy="21" r="4" fill="#c4a373"/>
                    <circle cx="80" cy="21" r="4" fill="#c4a373"/>
                </g>
            </g>

            {{-- Демон-кухар помішує --}}
            <g transform="translate(110, 10)">
                {{-- тіло --}}
                <rect x="8" y="35" width="26" height="32" rx="4" fill="#2d0a0a"/>
                {{-- голова --}}
                <ellipse cx="21" cy="22" rx="14" ry="13" fill="#2d0a0a"/>
                {{-- роги --}}
                <polygon points="11,13 7,3 16,14" fill="#8b0000"/>
                <polygon points="31,13 35,3 26,14" fill="#8b0000"/>
                {{-- очі (задоволені) --}}
                <ellipse cx="15" cy="21" rx="4" ry="3" fill="#ff4500"/>
                <ellipse cx="27" cy="21" rx="4" ry="3" fill="#ff4500"/>
                <circle cx="15" cy="21" r="1.5" fill="#ff0000"/>
                <circle cx="27" cy="21" r="1.5" fill="#ff0000"/>
                {{-- усмішка --}}
                <path d="M12 28 Q21 34 30 28" stroke="#dc143c" stroke-width="2" fill="none" stroke-linecap="round"/>
                {{-- зуби --}}
                <path d="M14 28 L15 31 L17 28 L19 31 L21 28 L23 31 L25 28 L27 31 L28 28" stroke="#ddd" stroke-width="1" fill="none"/>
                {{-- хвіст --}}
                <g class="demon-tail">
                    <path d="M8 55 Q-4 62 -6 72" stroke="#8b0000" stroke-width="3" fill="none" stroke-linecap="round"/>
                    <polygon points="-6,72 -2,76 1,70" fill="#dc143c"/>
                </g>
                {{-- рука з ложкою (помішує) --}}
                <g class="demon-stir">
                    <line x1="8" y1="45" x2="-20" y2="80" stroke="#2d0a0a" stroke-width="5" stroke-linecap="round"/>
                    {{-- ложка --}}
                    <ellipse cx="-23" cy="84" rx="6" ry="4" fill="#888" stroke="#555" stroke-width="1"/>
                    <line x1="-20" y1="80" x2="-23" y2="84" stroke="#888" stroke-width="2"/>
                </g>
                {{-- рука інша --}}
                <line x1="34" y1="45" x2="45" y2="55" stroke="#2d0a0a" stroke-width="5" stroke-linecap="round"/>
                {{-- ноги --}}
                <line x1="14" y1="67" x2="10" y2="90" stroke="#2d0a0a" stroke-width="5" stroke-linecap="round"/>
                <line x1="28" y1="67" x2="32" y2="90" stroke="#2d0a0a" stroke-width="5" stroke-linecap="round"/>
                <ellipse cx="10" cy="90" rx="5" ry="3" fill="#1a0000"/>
                <ellipse cx="32" cy="90" rx="5" ry="3" fill="#1a0000"/>
                {{-- крило --}}
                <path d="M8 40 Q-8 25 -12 15 Q-4 22 8 42" fill="#1a0505" stroke="#8b0000" stroke-width="1"/>
            </g>
        </svg>
    </div>

    {{-- ══ СЦЕНА 3: Демон з вилами праворуч (дзеркальний) ══ --}}
    <div class="demon-pitchfork-scene-right">
        <svg width="110" height="110" viewBox="0 0 110 110" style="transform: scaleX(-1);">
            {{-- людина --}}
            <g transform="translate(15, 5)">
                <circle cx="20" cy="8" r="7" fill="#c4a373" stroke="#8b5e3c" stroke-width="1"/>
                <ellipse cx="20" cy="11" rx="3" ry="2" fill="#3d0000"/>
                <line x1="20" y1="15" x2="4" y2="6" stroke="#c4a373" stroke-width="3" stroke-linecap="round"/>
                <line x1="20" y1="15" x2="36" y2="7" stroke="#c4a373" stroke-width="3" stroke-linecap="round"/>
                <rect x="14" y="15" width="12" height="18" rx="3" fill="#6b4030" stroke="#5c3d2e" stroke-width="1"/>
                <line x1="17" y1="33" x2="14" y2="50" stroke="#6b4030" stroke-width="4" stroke-linecap="round"/>
                <line x1="23" y1="33" x2="26" y2="48" stroke="#6b4030" stroke-width="4" stroke-linecap="round"/>
            </g>
            {{-- вила --}}
            <g class="demon-pitchfork" transform="translate(22,0)">
                <rect x="34" y="28" width="4" height="70" rx="2" fill="#5c3d1e"/>
                <rect x="24" y="10" width="3" height="28" rx="1.5" fill="#888"/>
                <rect x="34" y="10" width="3" height="28" rx="1.5" fill="#aaa"/>
                <rect x="44" y="10" width="3" height="28" rx="1.5" fill="#888"/>
                <rect x="22" y="33" width="28" height="3" rx="1.5" fill="#666"/>
                <ellipse cx="36" cy="33" rx="4" ry="2" fill="#8b0000" opacity="0.7"/>
            </g>
            {{-- демон --}}
            <g transform="translate(55, 18)">
                <rect x="6" y="30" width="26" height="28" rx="5" fill="#3d1010"/>
                <ellipse cx="20" cy="18" rx="13" ry="12" fill="#2d0a0a"/>
                <polygon points="10,9 6,1 14,10" fill="#8b0000"/>
                <polygon points="30,9 34,1 26,10" fill="#8b0000"/>
                <ellipse cx="14" cy="17" rx="4" ry="4" fill="#ff4500"/>
                <ellipse cx="26" cy="17" rx="4" ry="4" fill="#ff4500"/>
                <circle cx="14" cy="17" r="2" fill="#ff0000"/>
                <circle cx="26" cy="17" r="2" fill="#ff0000"/>
                <path d="M12 23 L14 27 L16 23 L18 27 L20 23 L22 27 L24 23 L26 27 L28 23" stroke="#ccc" stroke-width="1" fill="none"/>
                <g class="demon-tail" transform="translate(0,38)">
                    <path d="M6 18 Q-5 23 -7 32" stroke="#8b0000" stroke-width="3" fill="none" stroke-linecap="round"/>
                    <polygon points="-7,32 -3,36 -2,30" fill="#dc143c"/>
                </g>
                <line x1="6" y1="34" x2="-5" y2="52" stroke="#2d0a0a" stroke-width="5" stroke-linecap="round"/>
                <line x1="34" y1="34" x2="44" y2="48" stroke="#2d0a0a" stroke-width="5" stroke-linecap="round"/>
                <g class="demon-legs">
                    <line x1="12" y1="58" x2="8" y2="80" stroke="#2d0a0a" stroke-width="5" stroke-linecap="round"/>
                    <line x1="28" y1="58" x2="32" y2="80" stroke="#2d0a0a" stroke-width="5" stroke-linecap="round"/>
                    <ellipse cx="8" cy="80" rx="5" ry="3" fill="#1a0000"/>
                    <ellipse cx="32" cy="80" rx="5" ry="3" fill="#1a0000"/>
                </g>
                <path d="M6 28 Q-12 14 -16 4 Q-8 13 6 30" fill="#1a0505" stroke="#8b0000" stroke-width="1"/>
                <path d="M34 28 Q50 14 54 4 Q47 13 34 30" fill="#1a0505" stroke="#8b0000" stroke-width="1"/>
            </g>
        </svg>
    </div>

    {{-- ══ Бігаючий демон 1 ══ --}}
    <div class="demon-runner-1">
        <svg width="80" height="90" viewBox="0 0 80 90">
            {{-- тіло --}}
            <rect x="22" y="32" width="22" height="28" rx="5" fill="#3d1010"/>
            {{-- голова --}}
            <ellipse cx="33" cy="20" rx="13" ry="12" fill="#2d0a0a"/>
            {{-- роги --}}
            <polygon points="23,12 19,3 27,13" fill="#8b0000"/>
            <polygon points="43,12 47,3 39,13" fill="#8b0000"/>
            {{-- очі --}}
            <ellipse cx="27" cy="19" rx="4" ry="4" fill="#ff4500"/>
            <ellipse cx="39" cy="19" rx="4" ry="4" fill="#ff4500"/>
            <circle cx="27" cy="19" r="2" fill="#900"/>
            <circle cx="39" cy="19" r="2" fill="#900"/>
            {{-- зуби --}}
            <path d="M24 26 L26 30 L28 26 L30 30 L32 26 L34 30 L36 26 L38 30 L40 26" stroke="#ccc" stroke-width="1" fill="none"/>
            {{-- хвіст --}}
            <g class="demon-tail" transform="translate(0,40)">
                <path d="M22 20 Q10 27 7 38" stroke="#8b0000" stroke-width="3" fill="none" stroke-linecap="round"/>
                <polygon points="7,38 11,42 13,36" fill="#dc143c"/>
            </g>
            {{-- руки (розкинуті при бігу) --}}
            <line x1="22" y1="38" x2="5" y2="32" stroke="#2d0a0a" stroke-width="5" stroke-linecap="round"/>
            <line x1="44" y1="38" x2="62" y2="30" stroke="#2d0a0a" stroke-width="5" stroke-linecap="round"/>
            {{-- ноги (біг) --}}
            <g class="demon-legs">
                <line x1="27" y1="60" x2="15" y2="82" stroke="#2d0a0a" stroke-width="6" stroke-linecap="round"/>
                <line x1="39" y1="60" x2="52" y2="80" stroke="#2d0a0a" stroke-width="6" stroke-linecap="round"/>
                <ellipse cx="15" cy="82" rx="7" ry="3" fill="#1a0000"/>
                <ellipse cx="52" cy="80" rx="7" ry="3" fill="#1a0000"/>
            </g>
            {{-- крила --}}
            <path d="M22 36 Q4 20 0 8 Q8 18 22 38" fill="#1a0505" stroke="#8b0000" stroke-width="1"/>
            <path d="M44 36 Q62 18 68 6 Q60 16 44 38" fill="#1a0505" stroke="#8b0000" stroke-width="1"/>
        </svg>
    </div>

    {{-- ══ Бігаючий демон 2 (менший, із вилами) ══ --}}
    <div class="demon-runner-2">
        <svg width="65" height="75" viewBox="0 0 65 75">
            {{-- держить вили --}}
            <g class="demon-pitchfork" transform="translate(50, 5) rotate(25)">
                <rect x="0" y="20" width="3" height="45" rx="1.5" fill="#5c3d1e"/>
                <rect x="-6" y="5" width="2.5" height="22" rx="1" fill="#888"/>
                <rect x="0" y="5" width="2.5" height="22" rx="1" fill="#aaa"/>
                <rect x="6" y="5" width="2.5" height="22" rx="1" fill="#888"/>
                <rect x="-7" y="24" width="20" height="2.5" rx="1" fill="#666"/>
            </g>
            {{-- тіло --}}
            <rect x="18" y="28" width="20" height="24" rx="4" fill="#3d1010"/>
            {{-- голова --}}
            <ellipse cx="28" cy="17" rx="11" ry="10" fill="#2d0a0a"/>
            {{-- роги --}}
            <polygon points="19,10 16,2 23,11" fill="#8b0000"/>
            <polygon points="37,10 40,2 33,11" fill="#8b0000"/>
            {{-- очі --}}
            <ellipse cx="23" cy="16" rx="3.5" ry="3.5" fill="#ff4500"/>
            <ellipse cx="33" cy="16" rx="3.5" ry="3.5" fill="#ff4500"/>
            <circle cx="23" cy="16" r="1.5" fill="#900"/>
            <circle cx="33" cy="16" r="1.5" fill="#900"/>
            {{-- хвіст --}}
            <g class="demon-tail" transform="translate(0,35)">
                <path d="M18 18 Q8 23 6 32" stroke="#8b0000" stroke-width="2.5" fill="none" stroke-linecap="round"/>
                <polygon points="6,32 9,35 11,30" fill="#dc143c"/>
            </g>
            {{-- руки --}}
            <line x1="18" y1="35" x2="4" y2="28" stroke="#2d0a0a" stroke-width="4" stroke-linecap="round"/>
            <line x1="38" y1="33" x2="53" y2="24" stroke="#2d0a0a" stroke-width="4" stroke-linecap="round"/>
            {{-- ноги (біг) --}}
            <g class="demon-legs">
                <line x1="23" y1="52" x2="12" y2="70" stroke="#2d0a0a" stroke-width="5" stroke-linecap="round"/>
                <line x1="33" y1="52" x2="44" y2="68" stroke="#2d0a0a" stroke-width="5" stroke-linecap="round"/>
                <ellipse cx="12" cy="70" rx="6" ry="3" fill="#1a0000"/>
                <ellipse cx="44" cy="68" rx="6" ry="3" fill="#1a0000"/>
            </g>
        </svg>
    </div>

    {{-- ══ Бігаючий демон 3 (маленький, швидкий) ══ --}}
    <div class="demon-runner-3">
        <svg width="55" height="65" viewBox="0 0 55 65">
            <rect x="14" y="24" width="18" height="20" rx="4" fill="#3d1010"/>
            <ellipse cx="23" cy="14" rx="10" ry="9" fill="#2d0a0a"/>
            <polygon points="15,8 12,1 19,9" fill="#8b0000"/>
            <polygon points="31,8 34,1 27,9" fill="#8b0000"/>
            <ellipse cx="19" cy="13" rx="3" ry="3" fill="#ff4500"/>
            <ellipse cx="27" cy="13" rx="3" ry="3" fill="#ff4500"/>
            <circle cx="19" cy="13" r="1.5" fill="#f00"/>
            <circle cx="27" cy="13" r="1.5" fill="#f00"/>
            <path d="M16 20 L18 23 L20 20 L22 23 L24 20 L26 23 L28 20" stroke="#bbb" stroke-width="1" fill="none"/>
            <g class="demon-tail" transform="translate(0,30)">
                <path d="M14 15 Q5 20 3 28" stroke="#8b0000" stroke-width="2" fill="none" stroke-linecap="round"/>
                <polygon points="3,28 6,31 8,26" fill="#dc143c"/>
            </g>
            <line x1="14" y1="30" x2="2" y2="24" stroke="#2d0a0a" stroke-width="4" stroke-linecap="round"/>
            <line x1="32" y1="30" x2="44" y2="22" stroke="#2d0a0a" stroke-width="4" stroke-linecap="round"/>
            <g class="demon-legs">
                <line x1="18" y1="44" x2="8" y2="60" stroke="#2d0a0a" stroke-width="5" stroke-linecap="round"/>
                <line x1="28" y1="44" x2="38" y2="58" stroke="#2d0a0a" stroke-width="5" stroke-linecap="round"/>
                <ellipse cx="8" cy="60" rx="5" ry="2.5" fill="#1a0000"/>
                <ellipse cx="38" cy="58" rx="5" ry="2.5" fill="#1a0000"/>
            </g>
        </svg>
    </div>
</template>

    <div class="space-y-6 relative" style="z-index: 9999;">
    <x-ui.flash />

    {{-- ═══ ДЕМОНІЧНИЙ ЗАГОЛОВОК ═══ --}}
    <div class="demon-header">
        {{-- Іскри --}}
        <div class="demon-embers">
            <div class="demon-ember-particle"></div>
            <div class="demon-ember-particle"></div>
            <div class="demon-ember-particle"></div>
            <div class="demon-ember-particle"></div>
            <div class="demon-ember-particle"></div>
            <div class="demon-ember-particle"></div>
            <div class="demon-ember-particle"></div>
            <div class="demon-ember-particle"></div>
        </div>

        {{-- Пентаграма --}}
        <svg class="demon-pentagram" width="80" height="80" viewBox="0 0 100 100" fill="none" stroke="currentColor" style="color: var(--demon-crimson);">
            <circle cx="50" cy="50" r="45" stroke-width="1.5"/>
            <polygon points="50,5 61.8,38.2 97.6,38.2 69,61.8 80.9,95 50,73.6 19.1,95 31,61.8 2.4,38.2 38.2,38.2" stroke-width="1.5"/>
        </svg>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 relative z-10">
            <div>
                <div class="flex items-center gap-3">
                    <span style="font-size: 1.75rem; filter: drop-shadow(0 0 10px rgba(220,20,60,0.6));"></span>
                    <h1 class="demon-title">властивості</h1>
                </div>
                <p class="demon-subtitle">Realm of dynamic attributes</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="demon-count">
                    <span></span>
                    <span>{{ count($properties) }} душ</span>
                </div>
                <button wire:click="create()" class="demon-btn-add">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Призвати
                </button>
            </div>
        </div>
    </div>

    <div class="demon-ornament-top"></div>

    {{-- ═══ ФІЛЬТРИ ═══ --}}
    <div class="demon-filters">
        <div class="flex flex-col lg:flex-row gap-4 items-center">
            <div class="flex-1 w-full flex gap-2">
                <div class="flex-1">
                    <x-form.search wire:model.blur="search" x-on:keydown.enter="$el.blur()" placeholder="🔍 Шукати в безодні... інв. номер, характеристика, значення..." />
                </div>
                @if($search !== '' || !empty($filterAttribute))
                    <button wire:click="resetFilters" class="demon-reset-btn" title="Скинути всі фільтри">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        <span>Очистити</span>
                    </button>
                @endif
            </div>
            <div class="w-full lg:w-auto">
                <x-form.multi-select label="Характеристика" :selectedCount="count($filterAttribute)">
                    <label class="flex items-center gap-2 text-xs cursor-pointer py-1 font-semibold text-amber-400">
                        <input type="checkbox" value="null" wire:model.live="filterAttribute" class="rounded border-white/10 bg-surface-900 text-brand-500 focus:ring-0 focus:ring-offset-0">
                        <span>[Без характеристики / Null]</span>
                    </label>
                    @foreach($dictAttributes as $attr)
                        <label class="flex items-center gap-2 text-xs cursor-pointer py-1">
                            <input type="checkbox" value="{{ $attr->id }}" wire:model.live="filterAttribute" class="rounded border-white/10 bg-surface-900 text-brand-500 focus:ring-0 focus:ring-offset-0">
                            <span>{{ $attr->name }}</span>
                        </label>
                    @endforeach
                </x-form.multi-select>
            </div>
        </div>
    </div>

    @if($isOpen)
    <x-ui.modal title="{{ $form->propertyId ? 'Редагувати' : 'Призвати' }} властивість" maxWidth="md">
        <x-form.select label="Атрибут (Характеристика)" model="form.attribute_id" :options="['' => 'Не вибрано'] + $dictAttributes->pluck('name', 'id')->toArray()" />
        
        <x-form.input label="Значення (наприклад: 16GB, Intel Core i5)" model="form.attr_value" type="text" />
        
        <div class="mt-4 p-4 border border-white/10 rounded-lg bg-white/5 space-y-4">
            <div class="text-sm text-gray-400">Прив'яжіть до активу АБО до матеріалу:</div>
            <x-form.select label="Прив'язка до Обладнання (Assets)" model="form.asset_id" :options="['' => 'Не вибрано'] + $assets->mapWithKeys(function($item) {
                return [$item->id => $item->componentType->component_name . ' (' . ($item->equipment->inv_number ?? 'Немає') . ')'];
            })->toArray()" />
            
            <x-form.select label="Прив'язка до Матеріалу (МШП)" model="form.nomenclature_id" :options="['' => 'Не вибрано'] + $materials->mapWithKeys(function($item) {
                return [$item->id => $item->material_account_name];
            })->toArray()" />
        </div>
    </x-ui.modal>
    @endif

    {{-- ═══ DESKTOP ТАБЛИЦЯ ═══ --}}
    <div class="hidden md:block demon-table-wrap">
        <table class="w-full">
            <thead>
                <tr>
                    <th style="width: 20px;" wire:click="sortBy('id')">
                        <div class="flex items-center gap-1">ID @if($sortField === 'id') <span class="demon-sort-arrow">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif</div>
                    </th>
                    <th wire:click="sortBy('inv_number')">
                        <div class="flex items-center gap-1">Прив'язка / Інв. № @if($sortField === 'inv_number') <span class="demon-sort-arrow">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif</div>
                    </th>
                    <th>Атрибут</th>
                    <th>Значення</th>
                    <th style="width: 32px; text-align: right;">Дії</th>
                </tr>
            </thead>
            <tbody>
                @forelse($properties as $prop)
                <tr>
                    <td><span class="demon-id">#{{ $prop->id }}</span></td>
                    <td>
                        @if($prop->asset_id)
                            <span class="demon-link-equip">⚔️ Обладнання:</span> {{ $prop->asset->componentType->component_name ?? 'N/A' }} <span class="demon-id">(Inv: {{ $prop->asset->equipment->inv_number ?? 'Немає' }})</span>
                        @elseif($prop->nomenclature_id)
                            <span class="demon-link-material">Матеріал:</span> {{ $prop->nomenclature->material_account_name ?? 'N/A' }}
                        @else
                            <span class="demon-unlinked">Не прив'язано</span>
                        @endif
                    </td>
                    <td><span class="demon-attr">{{ $prop->attribute->name ?? 'N/A' }}</span></td>
                    <td>{{ $prop->attr_value }}</td>
                    <td style="text-align: right;">
                        <x-ui.action-buttons id="{{ $prop->id }}" />
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="demon-empty">
                            <div class="demon-empty-icon"></div>
                            <div>Безодня порожня... Жодної властивості не знайдено</div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="demon-ornament-bottom"></div>

    {{-- ═══ MOBILE ═══ --}}
    <div class="md:hidden space-y-3">
        @forelse($properties as $prop)
        <div class="demon-mobile-card">
            <div class="flex items-center justify-between">
                <div>
                    <div class="demon-attr text-sm font-semibold">{{ $prop->attribute->name ?? 'N/A' }}: {{ $prop->attr_value }}</div>
                    <div class="text-xs mt-1" style="color: var(--demon-ash);">
                        @if($prop->asset_id)
                            Обладнання
                        @elseif($prop->nomenclature_id)
                            МШП
                        @else
                            Немає
                        @endif
                    </div>
                </div>
                <x-ui.action-buttons id="{{ $prop->id }}" />
            </div>
        </div>
        @empty
        <div class="demon-empty">
            <div class="demon-empty-icon"></div>
            <div>Безодня порожня...</div>
        </div>
        @endforelse
    </div>
</div>
</div>

