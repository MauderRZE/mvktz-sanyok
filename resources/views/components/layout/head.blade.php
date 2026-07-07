<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Інвентар МВКТЗ — Панель керування</title>
    <meta name="description" content="Адмін-панель обліку техніки МВКТЗ">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script>
        // Init theme before render to prevent flash
        const savedTheme = localStorage.getItem('theme') || 'indigo';
        document.documentElement.setAttribute('data-theme', savedTheme);

        const THEMES = ['indigo','sky','emerald','rose','amber','violet','cyan','orange'];

        window.setTheme = function(slug) {
            document.documentElement.setAttribute('data-theme', slug);
            localStorage.setItem('theme', slug);
        };

        window.toggleTheme = function() {
            const current = document.documentElement.getAttribute('data-theme');
            const idx = THEMES.indexOf(current);
            const next = THEMES[(idx + 1) % THEMES.length];
            window.setTheme(next);
        };
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
        :root[data-theme="emerald"] {
            --brand-50: 236 253 245; --brand-100: 209 250 229; --brand-200: 167 243 208;
            --brand-300: 110 231 183; --brand-400: 52 211 153; --brand-500: 16 185 129;
            --brand-600: 5 150 105; --brand-700: 4 120 87; --brand-800: 6 95 70; --brand-900: 6 78 59;
            --brand-rgb: 16, 185, 129;
            --surface-50: 248 250 252; --surface-100: 241 245 249; --surface-200: 226 232 240;
            --surface-700: 30 41 59; --surface-800: 15 23 42; --surface-900: 2 6 23;
        }
        :root[data-theme="rose"] {
            --brand-50: 255 241 242; --brand-100: 255 228 230; --brand-200: 254 205 211;
            --brand-300: 253 164 175; --brand-400: 251 113 133; --brand-500: 244 63 94;
            --brand-600: 225 29 72; --brand-700: 190 18 60; --brand-800: 159 18 57; --brand-900: 136 19 55;
            --brand-rgb: 244, 63, 94;
            --surface-50: 250 250 250; --surface-100: 244 244 245; --surface-200: 228 228 231;
            --surface-700: 63 63 70; --surface-800: 39 39 42; --surface-900: 24 24 27;
        }
        :root[data-theme="amber"] {
            --brand-50: 255 251 235; --brand-100: 254 243 199; --brand-200: 253 230 138;
            --brand-300: 252 211 77; --brand-400: 251 191 36; --brand-500: 245 158 11;
            --brand-600: 217 119 6; --brand-700: 180 83 9; --brand-800: 146 64 14; --brand-900: 120 53 15;
            --brand-rgb: 245, 158, 11;
            --surface-50: 250 250 249; --surface-100: 245 245 244; --surface-200: 231 229 228;
            --surface-700: 68 64 60; --surface-800: 41 37 36; --surface-900: 28 25 23;
        }
        :root[data-theme="violet"] {
            --brand-50: 245 243 255; --brand-100: 237 233 254; --brand-200: 221 214 254;
            --brand-300: 196 181 253; --brand-400: 167 139 250; --brand-500: 139 92 246;
            --brand-600: 124 58 237; --brand-700: 109 40 217; --brand-800: 91 33 182; --brand-900: 76 29 149;
            --brand-rgb: 139, 92, 246;
            --surface-50: 248 250 252; --surface-100: 241 245 249; --surface-200: 226 232 240;
            --surface-700: 30 41 59; --surface-800: 15 23 42; --surface-900: 2 6 23;
        }
        :root[data-theme="cyan"] {
            --brand-50: 236 254 255; --brand-100: 207 250 254; --brand-200: 165 243 252;
            --brand-300: 103 232 249; --brand-400: 34 211 238; --brand-500: 6 182 212;
            --brand-600: 8 145 178; --brand-700: 14 116 144; --brand-800: 21 94 117; --brand-900: 22 78 99;
            --brand-rgb: 6, 182, 212;
            --surface-50: 250 250 250; --surface-100: 245 245 245; --surface-200: 229 229 229;
            --surface-700: 64 64 64; --surface-800: 38 38 38; --surface-900: 23 23 23;
        }
        :root[data-theme="orange"] {
            --brand-50: 255 247 237; --brand-100: 255 237 213; --brand-200: 254 215 170;
            --brand-300: 253 186 116; --brand-400: 251 146 60; --brand-500: 249 115 22;
            --brand-600: 234 88 12; --brand-700: 194 65 12; --brand-800: 154 52 18; --brand-900: 124 45 18;
            --brand-rgb: 249, 115, 22;
            --surface-50: 250 250 250; --surface-100: 244 244 245; --surface-200: 228 228 231;
            --surface-700: 63 63 70; --surface-800: 39 39 42; --surface-900: 24 24 27;
        }

        [wire\:loading] { display: none !important; }
        * { scrollbar-width: thin; scrollbar-color: rgb(var(--brand-600)) #1e293b; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #1e293b; }
        ::-webkit-scrollbar-thumb { background: rgb(var(--brand-600)); border-radius: 3px; }

        .sidebar-link { transition: all 0.2s ease; }
        .sidebar-link:hover { background: rgba(var(--brand-rgb), 0.15); }
        .sidebar-link.active { background: rgba(var(--brand-rgb), 0.2); border-left: 3px solid var(--brand-500); }

        .fade-in { animation: fadeIn 0.3s ease-out; }
        @keyframes fadeIn { from { opacity:0; transform:translateY(8px); } to { opacity:1; transform:translateY(0); } }

        .glass { background: rgb(var(--surface-800) / 0.7); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); }

        @media (max-width: 768px) {
            .sidebar-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 40; }
        }
    </style>
    @livewireStyles
</head>
