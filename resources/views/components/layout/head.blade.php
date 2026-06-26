<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Інвентар МВКТЗ — Панель керування</title>
    <meta name="description" content="Адмін-панель обліку техніки МВКТЗ">
    <script src="/tailwind.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        brand: {
                            50: '#eef2ff', 100: '#e0e7ff', 200: '#c7d2fe',
                            300: '#a5b4fc', 400: '#818cf8', 500: '#6366f1',
                            600: '#4f46e5', 700: '#4338ca', 800: '#3730a3', 900: '#312e81',
                        },
                        surface: {
                            50: '#f8fafc', 100: '#f1f5f9', 200: '#e2e8f0',
                            700: '#1e293b', 800: '#0f172a', 900: '#020617',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        [wire\:loading] { display: none !important; }
        * { scrollbar-width: thin; scrollbar-color: #4f46e5 #1e293b; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #1e293b; }
        ::-webkit-scrollbar-thumb { background: #4f46e5; border-radius: 3px; }

        .sidebar-link { transition: all 0.2s ease; }
        .sidebar-link:hover { background: rgba(99,102,241,0.15); }
        .sidebar-link.active { background: rgba(99,102,241,0.2); border-left: 3px solid #6366f1; }

        .fade-in { animation: fadeIn 0.3s ease-out; }
        @keyframes fadeIn { from { opacity:0; transform:translateY(8px); } to { opacity:1; transform:translateY(0); } }

        .glass { background: rgba(30,41,59,0.7); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); }

        @media (max-width: 768px) {
            .sidebar-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 40; }
        }
    </style>
    @livewireStyles
</head>
