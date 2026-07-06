<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вхід — Інвентар МВКТЗ</title>
    <meta name="description" content="Сторінка входу в систему обліку техніки МВКТЗ">
    <script src="/tailwind.js"></script>
    <script>
        // Init theme before render to prevent flash
        const savedTheme = localStorage.getItem('theme') || 'indigo';
        document.documentElement.setAttribute('data-theme', savedTheme);

        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        brand: {
                            50: 'rgb(var(--brand-50) / <alpha-value>)', 100: 'rgb(var(--brand-100) / <alpha-value>)', 200: 'rgb(var(--brand-200) / <alpha-value>)',
                            300: 'rgb(var(--brand-300) / <alpha-value>)', 400: 'rgb(var(--brand-400) / <alpha-value>)', 500: 'rgb(var(--brand-500) / <alpha-value>)',
                            600: 'rgb(var(--brand-600) / <alpha-value>)', 700: 'rgb(var(--brand-700) / <alpha-value>)', 800: 'rgb(var(--brand-800) / <alpha-value>)', 900: 'rgb(var(--brand-900) / <alpha-value>)',
                        },
                        surface: {
                            50: 'rgb(var(--surface-50) / <alpha-value>)', 100: 'rgb(var(--surface-100) / <alpha-value>)', 200: 'rgb(var(--surface-200) / <alpha-value>)',
                            700: 'rgb(var(--surface-700) / <alpha-value>)', 800: 'rgb(var(--surface-800) / <alpha-value>)', 900: 'rgb(var(--surface-900) / <alpha-value>)',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        :root[data-theme="indigo"] {
            --brand-50: 238 242 255; --brand-100: 224 231 255; --brand-200: 199 210 254;
            --brand-300: 165 180 252; --brand-400: 129 140 248; --brand-500: 99 102 241;
            --brand-600: 79 70 229; --brand-700: 67 56 202; --brand-800: 55 48 163; --brand-900: 49 46 129;
            --brand-rgb: 99, 102, 241;
            
            --surface-50: 248 250 252; --surface-100: 241 245 249; --surface-200: 226 232 240;
            --surface-700: 30 41 59; --surface-800: 15 23 42; --surface-900: 2 6 23;
        }
        :root[data-theme="sky"] {
            --brand-50: 240 249 255; --brand-100: 224 242 254; --brand-200: 186 230 253;
            --brand-300: 125 211 252; --brand-400: 56 189 248; --brand-500: 14 165 233;
            --brand-600: 2 132 199; --brand-700: 3 105 161; --brand-800: 7 89 133; --brand-900: 12 74 110;
            --brand-rgb: 14, 165, 233;
            
            --surface-50: 249 250 251; --surface-100: 243 244 246; --surface-200: 229 231 235;
            --surface-700: 55 65 81; --surface-800: 31 41 55; --surface-900: 17 24 39;
        }

        [wire\:loading] { display: none !important; }
        .glow { box-shadow: 0 0 80px rgba(var(--brand-rgb), 0.15), 0 0 160px rgba(var(--brand-rgb), 0.05); }
        @keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-8px)} }
        .float { animation: float 6s ease-in-out infinite; }
    </style>
    @livewireStyles
</head>
<body class="bg-surface-900 font-sans text-gray-200 antialiased min-h-screen flex items-center justify-center p-4">

    <!-- Background decoration -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-brand-500/10 rounded-full blur-3xl float"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-brand-700/10 rounded-full blur-3xl float" style="animation-delay: -3s;"></div>
    </div>

    {{ $slot }}

    @livewireScripts
</body>
</html>
