<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вхід — Інвентар МВКТЗ</title>
    <meta name="description" content="Сторінка входу в систему обліку техніки МВКТЗ">
    <script src="/tailwind.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        brand: {
                            400: '#818cf8', 500: '#6366f1', 600: '#4f46e5', 700: '#4338ca',
                        },
                        surface: { 800: '#0f172a', 900: '#020617' }
                    }
                }
            }
        }
    </script>
    <style>
        [wire\:loading] { display: none !important; }
        .glow { box-shadow: 0 0 80px rgba(99,102,241,0.15), 0 0 160px rgba(99,102,241,0.05); }
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
