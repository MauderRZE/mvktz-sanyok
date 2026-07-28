<header class="sticky top-0 z-30 glass border-b border-white/5">
    <div class="flex items-center justify-between px-4 sm:px-6 py-3">
        <div class="flex items-center gap-3">
            <button @click="sidebarOpen = true" class="md:hidden text-gray-400 hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <h2 class="text-lg font-semibold text-white">
                @if(request()->routeIs('admin.equipment')) Обладнання
                @elseif(request()->routeIs('admin.employees')) Співробітники
                @elseif(request()->routeIs('admin.categories')) Категорії
                @elseif(request()->routeIs('admin.types')) Моделі техніки
                @elseif(request()->routeIs('admin.users')) Користувачі
                @elseif(request()->routeIs('admin.base-components')) Базові компоненти
                @elseif(request()->routeIs('admin.base-materials')) Базові матеріали
                @elseif(request()->routeIs('admin.suppliers')) Постачальники
                @elseif(request()->routeIs('admin.locations')) Кабінети та локації
                @elseif(request()->routeIs('admin.maintenance-types')) Типи обслуговування
                @elseif(request()->routeIs('admin.contracts')) Договори
                @elseif(request()->routeIs('admin.assets')) Активи
                @elseif(request()->routeIs('admin.complaints')) Скарги та інциденти
                @elseif(request()->routeIs('admin.movements')) Переміщення обладнання
                @elseif(request()->routeIs('admin.low-value-materials')) Малоцінні матеріали
                @elseif(request()->routeIs('admin.maintenance-logs')) Журнал ТО
                @elseif(request()->routeIs('admin.software-licenses')) Ліцензії ПЗ
                @elseif(request()->routeIs('admin.type-requirements')) Шаблони типів
                @elseif(request()->routeIs('admin.history')) Історія та Аудит
                @else Панель керування
                @endif
            </h2>
        </div>
    </div>
</header>
